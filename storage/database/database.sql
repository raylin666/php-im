CREATE TABLE `im_account` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `authorization_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '授权ID',
  `uid` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '应用账号ID',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '账号名称',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '账号头像',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0:关闭 1:开启 2:删除',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_authorization` (`uid`,`authorization_id`) USING BTREE,
  KEY `un_authorization_state` (`authorization_id`,`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户账号管理表';

CREATE TABLE `im_account_friend` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `ident` varchar(23) NOT NULL COMMENT '单聊唯一标识',
  `account_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '用户账号ID',
  `to_account_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '好友账号ID',
  `to_account_remark` varchar(20) NOT NULL DEFAULT '' COMMENT '好友账号名称备注',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0:关闭 1:开启 2:删除',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_to` (`account_id`,`to_account_id`) USING BTREE,
  KEY `idx_ident` (`ident`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户账号好友关系表';

CREATE TABLE `im_account_friend_apply` (
 `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
 `account_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '用户账号ID',
 `to_account_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '申请添加好友账号ID',
 `to_account_remark` varchar(20) NOT NULL DEFAULT '' COMMENT '添加原因备注',
 `state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0:待确认 1:已通过 2:已拒绝',
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
 `operated_at` datetime DEFAULT NULL COMMENT '操作时间 (通过或拒绝)',
 PRIMARY KEY (`id`),
 KEY `un_account_to` (`account_id`,`to_account_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户账号好友申请表';

CREATE TABLE `im_group` (
    `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
    `account_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '创建者用户账号ID',
    `authorization_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '授权ID',
    `ident` varchar(25) NOT NULL COMMENT '群聊唯一标识',
    `name` varchar(20) NOT NULL DEFAULT '' COMMENT '群昵称',
    `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '群头像',
    `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '群类型 0:普通 1:讨论组',
    `state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0:关闭 1:开启 2:删除',
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_ident` (`ident`) USING BTREE,
    KEY `un_account_authorization_ident` (`account_id`, `authorization_id`, `ident`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='群聊信息表';

CREATE TABLE `im_group_account_apply` (
    `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
    `group_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '群ID',
    `account_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '用户账号ID',
    `operated_account_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '操作者用户账号ID',
    `apply_remark` varchar(30) NOT NULL DEFAULT '' COMMENT '申请入群备注',
    `state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0:待审核 1:已通过 2:已拒绝',
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `operated_at` datetime DEFAULT NULL COMMENT '操作时间 (通过或拒绝)',
    PRIMARY KEY (`id`),
    KEY `un_account_group` (`account_id`,`group_id`) USING BTREE,
    KEY `idx_group` (`group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='群聊成员申请入群表';

CREATE TABLE `im_group_account` (
 `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
 `group_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '群ID',
 `account_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '用户账号ID',
 `to_account_remark` varchar(20) NOT NULL DEFAULT '' COMMENT '群成员名称备注',
 `group_remark` varchar(20) NOT NULL DEFAULT '' COMMENT '群名称备注',
 `identity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '群成员身份 0:普通 1:管理员 2:群主',
 `state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0:关闭 1:开启 2:删除',
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
 `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
 PRIMARY KEY (`id`),
 UNIQUE KEY `uk_account_group` (`account_id`,`group_id`) USING BTREE,
 KEY `idx_group` (`group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='群聊成员表';

CREATE TABLE `im_authorization` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `app` varchar(36) NOT NULL COMMENT '使用应用名称',
  `key` varchar(36) NOT NULL COMMENT '颁布标识 Key',
  `secret` varchar(80) NOT NULL COMMENT '颁布标识 Secret',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `expired_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '过期时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_key_secret` (`key`,`secret`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='授权认证颁布标识表';

CREATE TABLE `im_account_authorization` (
    `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
    `account_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '账号ID',
    `authorization_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '授权ID',
    `token` varchar(500) NOT NULL DEFAULT '' COMMENT 'TOKEN 认证',
    `is_online` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否在线',
    `ttl` int unsigned NOT NULL DEFAULT '0' COMMENT '有效时长',
    `expired_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '过期时间',
    `onlined_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '在线时间',
    `offlined_at` datetime DEFAULT NULL COMMENT '离线时间',
    `refresh_at` datetime DEFAULT NULL COMMENT '刷新时间',
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_account_authorization` (`account_id`, `authorization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户账号授权管理表';

CREATE TABLE `im_c2c_message` (
   `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
   `ident` varchar(23) NOT NULL COMMENT '单聊唯一标识',
   `from_account_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '发送者用户账号ID',
   `to_account_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '接收者用户账号ID',
   `message_type` varchar(20) NOT NULL DEFAULT '' COMMENT '消息类型',
   `message_content` text COMMENT '消息内容',
   `state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0:隐藏 1:显示 2:删除',
   `is_system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统消息 0:否 1:是',
   `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
   `send_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发送时间',
   `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
   PRIMARY KEY (`id`),
   KEY `uk_account_to` (`from_account_id`,`to_account_id`) USING BTREE,
   KEY `idx_ident` (`ident`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='C2C 单聊消息记录表';

CREATE TABLE `im_group_message` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '群ID',
  `from_account_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '发送者用户账号ID',
  `message_type` varchar(20) NOT NULL DEFAULT '' COMMENT '消息类型',
  `message_content` text COMMENT '消息内容',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0:隐藏 1:显示 2:撤回',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统消息 0:否 1:是',
  `identity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '群成员身份 0:普通 1:管理员 2:群主',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `send_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发送时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `uk_group_account` (`group_id`,`from_account_id`) USING BTREE,
  KEY `idx_account` (`from_account_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='GROUP 群聊消息记录表';

