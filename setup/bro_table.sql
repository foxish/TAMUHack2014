
CREATE TABLE IF NOT EXISTS `bro_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `url` varchar(4000) NOT NULL,
  `type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `stars` double DEFAULT NULL,
  `upvotes` int(11) NOT NULL DEFAULT '0',
  `downvotes` int(11) NOT NULL DEFAULT '0',
  `spam` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

