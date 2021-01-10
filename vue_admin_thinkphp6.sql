/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50643
Source Host           : localhost:3306
Source Database       : vue_admin_thinkphp6

Target Server Type    : MYSQL
Target Server Version : 50643
File Encoding         : 65001

Date: 2021-01-11 00:09:12
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
INSERT INTO `vat_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '1', '1,2', '9ac2d9013b152abcfd28d6758d6cfadb', '1610380510');
INSERT INTO `vat_admin` VALUES ('2', 'test', '098f6bcd4621d373cade4e832627b4f6', '1', '2', '', '0');

-- ----------------------------
-- Table structure for vat_role
-- ----------------------------
DROP TABLE IF EXISTS `vat_role`;
CREATE TABLE `vat_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `role_name` varchar(255) NOT NULL DEFAULT '' COMMENT '角色名称',
  `route_resource_ids` text NOT NULL COMMENT '半选中和全选中id',
  `temp_route_resource_ids` text NOT NULL COMMENT '全选中节点id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of vat_role
-- ----------------------------
INSERT INTO `vat_role` VALUES ('1', '超级管理员', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15');
INSERT INTO `vat_role` VALUES ('2', '访客', '1,2', '1,2');

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='路由资源表';

-- ----------------------------
-- Records of vat_route_resource
-- ----------------------------
INSERT INTO `vat_route_resource` VALUES ('1', '首页', '/', 'Index', 'Layout', '/dashboard', '0', '0', '', '0', '0', '0');
INSERT INTO `vat_route_resource` VALUES ('2', '控制台', 'dashboard', 'Dashboard', 'Dashboard', '', '0', '0', 'dashboard', '1', '1', '1');
INSERT INTO `vat_route_resource` VALUES ('3', '权限管理', '/permission', 'Permission', 'Layout', 'noRedirect', '1', '0', 'permission', '0', '1', '0');
INSERT INTO `vat_route_resource` VALUES ('4', '路由资源管理', 'route_resource', 'RouteResource', 'RouteResource', '/permission/route_resource/route_resource_list', '0', '0', '', '0', '0', '3');
INSERT INTO `vat_route_resource` VALUES ('5', '路由资源列表', 'route_resource_list', 'RouteResourceList', 'RouteResourceList', '', '0', '0', 'table', '0', '1', '4');
INSERT INTO `vat_route_resource` VALUES ('6', '路由资源添加', 'route_resource_add', 'RouteResourceAdd', 'RouteResourceAdd', '', '0', '1', '', '0', '1', '4');
INSERT INTO `vat_route_resource` VALUES ('7', '路由资源编辑', 'route_resource_edit', 'RouteResourceEdit', 'RouteResourceEdit', '', '0', '1', '', '0', '1', '4');
INSERT INTO `vat_route_resource` VALUES ('8', '角色管理', 'role', 'Role', 'Role', '/permission/role/role_list', '0', '0', '', '0', '0', '3');
INSERT INTO `vat_route_resource` VALUES ('9', '角色列表', 'role_list', 'RoleList', 'RoleList', '', '0', '0', 'table', '0', '1', '8');
INSERT INTO `vat_route_resource` VALUES ('10', '角色添加', 'role_add', 'RoleAdd', 'RoleAdd', '', '0', '1', '', '0', '1', '8');
INSERT INTO `vat_route_resource` VALUES ('11', '角色编辑', 'role_edit', 'RoleEdit', 'RoleEdit', '', '0', '1', '', '0', '1', '8');
INSERT INTO `vat_route_resource` VALUES ('12', '管理员管理', 'admin', 'Admin', 'Admin', '/permission/admin/admin_list', '0', '0', '', '0', '0', '3');
INSERT INTO `vat_route_resource` VALUES ('13', '管理员列表', 'admin_list', 'AdminList', 'AdminList', '', '0', '0', 'table', '0', '1', '12');
INSERT INTO `vat_route_resource` VALUES ('14', '管理员添加', 'admin_add', 'AdminAdd', 'AdminAdd', '', '0', '1', '', '0', '1', '12');
INSERT INTO `vat_route_resource` VALUES ('15', '管理员编辑', 'admin_edit', 'AdminEdit', 'AdminEdit', '', '0', '1', '', '0', '1', '12');
