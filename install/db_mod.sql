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
