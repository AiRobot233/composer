<?php

namespace Airobot\Hyperf\Controller\admin\org;

use Airobot\Hyperf\Middleware\LoginMiddleware;
use Airobot\Hyperf\Services\admin\org\SubService;
use Airobot\Hyperf\Utils\Tool;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;

#[Controller]
#[Middleware(LoginMiddleware::class)]
class SubController
{

    #[Inject]
    private SubService $subService;

    #[RequestMapping(path: "/admin/sub", methods: "post")]
    public function subassembly(RequestInterface $request): ResponseInterface
    {
        $data = $request->post();
        $key = array_key_first($data);
        $res = $this->subService->common($key, $data[$key]);
        return Tool::OK($res);
    }
}