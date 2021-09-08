<?php

declare (strict_types=1);
namespace App\Model\Group;

use App\Model\Model;

/**
 * @property int $id 
 * @property int $group_id 
 * @property int $account_id 
 * @property int $operated_account_id 
 * @property string $apply_remark 
 * @property int $state 
 * @property \Carbon\Carbon $created_at 
 * @property string $operated_at 
 */
class GroupAccountApply extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_account_apply';
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
    protected $casts = ['id' => 'integer', 'group_id' => 'integer', 'account_id' => 'integer', 'operated_account_id' => 'integer', 'state' => 'integer', 'created_at' => 'datetime'];
}