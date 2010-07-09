/*
MySQL Data Transfer
Source Host: localhost
Source Database: hdrc
Target Host: localhost
Target Database: hdrc
Date: 01/01/2009 10:02:47
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for tempkeys
-- ----------------------------
DROP TABLE IF EXISTS `temp-keys`;
CREATE TABLE `temp-keys` (
  `ip` varchar(255) NOT NULL default '',
  `key` varchar(255) default NULL,
  PRIMARY KEY  (`ip`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
