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

INSERT INTO `#__ccs_admin_fields` (`id`, `ordering`, `db_alias`, `fld_alias`, `fld_type`, `fld_params`) VALUES
(4, 21, 'ccs_databases', 'parent_db_alias', 'dbFieldList', 'ccs_databases,db_alias'),
(6, 19, 'ccs_databases', 'db_alias', 'dbFieldList', ''),
(7, 14, 'ccs_admin_fields', 'db_alias', 'dbFieldList', ''),
(9, 18, 'ccs_databases', 'access', 'droplist', '1,Public\r\n2,Registered \r\n6,Manager\r\n7,Administrator\r\n8,Super User\r\n999,None'),
(10, 20, 'ccs_databases', 'db_image', 'img', ''),
(12, 12, 'ccs_admin_fields', 'fld_alias', '', ''),
(13, 16, 'ccs_admin_fields', 'fld_params', 'plainText', ''),
(14, 13, 'ccs_admin_fields', 'ordering', '', ''),
(25, 15, 'ccs_admin_fields', 'fld_type', 'droplist', 'dbFieldList,dbFieldList\r\nfileList,fileList\r\nimg,img\r\ndroplist,droplist\r\ncheckbox,checkbox\r\nradio,radio\r\nbutton,button\r\nmodal,modal\r\nplainText,plainText\r\nrichText,richText\r\nlimitVarChar,limitVarChar\r\ndate,date\r\nboolean,boolean'),
(26, 17, 'ccs_databases', 'ordering', '', ''),
(31, 22, 'ccs_databases', 'quick_icon', 'boolean', '');

INSERT INTO `#__ccs_databases` (`id`, `ordering`, `db_alias`, `access`, `db_image`, `parent_db_alias`, `quick_icon`) VALUES
(23, 3, 'ccs_admin_fields', 8, 'administrator/components/com_ccs/assets/images/icon-48-ccs.png', '', 1),
(24, 2, 'ccs_databases', 8, 'administrator/components/com_ccs/assets/images/icon-48-ccs.png', '', 1);