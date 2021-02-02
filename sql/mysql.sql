CREATE TABLE `tad_faq_cate` (
  `fcsn` smallint(5) unsigned NOT NULL auto_increment,
  `of_fcsn` smallint(5) unsigned NOT NULL default 0,
  `title` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `sort` smallint(5) unsigned NOT NULL default 0,
  `cate_pic` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`fcsn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE `tad_faq_content` (
  `fqsn` smallint(5) unsigned NOT NULL auto_increment,
  `fcsn` smallint(5) unsigned NOT NULL default 0,
  `title` varchar(255) NOT NULL default '',
  `sort` smallint(5) unsigned NOT NULL default 0,
  `uid` mediumint(8) unsigned NOT NULL default 0,
  `post_date` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `enable` enum('1','0') NOT NULL default '1',
  `counter` smallint(5) NOT NULL default 0,
  PRIMARY KEY  (`fqsn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
