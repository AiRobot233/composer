<?php

declare(strict_types=1);

namespace App\Model;

use App\Utils\Tool;
use Hyperf\Database\Model\Events\Creating;
use Hyperf\Database\Model\Events\Deleting;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $pid
 * @property string $name
 * @property string $type
 * @property string $router
 * @property int $sort
 * @property string $method
 * @property string $tag
 */
class Rule extends Model
{
    public bool $timestamps = false;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'rule';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'pid' => 'integer', 'sort' => 'integer'];

    public function creating(Creating $event)
    {
        if ($this->type == 'page') {
            $bol = $this->query()->where('router', $this->router)->exists();
            if ($bol) Tool::E('页面规则已存在！');
        } else {
            $bol = $this->query()->where(['router' => $this->router, 'method' => $this->method])->exists();
            if ($bol) Tool::E('接口规则已存在！');
        }
    }

    public function deleting(Deleting $event)
    {
        $bol = $this->query()->where('pid', $this->id)->exists();
        if ($bol) Tool::E('有子级不允许删除');
    }

    public function setFromData(array $data)
    {
        $this->pid = $data['pid'];
        $this->name = $data['name'];
        $this->type = $data['type'];
        $this->router = $data['router'];
        $this->sort = $data['sort'] ?? 0;
        $this->method = $data['method'] ?? null;
        $this->tag = $data['tag'] ?? null;
    }
}
