/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50643
Source Host           : localhost:3306
Source Database       : vue_admin_thinkphp6

Target Server Type    : MYSQL
Target Server Version : 50643
File Encoding         : 65001

Date: 2021-01-10 00:40:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for vat_admin
-- ----------------------------
DROP TABLE IF EXISTS `vat_admin`;
CREATE TABLE `vat_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '账号名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1启用 0禁用',
  `role_ids` text NOT NULL COMMENT '角色id数组，逗号隔开',
  `token` varchar(32) NOT NULL DEFAULT '' COMMENT 'token',
  `expire_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of vat_admin
-- ----------------------------
INSERT INTO `vat_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '1', '1', '82af88abd08291305ff0f96ed185a781', '1610296278');
INSERT INTO `vat_admin` VALUES ('2', 'test', '098f6bcd4621d373cade4e832627b4f6', '1', '2', '', '0');
