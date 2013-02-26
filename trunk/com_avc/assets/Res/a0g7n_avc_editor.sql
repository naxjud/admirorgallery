-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 26, 2013 at 04:09 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.6-1ubuntu1.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Joomla_17`
--

-- --------------------------------------------------------

--
-- Table structure for table `a0g7n_avc_editor`
--

CREATE TABLE IF NOT EXISTS `a0g7n_avc_editor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon_path` varchar(255) NOT NULL,
  `query` text NOT NULL,
  `fields_config` text NOT NULL,
  `admin_only` tinyint(4) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '1',
  `group_alias` varchar(255) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `a0g7n_avc_editor`
--

INSERT INTO `a0g7n_avc_editor` (`id`, `name`, `icon_path`, `query`, `fields_config`, `admin_only`, `ordering`, `group_alias`, `published`) VALUES
(1, 'VIEW FIELDS TEST', 'images/icons_64/display.im6.png', '{\r\n"select":"id, name, icon_path, query, fields_config, admin_only, ordering, group_alias, published",\r\n"from":"#__avc_editor"\r\n}', '{\r\n"query":{"type":"plainText","params":"","title":""},\r\n"icon_path":{"type":"img","params":""},\r\n"fields_config":{"type":"plainText","params":""},\r\n"admin_only":{"type":"boolean","params":""},\r\n"published":{"type":"boolean","params":""}\r\n}', 0, 1, 'admin', 1),
(2, 'Test Banners', 'images/icons_64/aptoncd.png', '{"select":"id, name, alias, clickurl","from":"#__banners"}', '', 0, 2, 'admin', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
