SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `tokens`;
DROP TABLE IF EXISTS `user`;

CREATE TABLE IF NOT EXISTS `tokens` (
	`id` int(10) NOT NULL auto_increment,
	`user_id` int(10) NOT NULL,
	`token` varchar(32) NOT NULL,
	`timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
	PRIMARY KEY  (`id`),
	UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `user` (
	`id` int(10) NOT NULL auto_increment,
	`email` varchar(255) NOT NULL,
	`username` varchar(255) NOT NULL,
	`passwordhash` varchar(255) NOT NULL,
	`active` tinyint(1) NOT NULL,
	`activationkey` varchar(16) NOT NULL,
	`timestamp_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
	PRIMARY KEY  (`id`),
	UNIQUE KEY `email` (`email`),
	UNIQUE KEY `activationkey` (`activationkey`),
	UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

ALTER TABLE `tokens`
	ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
