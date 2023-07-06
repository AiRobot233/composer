<?php

namespace Airobot\Hyperf\Controller\admin\org;

use Airobot\Hyperf\Middleware\LoginMiddleware;
use Airobot\Hyperf\Services\admin\org\AuthService;
use Airobot\Hyperf\Utils\Tool;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

#[Controller]
#[Middleware(LoginMiddleware::class)]
class AuthController
{
    #[Inject]
    private AuthService $authService;

    #[RequestMapping(path: "/admin/auth", methods: "get")]
    public function auth(): ResponseInterface
    {
        return Tool::OK($this->authService->auth());
    }
}