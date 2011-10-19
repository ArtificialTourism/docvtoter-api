ALTER TABLE `event` ADD `private` TINYINT( 1 ) NULL DEFAULT '0' AFTER `auto_close` ,
ADD `password` VARCHAR( 128 ) NULL DEFAULT NULL AFTER `private`; 

ALTER TABLE `user` ADD `password` VARCHAR( 128 ) NULL AFTER `username`; 

ALTER TABLE `tags` ADD `type` VARCHAR( 64 ) NOT NULL DEFAULT 'tag' AFTER `name`; 

ALTER TABLE `card` ADD `status` ENUM( 'active', 'deleted' ) NOT NULL DEFAULT 'active' AFTER `type`; 
