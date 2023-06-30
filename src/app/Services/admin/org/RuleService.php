<?php

namespace App\Services\admin\org;

use App\Model\Rule;
use App\Utils\Util;
use Hyperf\Di\Annotation\Inject;

class RuleService
{
    #[Inject]
    private Util $util;

    public function list(): array
    {
        $data = Rule::query()->select(['id', 'pid', 'name', 'type', 'router'])->get();
        return $this->util->arrayToTree($data->toArray());
    }

    public function add(array $data): void
    {
        $rule = new Rule();
        $rule->setFromData($data);
        $rule->save();
    }

    public function edit(int $id, array $data): void
    {
        $rule = Rule::query()->where('id', $id)->first();
        $rule->setFromData($data);
        $rule->save();
    }

    public function del(int $id): void
    {
        Rule::destroy($id);
    }
}