<?php

declare (strict_types=1);
namespace App\Model\Message;

use App\Model\Group\GroupAccount;
use App\Model\Model;
use Carbon\Carbon;

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

    /**
     * 添加消息
     * @param             $group_id
     * @param             $message_type
     * @param             $message_content
     * @param             $from_account_id
     * @param int         $identity
     * @param bool        $is_system
     * @param Carbon|null $send_at
     * @return int
     */
    protected function addMessage(
        $group_id,
        $message_type,
        $message_content,
        $from_account_id,
        ?Carbon $send_at,
        bool $is_system = false
    ): int
    {
        return $this->insertGetId([
            'group_id' => $group_id,
            'from_account_id' => $from_account_id,
            'message_type' => $message_type,
            'message_content' => $message_content,
            'is_system' => intval($is_system),
            'identity' => intval(GroupAccount::getGroupAccountIdentity($from_account_id, $group_id)),
            'created_at' => Carbon::now(),
            'send_at' => $send_at instanceof Carbon ? $send_at : Carbon::now(),
        ]);
    }
}