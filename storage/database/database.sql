CREATE TABLE `im_account` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `authorization_id` bigint(19) unsigned NOT NULL DEFAULT '1' COMMENT '授权ID',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '账号名称',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '账号头像',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0:关闭 1:开启 2:删除',
  `created_at` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `deleted_at` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_authorization_status` (`authorization_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户账号管理表';
