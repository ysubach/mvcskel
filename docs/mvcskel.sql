-- phpMyAdmin SQL Dump
-- version 2.8.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Mar 06, 2007 at 10:40 AM
-- Server version: 3.23.53
-- PHP Version: 4.3.11
-- 
-- Database: `mvcskel`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `User`
-- 

CREATE TABLE `User` (
  `id` bigint(20) NOT NULL auto_increment,
  `login` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `rights` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `fname` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`)
) TYPE=MyISAM AUTO_INCREMENT=11 ;

-- 
-- Dumping data for table `User`
-- 

INSERT INTO `User` (`id`, `login`, `password`, `rights`, `email`, `fname`) VALUES (1, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'User', 'user@noemail.org', 'First User'),
(2, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 'admin@noemail.org', 'Administrator'),
(3, 'tst', '9301c2a72c0f099d0313099f1cd54799', 'User,Administrator', 'tst@test.ru', 'Test'),
(6, 'u3', 'd41d8cd98f00b204e9800998ecf8427e', 'User', 'u3@test.com', ''),
(7, 'iutinvg', 'bed128365216c019988915ed3add75fb', 'User', 'iutinvg@yahoo.com', 'Slava Iutin1'),
(10, 'nu', '0288bde0c2d593f2b5766f61b826a650', 'User,Administrator', 'nu@nu.com', 'nu');
