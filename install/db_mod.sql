ALTER TABLE `card` CHANGE `name` `name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `safe_name` `safe_name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL 

INSERT INTO `api_methods` (`id`, `method`, `oauth`, `cookie`) VALUES (NULL, 'eventcards/put', '2', '0');
