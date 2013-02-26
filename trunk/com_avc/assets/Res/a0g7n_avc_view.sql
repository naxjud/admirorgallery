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
-- Table structure for table `a0g7n_avc_view`
--

CREATE TABLE IF NOT EXISTS `a0g7n_avc_view` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `query` text NOT NULL,
  `tmpl` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `a0g7n_avc_view`
--

INSERT INTO `a0g7n_avc_view` (`id`, `name`, `query`, `tmpl`) VALUES
(1, 'Banners', '{\r\n"select":"a.id AS aid, a.name AS aname, a.alias AS aalias, a.clickurl AS aclickurl, b.name AS client",\r\n"from":"#__banners as a",\r\n"left_join":"#__banner_clients AS b ON a.cid = b.id",\r\n"order_by":"aname ASC",\r\n"limit":"0,3"\r\n}', '{\r\n"name":"default",\r\n"vars":{"key":"aid"},\r\n"open":{\r\n"view":"10",\r\n"view_name":"Details",\r\n"tmpl":"default",\r\n"order_by":"",\r\n"limit":"",\r\n"where":"a.id = $0"\r\n}\r\n}'),
(10, 'Akeba Stats', '{\r\n"select":"a.description AS description, a.backupend AS backupend, a.archivename AS arhcivename",\r\n"from":"#__ak_stats AS a"\r\n}', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
