CREATE TABLE IF NOT EXISTS `twitter_user` ( 
	`user_id` int(10) unsigned NOT NULL, 
	`twitter_id` varchar(255) NOT NULL, 
	PRIMARY KEY  (`user_id`),
	UNIQUE KEY `twitter_id` (`twitter_id`)
); 
