--
-- Table structure for table `#__avc_field`
--

CREATE TABLE IF NOT EXISTS `#__avc_field` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `params` varchar(1024) NOT NULL,
  `relationship` varchar(255) NOT NULL,
  `access_level` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `system` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;


INSERT INTO `#__avc_field` (`id`, `title`, `name`, `type`, `params`, `relationship`, `access_level`, `ordering`, `published`, `system`) VALUES
(9, 'Prezime', 'last_name', '', '', '', 0, 1, 1, 0),
(10, 'Ime', 'first_name', '', '', '', 0, 2, 1, 0),
(11, 'Telefon', 'phone', 'json', '{"0":"home","1":"work","2":"mobile","3":"fax","4":"other"}', '', 0, 3, 1, 0),
(12, 'Mejl', 'e_mail', 'json', '{"0":"home","1":"work","2":"mobile","3":"fax","4":"other"}', '', 0, 4, 1, 0),
(13, 'Naziv', 'title', '', '', '', 0, 1, 1, 0),
(14, 'Naselje', 'town', '', '', '', 0, 3, 1, 0),
(15, 'Ulica', 'address', '', '', '', 0, 4, 1, 0),
(16, 'Mejl', 'e_mail', '', '', '', 0, 6, 1, 0),
(17, 'Sajt', 'web', '', '', '', 0, 7, 1, 0),
(18, 'Telefon', 'phone', 'json', '{"0":"home","1":"work","2":"mobile","3":"fax","4":"other"}', '', 0, 5, 1, 0),
(19, 'Datum osnivanja', 'established', 'date', '', '', 0, 8, 1, 0),
(20, 'Vazeca', 'active', 'boolean', '', '', 0, 9, 1, 0),
(21, 'Zemlja', 'country', 'rel', '{"value":"country_code","label":"country"}', '{"table":"#__skfs_zemlje","key":"country_code"}', 0, 2, 1, 0),
(22, 'Regija', 'region', 'rel', '{"value":"id","label":"region"}', '{"table":"#__skfs_regije","key":"id"}', 0, 3, 1, 0);

--
-- Table structure for table `#__avc_view`
--

CREATE TABLE IF NOT EXISTS `#__avc_view` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `icon_path` varchar(1024) NOT NULL,
  `title` varchar(255) NOT NULL,
  `sort_field_name` varchar(255) NOT NULL,
  `sort_order` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `key_field_name` varchar(255) NOT NULL,
  `params` varchar(255) NOT NULL,
  `access_level` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `system` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `#__avc_view` (`id`, `icon_path`, `title`, `sort_field_name`, `sort_order`, `name`, `key_field_name`, `params`, `access_level`, `ordering`, `published`, `system`) VALUES
(3, 'images/icons_64/members_icon.png', 'Ljudski Resursi', '', '', '#__skfs_ljudski_resursi', 'id', '', 0, 0, 1, 0),
(4, 'images/icons_64/institution.png', 'Organizacije', '', '', '#__skfs_organizacije', 'id', '', 0, 0, 1, 0);

--
-- Table structure for table `#__avc_view_fields`
--

CREATE TABLE IF NOT EXISTS `#__avc_view_fields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `view_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

INSERT INTO `#__avc_view_fields` (`id`, `view_id`, `field_id`) VALUES
(9, 3, 9),
(10, 3, 10),
(11, 3, 11),
(12, 3, 12),
(13, 4, 13),
(14, 4, 14),
(15, 4, 15),
(16, 4, 16),
(17, 4, 17),
(18, 4, 18),
(19, 4, 19),
(20, 4, 20),
(21, 4, 21),
(22, 4, 22);
