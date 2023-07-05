<?php

namespace App\Services\admin\org;

use App\Model\User;
use App\Utils\Util;
use Hyperf\Di\Annotation\Inject;

class UserService
{
    #[Inject]
    private Util $util;

    public function list(int $size, string $name): array
    {
        $data = User::query()
            ->with(['role'])
            ->when(!empty($name), function ($query) use ($name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->select(['id', 'name', 'phone', 'status', 'role_id'])
            ->paginate($size);
        return $this->util->p($data);
    }

    public function add(array $data): void
    {
        $user = new User();
        $user->setFromData($data, $this->util);
        $user->save();
    }

    public function edit(int $id, array $data): void
    {
        $user = User::query()->find($id);
        $user->setFromData($data, $this->util);
        $user->save();
    }

    public function del(int $id): void
    {
        User::destroy($id);
    }
}