<?php

declare(strict_types=1);

namespace App\Model;

use App\Utils\Tool;
use Hyperf\Database\Model\Events\Deleting;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $pid
 * @property string $name
 * @property string $value
 * @property int $sort
 */
class Dictionary extends Model
{
    public bool $timestamps = false;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'dictionary';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'pid' => 'integer', 'sort' => 'integer'];

    public function saving(Saving $event)
    {
        $bol = $this->query()->where('id', '<>', $this->id)->where('name', $this->name)->exists();
        if ($bol) Tool::E('名称不能重复');
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
        $this->value = $data['value'];
        $this->sort = $data['sort'] ?? 0;
    }
}
