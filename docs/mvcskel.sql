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
  `id` int NOT NULL auto_increment,
  `username` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `roles` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `fname` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `User`
-- 

INSERT INTO `User` (`id`, `username`, `password`, `roles`, `email`, `fname`) 
VALUES 
(1, 'user', md5('user'), 'User', 'user@noemail.org', 'First User'),
(2, 'admin', md5('admin'), 'Administrator', 'admin@noemail.org', 'Administrator');
