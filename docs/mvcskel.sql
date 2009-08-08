CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(40) NOT NULL,
  `password` varchar(32) NOT NULL,
  `roles` varchar(255) NOT NULL,
  `email` varchar(40) NOT NULL,
  `fname` varchar(40) NOT NULL,
  `autoLoginKey` varchar(32) NOT NULL,
  `lastLoginDT` datetime NOT NULL,
  `lastIP` varchar(15) NOT NULL,
  `registrationDT` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `autoLoginKey` (`autoLoginKey`)
) engine=InnoDB;

INSERT INTO `User` (`id`, `username`, `password`, `roles`, `email`, `fname`, `lastLoginDT`, `registrationDT`) VALUES
(1, 'admin', md5('admin'), 'Administrator', 'admin@noemail.org', 'Fred', now(), now()),
(2, 'user', md5('user'), 'User', 'user@noemail.org', 'Joe', now(), now());
