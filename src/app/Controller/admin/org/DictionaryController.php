<?php

namespace App\Controller\admin\org;

use App\Middleware\AuthMiddleware;
use App\Middleware\LoginMiddleware;
use App\Request\DictionaryRequest;
use App\Services\admin\org\DictionaryService;
use App\Utils\Tool;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

#[Controller]
#[Middlewares([LoginMiddleware::class, AuthMiddleware::class])]
class DictionaryController
{
    #[Inject]
    private DictionaryService $dictionaryService;

    #[RequestMapping(path: "/admin/dictionary", methods: "get")]
    public function list(): ResponseInterface
    {
        $res = $this->dictionaryService->list();
        return Tool::OK($res);
    }

    #[RequestMapping(path: "/admin/dictionary", methods: "post")]
    public function add(DictionaryRequest $request): ResponseInterface
    {
        $data = $request->post();
        $this->dictionaryService->add($data);
        return Tool::OK();
    }

    #[RequestMapping(path: "/admin/dictionary/{id}", methods: "put")]
    public function edit(DictionaryRequest $request, int $id): ResponseInterface
    {
        $data = $request->getParsedBody();
        $this->dictionaryService->edit($id, $data);
        return Tool::OK();
    }

    #[RequestMapping(path: "/admin/dictionary/{id}", methods: "delete")]
    public function del(int $id): ResponseInterface
    {
        $this->dictionaryService->del($id);
        return Tool::OK();
    }
}