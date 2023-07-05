<?php

namespace App\Controller\admin\org;

use App\Middleware\AuthMiddleware;
use App\Middleware\LoginMiddleware;
use App\Request\RoleRequest;
use App\Services\admin\org\RoleService;
use App\Utils\Tool;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

#[Controller]
#[Middlewares([LoginMiddleware::class, AuthMiddleware::class])]
class RoleController
{
    #[Inject]
    private RoleService $roleService;

    #[RequestMapping(path: "/admin/role", methods: "get")]
    public function list(): ResponseInterface
    {
        $data = $this->roleService->list();
        return Tool::OK($data);
    }

    #[RequestMapping(path: "/admin/role", methods: "post")]
    public function add(RoleRequest $request): ResponseInterface
    {
        $data = $request->post();
        $this->roleService->add($data);
        return Tool::OK();
    }

    #[RequestMapping(path: "/admin/role/{id}", methods: "put")]
    public function edit(RoleRequest $request, int $id): ResponseInterface
    {
        $data = $request->getParsedBody();
        $this->roleService->edit($id, $data);
        return Tool::OK();
    }

    #[RequestMapping(path: "/admin/role/{id}", methods: "delete")]
    public function del(int $id): ResponseInterface
    {
        $this->roleService->del($id);
        return Tool::OK();
    }
}