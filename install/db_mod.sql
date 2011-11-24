ALTER TABLE `event` ADD `private` TINYINT( 1 ) NULL DEFAULT '0' AFTER `auto_close` ,
ADD `password` VARCHAR( 128 ) NULL DEFAULT NULL AFTER `private`; 

ALTER TABLE `user` ADD `password` VARCHAR( 128 ) NULL AFTER `username`; 

ALTER TABLE `tags` ADD `type` VARCHAR( 64 ) NOT NULL DEFAULT 'tag' AFTER `name`; 

ALTER TABLE `card` ADD `status` ENUM( 'active', 'deleted' ) NOT NULL DEFAULT 'active' AFTER `type`;

--
-- Table structure for table `eventusers`
--

CREATE TABLE IF NOT EXISTS `eventusers` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

INSERT INTO `api_methods` (`id`, `method`, `oauth`, `cookie`) VALUES (NULL, 'eventuser/post', '2', '1'), (NULL, 'eventuser/delete', '2', '1');

RENAME TABLE role TO groups;

ALTER TABLE `user` CHANGE COLUMN role_id group_id int(11) DEFAULT 0;
ALTER TABLE  `user` ADD  `params` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `group_id`;

UPDATE  `api_methods` SET  `method` = 'group/get' WHERE  `method` = 'role/get';

ALTER TABLE `organisation` ADD `owner` INT NOT NULL DEFAULT '0' AFTER `mtime`;