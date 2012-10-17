--
-- Table structure for table `ccs_ccs_databases`
--

CREATE TABLE IF NOT EXISTS `#__ccs_databases` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `db_alias` varchar(255) NOT NULL,
  `access` int(11) NOT NULL DEFAULT '1',
  `db_image` varchar(255) NOT NULL,
  `parent_db_alias` varchar(255) NOT NULL,
  `quick_icon` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;


--
-- Table structure for table `ccs_ccs_admin_fields`
--

CREATE TABLE IF NOT EXISTS `#__ccs_admin_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `db_alias` varchar(255) NOT NULL,
  `fld_alias` varchar(255) NOT NULL,
  `fld_type` varchar(255) NOT NULL,
  `fld_params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;