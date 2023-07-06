<?php

namespace Airobot\Hyperf\Controller\admin\org;

use Airobot\Hyperf\Middleware\AntiRepeatMiddleware;
use Airobot\Hyperf\Middleware\AuthMiddleware;
use Airobot\Hyperf\Middleware\LoginMiddleware;
use Airobot\Hyperf\Request\UserRequest;
use Airobot\Hyperf\Services\admin\org\UserService;
use Airobot\Hyperf\Utils\Tool;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;

#[Controller]
#[Middlewares([LoginMiddleware::class, AuthMiddleware::class])]
class UserController
{

    #[Inject]
    private UserService $userService;

    #[RequestMapping(path: "/admin/user", methods: "get")]
    public function list(RequestInterface $request): ResponseInterface
    {
        $size = $request->input('pageSize', 10);
        $name = $request->input('name', '');
        $res = $this->userService->list($size, $name);
        return Tool::OK($res);
    }

    #[Middlewares([AntiRepeatMiddleware::class])]
    #[RequestMapping(path: "/admin/user", methods: "post")]
    public function add(UserRequest $request): ResponseInterface
    {
        $data = $request->post();
        $this->userService->add($data);
        return Tool::OK();
    }

    #[RequestMapping(path: "/admin/user/{id}", methods: "put")]
    public function edit(UserRequest $request, int $id): ResponseInterface
    {
        $data = $request->getParsedBody();
        $this->userService->edit($id, $data);
        return Tool::OK();
    }

    #[RequestMapping(path: "/admin/user/{id}", methods: "delete")]
    public function del(int $id): ResponseInterface
    {
        $this->userService->del($id);
        return Tool::OK();
    }
}