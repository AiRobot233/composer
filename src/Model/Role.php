<?php

declare(strict_types=1);

namespace Airobot\Hyperf\Model;

use Airobot\Hyperf\Utils\Tool;
use Hyperf\Database\Model\Events\Deleting;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $pid
 * @property string $name
 * @property string $rule
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property int $is_system
 */
class Role extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'role';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'pid' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'is_system' => 'integer'];

    public function deleting(Deleting $event)
    {
        if($this->is_system == 2) Tool::E('管理员账号不允许删除');
        $bol = $this->query()->where('pid', $this->id)->exists();
        if ($bol) Tool::E('有子级不允许删除');
    }

    public function setFromData(array $data)
    {
        $this->pid = $data['pid'];
        $this->name = $data['name'];
        $this->rule = $data['rule'];
        $this->is_system = $data['is_system'] ?? 1;
    }
}
