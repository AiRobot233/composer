<?php

namespace App\Services\admin\org;

use App\Model\Dictionary;
use App\Model\Role;
use App\Model\Rule;
use App\Utils\Tool;
use App\Utils\Util;
use Hyperf\Di\Annotation\Inject;

class SubService
{

    #[Inject]
    private Util $util;

    public function common(string $key, mixed $data): mixed
    {
        if (!method_exists($this, $key)) Tool::E('方法不存在！');
        return $this->$key($data);
    }

    /**
     * 获取规则树状下拉
     * @param string $type
     * @return array
     */
    private function rule(string $type): array
    {
        $data = Rule::query()->when(!empty($type), function ($query) use ($type) {
            return $query->where('type', $type);
        })->select(['id', 'pid', 'name', 'type', 'router'])->get();
        return $this->util->arrayToTree($data->toArray());
    }

    /**
     * 获取角色组树状下拉
     * @return array
     */
    private function role(): array
    {
        $data = Role::query()->select(['id', 'pid', 'name'])->get();
        return $this->util->arrayToTree($data->toArray());
    }

    /**
     * 获取字典树状下拉
     * @return array
     */
    private function dictionary(): array
    {
        $data = Dictionary::query()->select(['id', 'pid', 'name', 'sort', 'value'])->get();
        return $this->util->arrayToTree($data->toArray());
    }
}