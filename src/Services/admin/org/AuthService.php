<?php

namespace Airobot\Hyperf\Services\admin\org;

use Airobot\Hyperf\Model\Role;
use Airobot\Hyperf\Model\Rule;
use Airobot\Hyperf\Utils\Tool;
use Airobot\Hyperf\Utils\Util;
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
            $rule = Rule::query()->where('type', 'page')->whereIn('id', $rules)->get();
            $roles = Db::select("SELECT b.router,a.operation FROM rule AS b LEFT JOIN (SELECT pid,GROUP_CONCAT(tag) AS operation FROM `rule` WHERE type = 'api' AND id IN ({$role->rule}) GROUP BY pid) AS a ON a.pid = b.id WHERE a.operation IS NOT NULL");
        } else {
            $rule = Rule::query()->where('type', 'page')->get();
            $roles = Db::select("SELECT b.router,a.operation FROM rule AS b LEFT JOIN (SELECT pid,GROUP_CONCAT(tag) AS operation FROM `rule` WHERE type = 'api' GROUP BY pid) AS a ON a.pid = b.id WHERE a.operation IS NOT NULL");
        }
        $tree = $this->util->arrayToTree($rule->toArray());
        return ['routes' => $tree, 'roles' => $roles];
    }
}