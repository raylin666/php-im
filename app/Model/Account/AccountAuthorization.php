<?php

declare (strict_types=1);
namespace App\Model\Account;

use App\Model\Model;
use Carbon\Carbon;

/**
 * @property int $id 
 * @property int $account_id 
 * @property int $login_platform 
 * @property string $token 
 * @property int $ttl 
 * @property string $expired_at 
 * @property string $refresh_at 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property int $deleted_at 
 */
class AccountAuthorization extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account_authorization';
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
    protected $casts = ['id' => 'integer', 'account_id' => 'integer', 'authorization_id' => 'integer', 'ttl' => 'integer', 'created_at' => 'datetime', 'expired_at' => 'datetime', 'onlined_at' => 'datetime', 'updated_at' => 'datetime'];
    /**
     * @var string[]
     */
    protected $hidden = ['deleted_at'];

    /**
     * 是否正常账号授权值
     * @param      $account_id
     * @param      $authorization_id
     * @param null $token
     * @return bool
     */
    protected function isNormalAccountAuthorization($account_id, $authorization_id, $token = null): bool
    {
        $builder = $this->where(['account_id' => $account_id, 'authorization_id' => $authorization_id]);

        if ($token) {
            $builder->where('token', $token);
        }

        return $builder->where('expired_at', '>', Carbon::now())
            ->whereNull('deleted_at')
            ->exists();
    }
}