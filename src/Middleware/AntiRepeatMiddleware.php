<?php

declare(strict_types=1);

namespace Airobot\Hyperf\Middleware;

use Airobot\Hyperf\Utils\Tool;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Redis\Redis;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AntiRepeatMiddleware implements MiddlewareInterface
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
        //访问的ip
        $host = $this->request->getUri()->getHost();
        //端口
        $port = $this->request->getUri()->getPort();
        // 方法名称
        $route = $this->request->getAttribute(Dispatched::class)->handler->route;
        $key = 'antiRepeat:' . $host . ':' . $port . ":" . $route;
        $container = ApplicationContext::getContainer();
        $redis = $container->get(Redis::class);
        if ($redis->get($key) == '1') {
            Tool::E('请不要提交重复请求!');
        } else {
            $redis->setex($key, 3, '1');
        }
        return $handler->handle($request);
    }
}
