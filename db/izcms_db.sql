CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `cat_name` varchar(40) NOT NULL,
  `position` int(3) NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `user_id` (`user_id`)
)

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(11) unsigned NOT NULL,
  `author` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `comment` text NOT NULL,
  `comment_date` datetime NOT NULL,
  PRIMARY KEY (`comment_id`)
)

CREATE TABLE IF NOT EXISTS `pages` (
  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `cat_id` int(11) unsigned NOT NULL,
  `page_name` varchar(40) NOT NULL,
  `content` text NOT NULL,
  `position` int(3) NOT NULL,
  `post_on` datetime NOT NULL,
  PRIMARY KEY (`page_id`),
  KEY `user_id` (`user_id`)
)

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `email` varchar(60) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `website` varchar(60) DEFAULT NULL,
  `yahoo` varchar(60) DEFAULT NULL,
  `bio` text,
  `avatar` varchar(60) DEFAULT NULL,
  `user_level` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `active` char(32) DEFAULT NULL,
  `registration_date` datetime NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  KEY `login` (`email`,`pass`)
)
)