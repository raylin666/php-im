CREATE TABLE `im_account` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `authorization_id` bigint(19) unsigned NOT NULL DEFAULT '0' COMMENT '授权ID',
  `uid` bigint unsigned NOT NULL DEFAULT '0' COMMENT '应用账号ID',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '账号名称',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '账号头像',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0:关闭 1:开启 2:删除',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_authorization` (`uid`,`authorization_id`) USING BTREE,
  KEY `un_authorization_state` (`authorization_id`,`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户账号管理表';

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

