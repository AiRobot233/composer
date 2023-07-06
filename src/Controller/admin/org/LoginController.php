<?php

namespace Airobot\Hyperf\Controller\admin\org;

use Airobot\Hyperf\Services\admin\org\LoginService;
use Airobot\Hyperf\Utils\Tool;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;

#[Controller]
class LoginController
{

    #[Inject]
    private LoginService $loginService;

    //登录
    #[RequestMapping(path: "/admin/login", methods: "post")]
    public function login(RequestInterface $request): ResponseInterface
    {
        $name = $request->post('name');
        $password = $request->post('password');
        return Tool::OK($this->loginService->login($name, $password));
    }
}