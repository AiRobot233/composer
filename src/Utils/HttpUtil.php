<?php

namespace Airobot\Hyperf\Utils;

use Exception;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\ApplicationContext;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class HttpUtil
{
    private static ?object $instance = null;

    /**
     * 禁止被实例化
     */
    private function __construct()
    {

    }

    /**
     * 禁止clone
     * @return void
     */
    private function __clone()
    {

    }

    /**
     * 实例化自己并保存到$instance中，已实例化则直接调用
     */
    public static function getInstance(): HttpUtil|null
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $msg
     * @return void
     * @throws Exception
     */
    public function error(string $msg, int $code = 200): void
    {
        throw new \Exception($msg, $code);
    }

    /**
     * @param mixed|null $data
     * @param string $msg
     * @param int $code
     */
    public function success(mixed $data = null, string $msg = 'success', int $code = 0): Psr7ResponseInterface
    {
        $response = ApplicationContext::getContainer()->get(ResponseInterface::class);
        $result = [
            'error' => $code,
            'message' => $msg,
            'data' => $data,
            'timestamp' => time(),
        ];
        return $response->json($result);
    }
}