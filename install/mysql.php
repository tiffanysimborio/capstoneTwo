<?php
return array(

"# Mysql
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;",


"# Mysql
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `description` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;",


"# Mysql
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `categories_id` int(10) unsigned DEFAULT '0',
  `code` varchar(50) DEFAULT NULL,
  `quantity` int(10) DEFAULT '0',
  `buying_price` decimal(19,2) unsigned DEFAULT '0.00',
  `selling_price` decimal(19,2) unsigned DEFAULT '0.00',
  `cost_total` decimal(19,2) unsigned DEFAULT '0.00',
  `income_total` decimal(19,2) unsigned DEFAULT '0.00',
  `location` varchar(200) DEFAULT NULL,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_items_categories` (`categories_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;",


"# Mysql
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned DEFAULT NULL,
  `log` text,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;",


"# Mysql
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `permissions_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=183 DEFAULT CHARSET=utf8;",


"# Mysql
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
    (152, 'view_users', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (153, 'view_roles', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (154, 'add_users', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (155, 'edit_users', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (156, 'delete_users', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (157, 'edit_self', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (158, 'view_categories', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (159, 'add_categories', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (160, 'edit_categories', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (161, 'delete_categories', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (162, 'view_contacts', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (163, 'add_contacts', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (164, 'edit_contacts', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (165, 'delete_contacts', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (166, 'view_groups', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (167, 'view_settings', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (168, 'edit_settings', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (169, 'view_items', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (170, 'view_item_edits', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (171, 'view_checkin', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (172, 'view_checkout', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (173, 'add_checkin', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (174, 'add_checkout', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (175, 'add_items', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (176, 'edit_items', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (177, 'delete_items', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (178, 'upload_item_images', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (179, 'delete_item_images', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (180, 'view_transactions', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (181, 'delete_transactions', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (182, 'edit_transactions', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00');",





"# Mysql
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `level` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `roles_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;",

"# Mysql
INSERT INTO `roles` (`id`, `name`, `description`, `level`, `created_at`, `updated_at`) VALUES
    (1, 'Super Admin', NULL, 10, '2013-03-17 17:13:47', '2013-03-17 17:13:47'),
    (2, 'Admin', NULL, 9, '2013-03-17 17:54:24', '2013-03-17 17:54:24'),
    (4, 'Seller', NULL, 7, '2013-03-17 18:00:42', '2013-03-17 18:00:42'),
    (5, 'Viewer', NULL, 6, '2013-03-17 18:00:42', '2013-03-17 18:00:42'),
    (6, 'Manager', NULL, 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (12, 'Demo', NULL, 0, '2013-03-23 03:16:35', '2013-03-23 03:16:35');",





"# Mysql
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(40) NOT NULL,
  `last_activity` int(10) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;",


"# Mysql
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `field` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;",

"# Mysql
INSERT INTO `settings` (`id`, `field`, `name`, `value`) VALUES
    (1, 'language', 'application.language', 'en');",


"# Mysql
CREATE TABLE IF NOT EXISTS `transaction_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;",


"# Mysql
INSERT INTO `transaction_types` (`id`, `type`, `name`, `description`) VALUES
    (1, 'out', 'Out', 'Outbound transaction'),
    (2, 'in', 'In', 'Inbound transaction');",


"# Mysql
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `password` varchar(60) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `disabled` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_username_index` (`username`),
  KEY `users_password_index` (`password`),
  KEY `users_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;",


"# Mysql
INSERT INTO `users` (`id`, `username`, `name`, `password`, `salt`, `email`, `verified`, `disabled`, `deleted`, `created_at`, `updated_at`) VALUES
    (1, 'admin', 'Admin', '\$2a$08\$zQjkrVgt.uwikAC4B/BRRuiNrMgRfbmtgwCOQZf3jQBLeO4WLNvB.', 'fb6b4f90341af9e9f054b5eeafd368bf', 'example@gmail.com', 1, 0, 0, '2013-03-17 17:13:47', '2013-03-21 19:19:24');",

    "# Mysql
CREATE TABLE IF NOT EXISTS `permission_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_role_permission_id_index` (`permission_id`),
  KEY `permission_role_role_id_index` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=utf8;",


"# Mysql
INSERT INTO `permission_role` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
    (134, 152, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (135, 153, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (136, 158, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (137, 159, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (138, 160, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (139, 161, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (140, 162, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (141, 163, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (142, 164, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (143, 165, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (144, 167, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (145, 169, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (146, 170, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (147, 173, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (148, 174, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (149, 175, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (150, 176, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (151, 177, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (152, 180, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (153, 181, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (154, 182, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (155, 157, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (156, 158, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (157, 162, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (158, 169, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (159, 170, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (160, 180, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (161, 157, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (162, 158, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (163, 162, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (164, 169, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (165, 170, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (166, 174, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (167, 178, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (168, 179, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (169, 180, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (170, 157, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (171, 158, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (172, 159, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (173, 160, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (174, 161, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (175, 162, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (176, 163, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (177, 164, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (178, 165, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (179, 169, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (180, 170, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (181, 173, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (182, 174, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (183, 175, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (184, 176, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (185, 177, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (186, 178, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (187, 179, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (188, 180, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (189, 181, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (190, 182, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (191, 152, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (192, 153, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (193, 154, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (194, 155, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (195, 156, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (196, 157, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (197, 158, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (198, 159, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (199, 160, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (200, 161, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (201, 162, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (202, 163, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (203, 164, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (204, 165, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (205, 167, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (206, 168, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (207, 169, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (208, 170, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (209, 173, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (210, 174, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (211, 175, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (212, 176, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (213, 177, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (214, 178, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (215, 179, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (216, 180, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (217, 181, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (218, 182, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
    (219, 179, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00');",

    "# Mysql
CREATE TABLE IF NOT EXISTS `role_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_user_user_id_index` (`user_id`),
  KEY `role_user_role_id_index` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;",


"# Mysql
INSERT INTO `role_user` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
    (16, 1, 2, '2013-03-24 00:53:16', '2013-03-24 00:53:16');",

"# Mysql
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `items_id` int(10) unsigned NOT NULL,
  `transactions_types_id` int(10) unsigned NOT NULL,
  `date` date DEFAULT NULL,
  `contacts_id` int(11) DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(19,2) unsigned DEFAULT '0.00',
  `sum` decimal(19,2) DEFAULT '0.00',
  `note` text,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_transactions_transactions_types1_idx` (`transactions_types_id`),
  KEY `fk_transactionsitems_idx` (`items_id`),
  CONSTRAINT `fk_transactions_items` FOREIGN KEY (`items_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_transactions_transactions_types1` FOREIGN KEY (`transactions_types_id`) REFERENCES `transaction_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;",

);