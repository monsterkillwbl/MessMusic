/**
 * Mrdong916@163.com
 * 
 **/
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for api_api
-- ----------------------------
DROP TABLE IF EXISTS `api_api`;
CREATE TABLE `api_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `TransCode` varchar(255) NOT NULL,
  `nums` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for api_one
-- ----------------------------
DROP TABLE IF EXISTS `api_one`;
CREATE TABLE `api_one` (
  `oneId` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `vol` varchar(255) NOT NULL,
  `img_url` varchar(255) NOT NULL,
  `img_author` varchar(255) NOT NULL,
  `img_kind` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `word_id` int(11) NOT NULL,
  `word` varchar(255) NOT NULL,
  `word_from` varchar(255) NOT NULL,
  PRIMARY KEY (`oneId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for api_user
-- ----------------------------
DROP TABLE IF EXISTS `api_user`;
CREATE TABLE `api_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `OpenId` varchar(255) NOT NULL DEFAULT '',
  `secret` varchar(255) NOT NULL DEFAULT '',
  `nums` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
