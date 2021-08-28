<?php

declare (strict_types=1);
namespace App\Model\Account;

use App\Model\Model;
use Carbon\Carbon;

/**
 * @property int $id 
 * @property string $app 
 * @property string $key 
 * @property string $secret 
 * @property \Carbon\Carbon $created_at 
 * @property string $expired_at 
 */
class Authorization extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'authorization';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime'];

    /**
     * 获取授权ID
     * @param $key
     * @param $secret
     * @return int
     */
    protected function getAuthorizationId($key, $secret): int
    {
        return intval($this->where(['key' => $key, 'secret' => $secret])
            ->where('expired_at', '>', Carbon::now())
            ->value('id'));
    }
}