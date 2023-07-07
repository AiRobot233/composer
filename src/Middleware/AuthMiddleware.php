<?php

declare(strict_types=1);

namespace Airobot\Hyperf\Middleware;

use Airobot\Hyperf\Model\Role;
use Airobot\Hyperf\Model\Rule;
use Airobot\Hyperf\Utils\Tool;
use Hyperf\Context\Context;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    protected ContainerInterface $container;

    protected RequestInterface $request;

    protected HttpResponse $response;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = Context::get("userData");
        $role = Role::query()->where('id', $user['role_id'])->first(['is_system', 'rule']);
        if (empty($role)) Tool::E('角色已被删除!');
        if ($role->is_system == 1) {
            $path = $this->request->getAttribute(Dispatched::class)->handler->route;
            $method = $this->request->getMethod();
            $rule = explode(',', $role->rule);
            $r = Rule::query()->whereIn('id', $rule)->where(['router' => $path, 'method' => $method])->exists();
            if (!$r) Tool::E('无权限访问!');
        }
        return $handler->handle($request);
    }
}
