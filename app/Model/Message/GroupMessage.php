<?php

declare (strict_types=1);
namespace App\Model\Message;

use App\Model\Model;

/**
 * @property int $id 
 * @property int $group_id 
 * @property int $from_account_id 
 * @property string $message_type 
 * @property string $message_content 
 * @property int $state 
 * @property int $is_system 
 * @property int $identity 
 * @property \Carbon\Carbon $created_at 
 * @property string $send_at 
 * @property string $deleted_at 
 */
class GroupMessage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_message';
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
    protected $casts = ['id' => 'integer', 'group_id' => 'integer', 'from_account_id' => 'integer', 'state' => 'integer', 'is_system' => 'integer', 'identity' => 'integer', 'created_at' => 'datetime'];
}