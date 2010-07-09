/*
MySQL Data Transfer
Source Host: localhost
Source Database: user_atuh
Target Host: localhost
Target Database: user_atuh
Date: 09/06/2010 19:55:46
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `name` varchar(255) NOT NULL DEFAULT '',
  `pass` varchar(255) DEFAULT NULL,
  `no-enc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `users` VALUES ('arieh', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '1234');
INSERT INTO `users` VALUES ('yosi', '8cb2237d0679ca88db6464eac60da96345513964', '12345');
INSERT INTO `users` VALUES ('bar', '7c4a8d09ca3762af61e59520943dc26494f8941b', '123456');
INSERT INTO `users` VALUES ('bob', 'd5f12e53a182c062b6bf30c1445153faff12269a', '4321');


DROP TABLE IF EXISTS `temp-keys`;
CREATE TABLE `temp-keys` (
  `ip` varchar(255) NOT NULL default '',
  `key` varchar(255) default NULL,
  PRIMARY KEY  (`ip`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `temp-keys` VALUES ('127.0.0.1', '1234');
INSERT INTO `temp-keys` VALUES ('127.0.0.2', '12345');
INSERT INTO `temp-keys` VALUES ('127.0.0.3', '123456');
INSERT INTO `temp-keys` VALUES ('127.0.0.4', '1234a');