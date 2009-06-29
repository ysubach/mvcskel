CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `roles` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `fname` varchar(255) NOT NULL default '',
  `autoLoginKey` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `autoLoginKey` (`autoLoginKey`)
);

INSERT INTO `User` (`id`, `username`, `password`, `roles`, `email`, `fname`, `autoLoginKey`) VALUES
(1, 'admin', md5('admin'), 'Administrator', 'admin@noemail.org', 'Fred', ''),
(2, 'user', md5('user'), 'User', 'user@noemail.org', 'Joe', '');
