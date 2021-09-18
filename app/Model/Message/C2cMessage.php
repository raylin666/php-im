<?php

declare (strict_types=1);
namespace App\Model\Message;

use App\Model\Friend\AccountFriend;
use App\Model\Model;
use Carbon\Carbon;

/**
 * @property int $id 
 * @property string $ident 
 * @property int $from_account_id 
 * @property int $to_account_id 
 * @property string $message_type 
 * @property string $message_content 
 * @property int $state 
 * @property int $is_system 
 * @property \Carbon\Carbon $created_at 
 * @property string $send_at 
 * @property string $deleted_at 
 */
class C2cMessage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'c2c_message';
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
    protected $casts = ['id' => 'integer', 'from_account_id' => 'integer', 'to_account_id' => 'integer', 'state' => 'integer', 'is_system' => 'integer', 'created_at' => 'datetime'];

    /**
     * 添加消息
     * @param             $from_account_id
     * @param             $to_account_id
     * @param             $message_type
     * @param             $message_content
     * @param Carbon|null $send_at
     * @param bool        $is_system
     * @return int
     */
    protected function addMessage(
        $from_account_id,
        $to_account_id,
        $message_type,
        $message_content,
        ?Carbon $send_at = null,
        bool $is_system = false
    ): int
    {
        return $this->insertGetId([
            'ident' => AccountFriend::getIdent($from_account_id, $to_account_id),
            'from_account_id' => $from_account_id,
            'to_account_id' => $to_account_id,
            'message_type' => $message_type,
            'message_content' => strval($message_content),
            'is_system' => intval($is_system),
            'created_at' => Carbon::now(),
            'send_at' => $send_at instanceof Carbon ? $send_at : Carbon::now(),
        ]);
    }
}