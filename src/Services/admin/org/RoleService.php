<?php

namespace Airobot\Hyperf\Services\admin\org;

use Airobot\Hyperf\Model\Role;
use Airobot\Hyperf\Utils\Util;
use Hyperf\Di\Annotation\Inject;

class RoleService
{
    #[Inject]
    private Util $util;

    public function list(): array
    {
        $data = Role::query()->select(['id', 'pid', 'name', 'rule', 'created_at', 'is_system'])->get();
        return $this->util->arrayToTree($data->toArray());
    }

    public function add(array $data): void
    {
        $role = new Role();
        $role->setFromData($data);
        $role->save();
    }

    public function edit(int $id, array $data): void
    {
        $role = Role::query()->where('id', $id)->first();
        $role->setFromData($data);
        $role->save();
    }

    public function del(int $id): void
    {
        Role::destroy($id);
    }
}