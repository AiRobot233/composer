<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Exception\Handler;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    public function __construct(protected StdoutLoggerInterface $logger)
    {
    }

    public function handle(Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $code = 200;
        if ($throwable instanceof ValidationException) {
            $msg = $throwable->validator->errors()->first();
        } else {
            $msg = $throwable->getMessage();
            $code = $throwable->getCode();
        }
        // 格式化输出
        $result = [
            'error' => 1,
            'message' => $msg,
            'data' => null,
            'timestamp' => time(),
        ];
        // 阻止异常冒泡
        $this->stopPropagation();
        return $response->withStatus($code)->withAddedHeader('content-type', 'application/json; charset=utf-8')->withBody(new SwooleStream(json_encode($result)));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
