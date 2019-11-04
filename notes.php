4-11-2019 ganapathi

ALTER TABLE sma_users ADD merchant_code varchar(150) NULL AFTER allow_discount;

CREATE TABLE `tenants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `merchant_code` varchar(191) DEFAULT NULL,
  `database_connection_name` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);



