<?php

declare(strict_types=1);

namespace Airobot\Hyperf\Middleware;

use Airobot\Hyperf\Model\User;
use Airobot\Hyperf\Utils\JwtUtil;
use Airobot\Hyperf\Utils\Tool;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Context\Context;

class LoginMiddleware implements MiddlewareInterface
{
    protected ContainerInterface $container;

    protected RequestInterface $request;

    protected HttpResponse $response;

    protected JwtUtil $jwtUtil;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request, JwtUtil $jwtUtil)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
        $this->jwtUtil = $jwtUtil;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $res = $this->request->getHeader('Authorization');
        if (empty($res[0])) Tool::E('未登录！', 401);
        $user = $this->jwtUtil->decode($res[0]);
        if (!User::query()->where('id', $user['id'])->exists()) {
            $result = $this->errorArr();
            return $this->response->withStatus(401)->withAddedHeader('content-type', 'application/json; charset=utf-8')->withBody(new SwooleStream(json_encode($result)));
        }
        Context::set("userData", $user);
        return $handler->handle($request);
    }

    private function errorArr(): array
    {
        return [
            'error' => 1,
            'message' => '用户不存在请联系管理员！',
            'data' => null,
            'timestamp' => time(),
        ];
    }
}
