<?php

namespace Airobot\Hyperf\Utils;

use Hyperf\Paginator\LengthAwarePaginator;

class Util
{

    /**
     * 获取唯一文件名称
     * @param string $name
     * @return string
     */
    public function getRandFileName(string $name): string
    {
        return md5(time() . md5($name) . rand(10000, 99999));
    }

    /**
     * 获取密码盐
     * @param string $str
     * @return string
     */
    public function getSalt(string $str): string
    {
        return substr(md5($str . time()), 0, 5);
    }

    /**
     * 通用分页返回
     * @param LengthAwarePaginator $data
     * @return array
     */
    public function p(LengthAwarePaginator $data): array
    {
        return ['total' => $data->total(), 'item' => $data->items()];
    }

    /**
     * 返回树状数据
     * @param array $data
     * @param int $pid
     * @return array
     */
    public function arrayToTree(array $data, int $pid = 0): array
    {
        $tree = [];
        foreach ($data as $value) {
            if ($value['pid'] == $pid) {
                $arr = self::arrayToTree($data, $value['id']);
                if (!empty($arr)) {
                    $value['children'] = $arr;
                }
                $tree[] = $value;
            }
        }
        return $tree;
    }
}