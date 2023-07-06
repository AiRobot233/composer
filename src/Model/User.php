<?php

declare(strict_types=1);

namespace Airobot\Hyperf\Model;

use Airobot\Hyperf\Utils\Tool;
use Airobot\Hyperf\Utils\Util;
use Hyperf\Database\Model\Events\Deleting;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $password
 * @property string $salt
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property int $status
 * @property int $role_id
 */
class User extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'user';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'status' => 'integer', 'role_id' => 'integer'];

    public function role(): \Hyperf\Database\Model\Relations\HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id')->select(['id', 'name']);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = md5($value . $this->salt);
    }

    public function saving(Saving $event)
    {
        $bol = $this->query()->where('id', '<>', $this->id)
            ->where('name', $this->name)->exists();
        if ($bol) Tool::E('名称不能重复');
    }

    public function deleting(Deleting $event)
    {
        $bol = $this->query()->where('pid', $this->id)->exists();
        if ($bol) Tool::E('有子级不允许删除');
    }

    public function setFromData(array $data, Util $util)
    {
        $this->name = $data['name'];
        $this->phone = $data['phone'];
        $this->salt = $this->salt ? $this->salt : $util->getSalt($this->phone);
        if (empty($this->password)) {
            $this->password = $data['password'] ?? '!Q@W#E$R5t';
        } else {
            if (!empty($data['password'])) {
                $this->password = $data['password'];
            }
        }
        $this->status = $data['status'] ?? 1;
        $this->role_id = $data['role_id'];
    }
}
