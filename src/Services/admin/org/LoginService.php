<?php

namespace Airobot\Hyperf\Services\admin\org;

use Airobot\Hyperf\Model\User;
use Airobot\Hyperf\Utils\JwtUtil;
use Airobot\Hyperf\Utils\Tool;
use Hyperf\Di\Annotation\Inject;

class LoginService
{
    #[Inject]
    private JwtUtil $jwtUtil;

    public function login(string $name, string $password): array
    {
        $user = User::query()->where('name', $name)->first();
        if (empty($user)) Tool::E('用户不存在！');
        if ($user->status == 2) Tool::E('用户已被禁用！');
        $p = md5($password . $user->salt);
        if ($user->password != $p) Tool::E('密码错误！');
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'role_id' => $user->role_id
        ];
        $res = $this->jwtUtil->issue($data);
        $res['user'] = $data;
        return $res;
    }
}