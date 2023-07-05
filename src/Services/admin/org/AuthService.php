<?php

namespace App\Services\admin\org;

use App\Model\Role;
use App\Model\Rule;
use App\Utils\Tool;
use App\Utils\Util;
use Hyperf\Context\Context;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class AuthService
{

    #[Inject]
    private Util $util;

    public function auth()
    {
        $user = Context::get('userData');
        $role = Role::query()->where('id', $user['role_id'])->first(['rule', 'is_system']);
        if (empty($role)) Tool::E('角色不存在!');
        if ($role->is_system == 1) {
            $rules = explode(',', $role->rule);
            $rule = Rule::query()->whereIn('id', $rules)->get();
            $roles = Db::select("SELECT b.router,a.operation FROM rule AS b LEFT JOIN (SELECT pid,GROUP_CONCAT(tag) AS operation FROM `rule` WHERE type = 'api' AND id IN ({$role->rule}) GROUP BY pid) AS a ON a.pid = b.id WHERE a.operation IS NOT NULL");
        } else {
            $rule = Rule::query()->get();
            $roles = Db::select("SELECT b.router,a.operation FROM rule AS b LEFT JOIN (SELECT pid,GROUP_CONCAT(tag) AS operation FROM `rule` WHERE type = 'api' GROUP BY pid) AS a ON a.pid = b.id WHERE a.operation IS NOT NULL");
        }
        $tree = $this->util->arrayToTree($rule->toArray());
        return ['routes' => $tree, 'roles' => $roles];
    }
}