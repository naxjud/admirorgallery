-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 05, 2013 at 01:14 PM
-- Server version: 5.5.31-0ubuntu0.13.04.1
-- PHP Version: 5.4.9-4ubuntu2.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `skif`
--

-- --------------------------------------------------------

--
-- Table structure for table `nhm5q_avc_generator`
--

CREATE TABLE IF NOT EXISTS `nhm5q_avc_generator` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `query` text NOT NULL,
  `primary_key` varchar(255) NOT NULL,
  `template_path` varchar(255) NOT NULL,
  `content_type` varchar(255) NOT NULL,
  `custom_values` varchar(255) NOT NULL,
  `test` tinyint(4) NOT NULL DEFAULT '1',
  `generate` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `nhm5q_avc_generator`
--

INSERT INTO `nhm5q_avc_generator` (`id`, `name`, `query`, `primary_key`, `template_path`, `content_type`, `custom_values`, `test`, `generate`) VALUES
(14, 'Contact (Serbia)', '{\r\n	"select":"istS.id as istid, oit.title as committee_name, fun.title as function_name, ljr.last_name, ljr.first_name, ljr.address, ljr.town as zip, nas.town, drz.country, ljr.phone, ljr.e_mail",\r\n	"from":"#__skfs_istorija_saziva as istS",\r\n	"where":{\r\n		"w1":"istS.organisation = 2",\r\n		"w2":"istS.date_end = 0000-00-00",\r\n		"w3":"istF.date_end = 0000-00-00",\r\n		"w4":"oit.id = 1"\r\n	},\r\n	"left_join":{\r\n		"j1":"#__skfs_istorija_funkcija as istF on istF.committee_historical = istS.id",\r\n		"j2":"#__skfs_organi_i_tela as oit on oit.id = istS.committee",\r\n		"j3":"#__skfs_funkcije as fun on fun.id = istF.function",\r\n		"j4":"#__skfs_ljudski_resursi as ljr on ljr.id = istF.person",\r\n		"j5":"#__skfs_naselja as nas on nas.id = ljr.town",\r\n		"j6":"#__skfs_drzave as drz on drz.country_code = ljr.country"\r\n	}\r\n}', 'istid', 'administrator/components/com_avc/assets/tmpl/skif-art-contact.php', 'article', '', 0, 0),
(15, 'Federation Structure - Kategorije', '{\r\n	"select":"orgstr.child as childid, orgC.title as orgtitle, orgstr.parent as parentid, orgC.article AS orgarticle, orgC.address AS orgaddress, orgC.phone as orgphone, orgC.e_mail AS orge_mail, orgC.web AS orgweb, zem.country AS zemcountry, nas.town AS nastown, nas.zip AS naszip",\r\n	"from":"#__skfs_organizaciona_struktura as orgstr",\r\n	"left_join":\r\n	{\r\n		"j1":"#__skfs_organizacije as orgP on orgP.id = orgstr.parent",\r\n		"j2":"#__skfs_organizacije as orgC on orgC.id = orgstr.child",\r\n		"j4":"#__skfs_drzave AS zem ON zem.country_code = orgC.country",\r\n		"j5":"#__skfs_naselja AS nas ON nas.id = orgC.town"\r\n	}\r\n}', 'childid', 'administrator/components/com_avc/assets/tmpl/skif-cats-organisations.php', 'category', '', 0, 0),
(17, 'Federation Structure - Administrator article', '{\r\n	"select":"org.id AS orgid, org.title AS orgtitle, orgstr.child as catid, zem.country AS zemcountry, nas.town AS nastown, oit.title as committee_name, fun.title as function_name, ljr.last_name, ljr.first_name",\r\n	"from":"#__skfs_organizaciona_struktura AS orgstr",\r\n	"where":{\r\n		"w2":"istS.date_end = 0000-00-00",\r\n		"w3":"istF.date_end = 0000-00-00"\r\n	},\r\n	"left_join":{\r\n		"j1":"#__skfs_organizacije AS org ON org.id = orgstr.child",\r\n		"j2":"#__skfs_drzave AS zem ON zem.country_code = org.country",\r\n		"j3":"#__skfs_naselja AS nas ON nas.id = org.town",\r\n		"jISTSAZ":"#__skfs_istorija_saziva as istS on istS.organisation = org.id",\r\n		"j4":"#__skfs_istorija_funkcija as istF on istF.committee_historical = istS.id",\r\n		"j5":"#__skfs_organi_i_tela as oit on oit.id = istS.committee",\r\n		"j6":"#__skfs_funkcije as fun on fun.id = istF.function",\r\n		"j7":"#__skfs_ljudski_resursi as ljr on ljr.id = istF.person"\r\n	}\r\n}', 'orgid', 'administrator/components/com_avc/assets/tmpl/skif-arts-org-administration.php', 'article', '{"gen_id_for_catid":15}', 0, 0),
(18, '21SKDUN Accommodations', '{\r\n	"select":"ha.id AS haid, h.title as htitle, h.alias as halias, a.title as atitle, a.alias as aalias, ha.quantity, ha.price, ha.supper, ha.supper_price",\r\n	"from":"#__skfs_hotel_accommodation as ha",\r\n	"where":{\r\n		"w1":"ha.publish = 1",\r\n		"w2":"h.publish = 1"\r\n	},\r\n	"left_join":\r\n	{\r\n		"j1":"#__skfs_hotels as h on h.id = ha.hotel",\r\n		"j2":"#__skfs_accommodation_types as a on a.id = ha.accommodation"\r\n	}\r\n}', 'haid', 'administrator/components/com_avc/assets/tmpl/cats-21skdun-accommodations.php', 'category', '', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
