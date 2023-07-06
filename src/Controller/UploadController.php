<?php

namespace Airobot\Hyperf\Controller;

use Airobot\Hyperf\Middleware\LoginMiddleware;
use Airobot\Hyperf\Utils\Tool;
use Airobot\Hyperf\Utils\Util;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use League\Flysystem\Filesystem;

#[Controller]
#[Middleware(LoginMiddleware::class)]
class UploadController
{
    #[Inject]
    private Util $util;

    #[RequestMapping(path: "/upload/file", methods: "post")]
    public function file(RequestInterface $request, Filesystem $filesystem): bool|string
    {
        $file = $request->file('file');
        $name = $file->getClientFilename();
        $extension = $file->getExtension();
        $stream = fopen($file->getRealPath(), 'r+');
        $path = 'uploads/' . date('Ymd') . '/' . $this->util->getRandFileName($name) . '.' . $extension;
        $filesystem->writeStream($path, $stream);
        fclose($stream);
        return Tool::Ok(['path' => $path, 'name' => $name, 'extension' => $extension]);
    }
}