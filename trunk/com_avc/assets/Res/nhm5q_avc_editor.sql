-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 29, 2013 at 08:28 AM
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
-- Table structure for table `nhm5q_avc_editor`
--

CREATE TABLE IF NOT EXISTS `nhm5q_avc_editor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon_path` varchar(255) NOT NULL,
  `query` text NOT NULL,
  `fields_config` text NOT NULL,
  `filters_config` text NOT NULL,
  `admin_only` tinyint(4) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '1',
  `group_alias` varchar(255) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `nhm5q_avc_editor`
--

INSERT INTO `nhm5q_avc_editor` (`id`, `name`, `description`, `icon_path`, `query`, `fields_config`, `filters_config`, `admin_only`, `ordering`, `group_alias`, `published`) VALUES
(1, 'com_avc_editor_config', '', 'images/icons_64/System-icon.png', '{\r\n	"select":"*",\r\n	"from":"#__avc_editor"\r\n}', '{\r\n"id":{},\r\n"name":{},\r\n"description":{\r\n		"type":"plainText"\r\n	},\r\n"icon_path":{"type":"img","params":""},\r\n"query":{"type":"configSQL"},\r\n"fields_config":{"type":"configFields"},\r\n"filters_config":{"type":"configSQL"},\r\n"admin_only":{"type":"boolean","params":""},\r\n"ordering":{},\r\n"group_alias":{},\r\n"published":{"type":"boolean","params":""}\r\n}', '{\r\n	"admin_only":\r\n	{\r\n		"label":"Select Admin Only",\r\n		"custom":{\r\n			"o1":{"value":"1","label":"True"},\r\n			"o2":{"value":"0","label":"False"}\r\n		}\r\n	},\r\n	"publised":\r\n	{\r\n		"label":"Select Status",\r\n		"custom":{\r\n			"o1":{"value":"1","label":"Published"},\r\n			"o2":{"value":"0","label":"Unpublished"}\r\n		}\r\n	}\r\n}', 1, 1, 'admin', 1),
(2, 'com_avc_generator_config', '', 'images/icons_64/System-icon.png', '{\r\n	"select":"*",\r\n	"from":"#__avc_generator"\r\n}', '{\r\n	"id":{},\r\n	"name":{},\r\n	"query":{\r\n		"type":"configSQL",\r\n		"params":{}\r\n	},\r\n	"primary_key":{},\r\n	"template_path":{\r\n		"type":"fileList",\r\n		"params":{\r\n			"folder":"administrator/components/com_avc/assets/tmpl/"\r\n		}\r\n	},\r\n	"content_type":{},\r\n	"custom_values":{},\r\n	"generate":{\r\n		"type":"boolean","params":""\r\n	}\r\n}', '', 1, 1, 'admin', 1),
(3, 'Organizaciona_Struktura', 'Tabela spajanja matičnih i pripadajućih organizacija.', 'images/icons_64/UXTreeView.png', '{\r\n	"select":"*",\r\n	"from":"#__skfs_organizaciona_struktura",\r\n	"order_by":"parent ASC"\r\n}', '{\r\n	"id":{},\r\n	"parent":{\r\n		"type":"rel",\r\n		"params":{\r\n			"queryId":"14"\r\n		}\r\n	},\r\n	"child":{\r\n		"type":"rel",\r\n		"params":{\r\n			"queryId":"14"\r\n		}\r\n	}\r\n}', '', 0, 2, 'skfs', 1),
(4, 'Organizacije', 'Tabela svih organizacija i njihovi podaci.', 'images/icons_64/institution.png', '{\r\n"select":"*",\r\n"from":"#__skfs_organizacije",\r\n"order_by":"id ASC"\r\n}', '{\r\n    "id":{},\r\n    "title":{},\r\n    "article":{\r\n        "type":"plainText"\r\n    },\r\n    "country":{\r\n	"type":"rel",\r\n	"params":{\r\n		"queryId":"13"\r\n	}\r\n    },\r\n    "town":{\r\n	"type":"rel",\r\n	"params":{\r\n		"queryId":"12"\r\n	}\r\n    },\r\n    "address":{},\r\n    "e_mail":\r\n    {\r\n        "type":"json",\r\n        "params":\r\n        {"0":"home","1":"work","2":"other"}\r\n    },\r\n    "web":{},\r\n    "phone":\r\n    {\r\n        "type":"json",\r\n        "params":\r\n        {"0":"office","1":"mobile","2":"fax","3":"other"}\r\n    },\r\n    "established":\r\n    {\r\n        "type":"date"\r\n    },\r\n    "active":\r\n    {\r\n        "type":"boolean"\r\n    }\r\n}', '{\r\n	"active":\r\n	{\r\n		"label":"Select Active",\r\n		"custom":{\r\n			"o1":{"value":"1","label":"Published"},\r\n			"o2":{"value":"0","label":"Unpublished"}\r\n		}\r\n	}\r\n}', 0, 1, 'skfs', 1),
(5, 'Ljudski_Resursi', 'Tabela svih osoba i njihovi lični podaci.', 'images/icons_64/members_icon.png', '{\r\n"select":"*",\r\n"from":"#__skfs_ljudski_resursi",\r\n"order_by":"last_name ASC"\r\n}', '{\r\n\r\n	"id":{},\r\n	"last_name":{},\r\n	"first_name":{},\r\n	"phone":\r\n	{	\r\n		"type":"json",\r\n		"params":\r\n		{"0":"home","1":"work","2":"mobile","3":"fax","4":"other"}\r\n	},\r\n	"e_mail":\r\n	{\r\n		"type":"json",\r\n		"params":\r\n		{"0":"home","1":"work","2":"other"}\r\n	},\r\n	"country":\r\n	{\r\n		"type":"rel",\r\n		"params":{\r\n			"queryId":"13"\r\n		}\r\n    	},\r\n	"town":{\r\n		"type":"rel",\r\n		"params":{\r\n			"queryId":"12"\r\n		}\r\n    	},\r\n	"address":{}\r\n}', '', 0, 1, 'skfs', 1),
(8, 'Istorija_Saziva', 'Tabela za spajanje organizacija, tipova saziva i tranjanja.', 'images/icons_64/icon_getinvolved.png', '{\r\n"select":"*",\r\n"from":"#__skfs_istorija_saziva"\r\n}', '{\r\n	"id":{},\r\n	"organisation":{\r\n		"type":"rel",\r\n		"params":{\r\n			"queryId":"14"\r\n		}\r\n    	},\r\n	"committee":{\r\n		"type":"rel",\r\n		"params":{\r\n			"queryId":"15"\r\n		}\r\n    	},\r\n	"date_start":{\r\n		"type":"date",\r\n		"params":{}\r\n	},\r\n	"date_end":{\r\n		"type":"date",\r\n		"params":{}\r\n	}\r\n}', '', 0, 1, 'skfs', 1),
(9, 'Istorija_Funkcija', 'Tabela spajanja saziva iz istorije, osoba, funkcija na kojem su i trajanje mandata.', 'images/icons_64/old-chair.png', '{\r\n	"select":"*",\r\n	"from":"#__skfs_istorija_funkcija"\r\n}', '{\r\n	"id":{},\r\n	"committee_historical":{\r\n		"type":"rel",\r\n		"params":{\r\n			"queryId":"18"\r\n		}\r\n	},\r\n	"function":{\r\n		"type":"rel",\r\n		"params":{\r\n			"queryId":"17"\r\n		}\r\n	},\r\n	"person":{\r\n		"type":"rel",\r\n		"params":{\r\n			"queryId":"16"\r\n		}\r\n	},\r\n	"date_start":{\r\n		"type":"date",\r\n		"params":{}\r\n	},\r\n	"date_end":{\r\n		"type":"date",\r\n		"params":{}\r\n	}\r\n}', '', 0, 1, 'skfs', 1),
(10, 'Funkcije', 'Tabela sa mogućim funkcijama.', 'images/icons_64/modern-chair.png', '{\r\n"select":"*",\r\n"from":"#__skfs_funkcije"\r\n}', '{\r\n"id":{},\r\n"title":{}\r\n}', '', 0, 1, 'skfs', 1),
(11, 'Organi_i_Tela', 'Tabela sa mogućim organima i telima.', 'images/icons_64/icon_getinvolved.png', '{\r\n"select":"*",\r\n"from":"#__skfs_organi_i_tela"\r\n}', '{\r\n"id":{},\r\n"title":{}\r\n}', '', 0, 1, 'skfs', 1),
(12, 'REL Naselja', '', 'images/icons_64/System-icon.png', '{\r\n	"select":"id,town,zip",\r\n	"from":"#__skfs_naselja"\r\n}', '{\r\n	"id":{},\r\n	"town":{},\r\n	"zip":{}\r\n}', '', 1, 0, 'skfs', 0),
(13, 'REL Drzave', '', 'images/icons_64/System-icon.png', '{\r\n	"key":"country_code",\r\n	"select":"*",\r\n	"from":"#__skfs_drzave"\r\n}', '{\r\n	"id":{},\r\n	"country":{},\r\n	"country_code":{}\r\n}', '', 1, 0, 'skfs', 0),
(14, 'REL Organizacije', '', 'images/icons_64/System-icon.png', '{\r\n	"key":"orgid",\r\n	"select":"org.id as orgid,org.title as title,drz.country as drzcountry,nas.town AS nastown",\r\n	"from":"#__skfs_organizacije as org",\r\n	"left_join":\r\n	{\r\n		"j1":"#__skfs_drzave AS drz ON drz.country_code = org.country",\r\n		"j2":"#__skfs_naselja AS nas ON nas.id = org.town"\r\n	}\r\n}', '{\r\n	"orgid":{},\r\n	"title":{},\r\n	"drzcountry":{},\r\n	"nastown":{}\r\n}', '', 1, 0, 'skfs', 0),
(15, 'REL Organi i tela', '', 'images/icons_64/System-icon.png', '{\r\n	"select":"id,title",\r\n	"from":"#__skfs_organi_i_tela"\r\n}', '{\r\n	"id":{},\r\n	"title":{}\r\n}', '', 1, 0, 'skfs', 0),
(16, 'REL Ljudski Resursi', '', 'images/icons_64/System-icon.png', '{\r\n	"key":"ljrid",\r\n	"select":"ljr.id AS ljrid,ljr.last_name AS last_name,ljr.first_name AS first_name,nas.town AS nastown,drz.country AS drzcountry",\r\n	"from":"#__skfs_ljudski_resursi AS ljr",\r\n	"left_join":\r\n	{\r\n		"j1":"#__skfs_drzave AS drz ON drz.country_code = ljr.country",\r\n		"j2":"#__skfs_naselja AS nas ON nas.id = ljr.town"\r\n	}\r\n}', '{\r\n	"ljrid":{},\r\n	"last_name":{},\r\n	"first_name":{},\r\n	"nastown":{},\r\n	"drzcountry":{}\r\n}', '', 1, 0, 'skfs', 0),
(17, 'REL Funkcije', '', 'images/icons_64/System-icon.png', '{\r\n"select":"id,title",\r\n"from":"#__skfs_funkcije"\r\n}', '{\r\n"id":{},\r\n"title":{}\r\n}', '', 1, 1, 'skfs', 0),
(18, 'REL Istorija Saziva', '', 'images/icons_64/System-icon.png', '{\r\n"key":"issazid",\r\n"select":"issaz.id AS issazid,org.title AS orgtitle,nas.town AS nastown,zem.country AS zemcountry,tela.title AS telatitle,issaz.date_start AS issazdate_start,issaz.date_end AS issazdate_end",\r\n"from":"#__skfs_istorija_saziva AS issaz",\r\n"left_join":\r\n	{\r\n		"j1":"#__skfs_organizacije AS org ON org.id = issaz.organisation",\r\n		"j2":"#__skfs_naselja AS nas ON nas.id = org.town",\r\n		"j3":"#__skfs_zemlje AS zem ON zem.country_code = org.country",\r\n		"j4":"#__skfs_organi_i_tela AS tela ON tela.id = issaz.committee"\r\n	}\r\n}', '{\r\n	"issazid":{},\r\n	"orgtitle":{},\r\n	"nastown":{},\r\n	"zemcountry":{},\r\n	"telatitle":{},\r\n	"issazdate_start":{},\r\n	"issazdate_end":{}\r\n}', '', 1, 1, 'skfs', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
