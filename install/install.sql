-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 27, 2011 at 02:21 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `doc`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_methods`
--

CREATE TABLE IF NOT EXISTS `api_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `method` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `oauth` enum('0','1','2','3') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0 = No, 1= both, 2 = 2-legged, 3 = 3-legged',
  `cookie` enum('0','1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0=none, 1=unauthed, 2=authed',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `api_methods`
--

INSERT INTO `api_methods` (`id`, `method`, `oauth`, `cookie`) VALUES
(1, 'session/login', '2', '2'),
(2, 'session/logout', '0', '1'),
(21, 'deck/put', '2', '1'),
(20, 'deck/post', '2', '1'),
(19, 'deck/get', '2', '1'),
(18, 'card/get', '2', '1'),
(16, 'oauth/access_token', '0', '1'),
(17, 'oauth/request_token', '0', '1');

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

CREATE TABLE IF NOT EXISTS `card` (
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `safe_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `question` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `factoid` varchar(1023) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_front` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_back` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `origin_event_id` int(11) DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Card',
  `params` text COLLATE utf8_unicode_ci,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=900 ;

-- --------------------------------------------------------

--
-- Table structure for table `cardstodeck`
--

CREATE TABLE IF NOT EXISTS `cardstodeck` (
  `card_id` int(11) NOT NULL,
  `deck_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `collection` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`name`, `collection`, `id`, `ctime`, `mtime`, `owner`, `group`, `perms`) VALUES
('social', 'steep', 1, 0, 0, 0, 0, 664),
('technology', 'steep', 2, 0, 0, 0, 0, 664),
('environment', 'steep', 3, 0, 0, 0, 0, 664),
('economic', 'steep', 4, 0, 0, 0, 0, 664),
('political', 'steep', 5, 0, 0, 0, 0, 664);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `card_id` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `comment`
--


-- --------------------------------------------------------

--
-- Table structure for table `deck`
--

CREATE TABLE IF NOT EXISTS `deck` (
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `summary` text COLLATE utf8_unicode_ci,
  `start` int(11) NOT NULL,
  `end` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `allow_anon` tinyint(1) NOT NULL,
  `auto_publish` tinyint(1) NOT NULL,
  `auto_close` tinyint(1) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=50 ;

-- --------------------------------------------------------

--
-- Table structure for table `eventcards`
--

CREATE TABLE IF NOT EXISTS `eventcards` (
  `event_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`),
  UNIQUE KEY `eventcard` (`event_id`,`card_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1960 ;

-- --------------------------------------------------------

--
-- Table structure for table `eventeventtype`
--

CREATE TABLE IF NOT EXISTS `eventeventtype` (
  `type_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `eventeventtype`
--


-- --------------------------------------------------------

--
-- Table structure for table `eventtype`
--

CREATE TABLE IF NOT EXISTS `eventtype` (
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `eventtype`
--


-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `address_1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address_2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `county` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `long` float DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `location`
--


-- --------------------------------------------------------

--
-- Table structure for table `oauth_acl`
--

CREATE TABLE IF NOT EXISTS `oauth_acl` (
  `client_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `resource` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '440',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `oauth_acl`
--


-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `vendor` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `secret` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `hosted` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','throttled','blacklisted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '2',
  `perms` int(11) NOT NULL DEFAULT '660',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`name`, `version`, `vendor`, `key`, `secret`, `hosted`, `status`, `id`, `ctime`, `mtime`, `owner`, `group`, `perms`) VALUES
('API Browser', '1.0', 'Mashine Project', 'eb9e55feac986debc4c5db1e9162f5', 'c2429b8871', 0, 'active', 1, 1308230700, 1308230700, 0, 2, 660);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_tokens`
--

CREATE TABLE IF NOT EXISTS `oauth_tokens` (
  `key` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '89b21b738b9e87a668f0c9f7f972bb',
  `secret` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '439696cb9a',
  `consumer_key` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('request','access') COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','used','revoked') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `callback` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '440',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `oauth_tokens`
--


-- --------------------------------------------------------

--
-- Table structure for table `organisation`
--

CREATE TABLE IF NOT EXISTS `organisation` (
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sector` enum('unspecified','commercial','education','government','non profit') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unspecified',
  `location_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `organisation`
--


-- --------------------------------------------------------

--
-- Table structure for table `relation`
--

CREATE TABLE IF NOT EXISTS `relation` (
  `parent_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `type` enum('issue_action','issue_issue','action_action') COLLATE utf8_unicode_ci NOT NULL,
  `weight` float NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `relation`
--


-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `role`
--


-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tags`
--


-- --------------------------------------------------------

--
-- Table structure for table `tagscard`
--

CREATE TABLE IF NOT EXISTS `tagscard` (
  `tag_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tagscard`
--


-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE IF NOT EXISTS `topic` (
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `topic`
--


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `organisation_id` int(11) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=148 ;

-- --------------------------------------------------------

--
-- Table structure for table `vote`
--

CREATE TABLE IF NOT EXISTS `vote` (
  `eventcards_id` int(11) NOT NULL,
  `ip_address` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  `perms` int(11) NOT NULL DEFAULT '664',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14272 ;
