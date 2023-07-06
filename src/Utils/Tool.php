<?php

namespace Airobot\Hyperf\Utils;

use Psr\Http\Message\ResponseInterface;

class Tool
{

    public static function E(string $msg, int $code = 200): void
    {
        HttpUtil::getInstance()->error($msg, $code);
    }

    public static function OK(mixed $data = null, string $msg = 'success', int $code = 0): ResponseInterface
    {
        return HttpUtil::getInstance()->success($data, $msg, $code);
    }
}