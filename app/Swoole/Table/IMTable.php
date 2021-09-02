<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Swoole\Table;

use App\Helpers\AppHelper;
use Swoole\Table;

class IMTable
{
    // 连接标识
    const COLUMN_FD = 'fd';
    // 账号ID
    const CONLUMN_ACCOUNT_ID = 'account_id';
    // 授权ID
    const COLUMN_AUTHORIZATION_ID = 'authorization_id';
    // 房间ID
    const COLUMN_ROOM_ID = 'room_id';
    // 数据内容
    const COLUMN_DATA = 'data';

    protected $fd = 0;
    protected $account_id = 0;
    protected $authorization_id = 0;
    protected $room_id = 0;
    protected $data = '';

    /**
     * 数据表列字段
     * @var array[]
     */
    protected $columns = [
        [
            'name' => self::COLUMN_FD,
            'type' => Table::TYPE_INT,
            'size' => 10,
        ],
        [
            'name' => self::CONLUMN_ACCOUNT_ID,
            'type' => Table::TYPE_INT,
            'size' => 19,
        ],
        [
            'name' => self::COLUMN_AUTHORIZATION_ID,
            'type' => Table::TYPE_INT,
            'size' => 19,
        ],
        [
            'name' => self::COLUMN_ROOM_ID,
            'type' => Table::TYPE_INT,
            'size' => 19,
        ],
        [
            'name' => self::COLUMN_DATA,
            'type' => Table::TYPE_STRING,
            'size' => 64,
        ],
    ];

    /**
     * 创建 IM Table 表
     * @param int   $table_size
     * @param float $conflict_proportion
     */
    public function create(int $table_size = 1024, float $conflict_proportion = 0.2)
    {
        $table = new Table($table_size, $conflict_proportion);

        foreach ($this->columns as $column) {
            $table->column($column['name'], $column['type'], $column['size']);
        }

        if (! $table->create()) {
            throw new \Exception('Failed to create im table');
        }

        $this->set($table);
        return $table;
    }

    /**
     * @param Table $table
     */
    protected function set(Table $table)
    {
        AppHelper::getServer()->IMTable = $table;
    }

    /**
     * @return Table
     */
    public function get()
    {
        return AppHelper::getServer()->IMTable;
    }

    /**
     * 创建 fd -> account 绑定关系
     * @param  $key
     * @return bool
     */
    public function bind($key): bool
    {
        $value = [];
        foreach ($this->columns as $column) {
            $name = $column['name'];
            $value[$name] = $this->$name;
        }

        return $this->get()->set(strval($key), $value);
    }

    /**
     * 删除 fd -> account 绑定关系
     * @param $key
     * @return bool
     */
    public function unbind($key): bool
    {
        return $this->get()->del(strval($key));
    }

    /**
     * 获取 fd 信息
     * @param $fd
     * @return mixed
     */
    public function getInfo($fd)
    {
        return $this->get()->get(strval($fd));
    }

    /**
     * @param int $fd
     * @return $this
     */
    public function withFd(int $fd): self
    {
        $this->fd = $fd;
        return $this;
    }

    /**
     * @param int $accountId
     * @return $this
     */
    public function withAccountId(int $accountId): self
    {
        $this->account_id = $accountId;
        return $this;
    }

    /**
     * @param int $authorizationId
     * @return $this
     */
    public function withAuthorizationId(int $authorizationId): self
    {
        $this->authorization_id = $authorizationId;
        return $this;
    }

    /**
     * @param int $roomId
     * @return $this
     */
    public function withRoomId(int $roomId): self
    {
        $this->room_id = $roomId;
        return $this;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function withData(string $data): self
    {
        $this->data = $data;
        return $this;
    }
}