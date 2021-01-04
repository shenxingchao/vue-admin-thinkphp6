/*
Navicat MySQL Data Transfer

Source Server         : 本地连接
Source Server Version : 50643
Source Host           : localhost:3306
Source Database       : vue_admin_thinkphp6

Target Server Type    : MYSQL
Target Server Version : 50643
File Encoding         : 65001

Date: 2021-01-04 17:14:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for vat_route_resource
-- ----------------------------
DROP TABLE IF EXISTS `vat_route_resource`;
CREATE TABLE `vat_route_resource` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路由地址',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '路由名称',
  `component` varchar(255) NOT NULL DEFAULT '' COMMENT '映射组件名称',
  `redirect` varchar(255) NOT NULL DEFAULT '' COMMENT '重定向路由',
  `always_show` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '显示根节点',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '菜单隐藏路由',
  `icon` varchar(255) NOT NULL DEFAULT '' COMMENT 'svg图标',
  `affix` tinyint(1) NOT NULL DEFAULT '0' COMMENT '菜单标签栏固定',
  `breadcrumb` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '面包屑显示菜单',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级路由id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='路由资源表';

-- ----------------------------
-- Records of vat_route_resource
-- ----------------------------
INSERT INTO `vat_route_resource` VALUES ('1', '首页', '/', 'Index', 'Layout', '/dashboard', '0', '0', '', '0', '0', '0');
INSERT INTO `vat_route_resource` VALUES ('2', '控制台', 'dashboard', 'Dashboard', 'Dashboard', '', '0', '0', 'dashboard', '1', '1', '1');
INSERT INTO `vat_route_resource` VALUES ('3', '权限管理', '/permission', 'Permission', 'Layout', 'noRedirect', '1', '0', 'permission', '0', '1', '0');
INSERT INTO `vat_route_resource` VALUES ('4', '路由资源管理', 'route_resource', 'RouteResource', 'RouteResource', '/permission/route_resource/route_resource_list', '0', '0', '', '0', '0', '3');
INSERT INTO `vat_route_resource` VALUES ('5', '路由资源', 'route_resource_list', 'RouteResourceList', 'RouteResourceList', '', '0', '0', 'table', '0', '1', '4');
INSERT INTO `vat_route_resource` VALUES ('6', '路由资源添加', 'route_resource_add', 'RouteResourceAdd', 'RouteResourceAdd', '', '0', '1', 'table', '0', '1', '4');
INSERT INTO `vat_route_resource` VALUES ('7', '大三大四', '1', '2', '3', '', '0', '0', '', '0', '1', '3');
