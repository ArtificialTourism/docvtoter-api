ALTER TABLE cardstodeck RENAME TO deckcards;
ALTER TABLE tagscard RENAME TO cardtags;

INSERT INTO tags (`name` ,`type` ,`id` ,`ctime` ,`mtime` ,`owner` ,`group` ,`perms`) VALUES
('social', 'steep', '1', NULL , NULL , '0', '0', '664'),
('technology', 'steep', '2', NULL , NULL , '0', '0', '664'),
('environment', 'steep', '3', NULL , NULL , '0', '0', '664'),
('economic', 'steep', '4', NULL , NULL , '0', '0', '664'),
('political', 'steep', '5', NULL , NULL , '0', '0', '664');

DROP TABLE category;

--
-- Table structure for table `collection`
--

CREATE TABLE IF NOT EXISTS `collection` (
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `collection`
--

INSERT INTO `collection` (`name`, `id`, `ctime`, `mtime`, `owner`, `group`, `perms`) VALUES
('steep', 1, NULL, NULL, 0, 0, 664);

--
-- Table structure for table `collectiontags`
--

CREATE TABLE IF NOT EXISTS `collectiontags` (
  `collection_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `collectiontags`
--

INSERT INTO `collectiontags` (`collection_id`, `tag_id`, `id`, `ctime`, `mtime`, `owner`, `group`, `perms`) VALUES
(1, 1, 1, NULL, NULL, 0, 0, 664),
(1, 2, 2, NULL, NULL, 0, 0, 664),
(1, 3, 3, NULL, NULL, 0, 0, 664),
(1, 4, 4, NULL, NULL, 0, 0, 664),
(1, 5, 5, NULL, NULL, 0, 0, 664);

ALTER TABLE `event` ADD `collection_id` INT( 11 ) NOT NULL DEFAULT '1' AFTER `safe_name`;

ALTER TABLE `eventcards` ADD `category_tag_id` INT( 11 ) NULL AFTER `card_id`;
