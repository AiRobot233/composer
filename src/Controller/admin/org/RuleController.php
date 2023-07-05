<?php

namespace App\Controller\admin\org;

use App\Middleware\AuthMiddleware;
use App\Middleware\LoginMiddleware;
use App\Request\RuleRequest;
use App\Services\admin\org\RuleService;
use App\Utils\Tool;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

#[Controller]
#[Middlewares([LoginMiddleware::class, AuthMiddleware::class])]
class RuleController
{
    #[Inject]
    private RuleService $ruleService;

    #[RequestMapping(path: "/admin/rule", methods: "get")]
    public function list(): ResponseInterface
    {
        $res = $this->ruleService->list();
        return Tool::OK($res);
    }

    #[RequestMapping(path: "/admin/rule", methods: "post")]
    public function add(RuleRequest $request): ResponseInterface
    {
        $data = $request->post();
        $this->ruleService->add($data);
        return Tool::OK();
    }

    #[RequestMapping(path: "/admin/rule/{id}", methods: "put")]
    public function edit(RuleRequest $request, int $id): ResponseInterface
    {
        $data = $request->getParsedBody();
        $this->ruleService->edit($id, $data);
        return Tool::OK();
    }

    #[RequestMapping(path: "/admin/rule/{id}", methods: "delete")]
    public function del(int $id): ResponseInterface
    {
        $this->ruleService->del($id);
        return Tool::OK();
    }
}