/*
Navicat MySQL Data Transfer

Source Server         : 本地DS
Source Server Version : 50727
Source Host           : localhost:3306
Source Database       : thinkpower

Target Server Type    : MYSQL
Target Server Version : 50727
File Encoding         : 65001

Date: 2019-08-04 23:31:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `t_power`
-- ----------------------------
DROP TABLE IF EXISTS `t_power`;
CREATE TABLE `t_power` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `module` varchar(50) DEFAULT NULL COMMENT '模块',
  `controller` varchar(50) DEFAULT NULL COMMENT '控制器',
  `action` varchar(50) DEFAULT NULL COMMENT '方法',
  `type` tinyint(1) DEFAULT NULL COMMENT '1菜单2二级菜单3功能',
  `par_id` int(11) DEFAULT '0' COMMENT '父级，0表示顶级',
  `enabled` tinyint(1) DEFAULT NULL COMMENT '是否启用0停用1启用',
  `creator` int(11) DEFAULT NULL COMMENT '创建者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间戳',
  `updater` int(11) DEFAULT NULL COMMENT '更新者',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间戳',
  `state` varchar(20) DEFAULT '1' COMMENT '删除标志，1表示正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of t_power
-- ----------------------------

-- ----------------------------
-- Table structure for `t_role`
-- ----------------------------
DROP TABLE IF EXISTS `t_role`;
CREATE TABLE `t_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `enabled` tinyint(1) DEFAULT '1' COMMENT '是否启用0停用1启用',
  `creator` int(11) DEFAULT NULL COMMENT '创建者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间戳',
  `updater` int(11) DEFAULT NULL COMMENT '更新者',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间戳',
  `state` varchar(20) DEFAULT '1' COMMENT '删除标志，1表示正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of t_role
-- ----------------------------
INSERT INTO `t_role` VALUES ('1', '超级管理员', '拥有至高无上的权利', '1', '1', '2019-08-04 01:00:42', '1', '2019-08-04 01:00:45', '1');

-- ----------------------------
-- Table structure for `t_role_power`
-- ----------------------------
DROP TABLE IF EXISTS `t_role_power`;
CREATE TABLE `t_role_power` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` int(11) DEFAULT NULL COMMENT '角色表id',
  `power_id` int(11) DEFAULT NULL COMMENT '权限表id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of t_role_power
-- ----------------------------

-- ----------------------------
-- Table structure for `t_user`
-- ----------------------------
DROP TABLE IF EXISTS `t_user`;
CREATE TABLE `t_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `account` varchar(16) DEFAULT NULL COMMENT '账号',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `salt` varchar(6) DEFAULT NULL COMMENT '盐',
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `gender` tinyint(1) DEFAULT '0' COMMENT '0未知1男2女',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `phone` varchar(11) DEFAULT NULL COMMENT '手机',
  `profile` varchar(255) DEFAULT NULL COMMENT '头像',
  `openid` varchar(32) DEFAULT NULL COMMENT '微信用户openid',
  `locked` tinyint(1) DEFAULT '0' COMMENT '是否锁定，0正常1锁定',
  `last_login_ip` varchar(20) DEFAULT NULL COMMENT '上次登陆IP',
  `last_login_time` datetime DEFAULT NULL COMMENT '上次登陆时间',
  `role_id` int(11) DEFAULT NULL COMMENT '角色表id',
  `creator` int(11) DEFAULT NULL COMMENT '创建者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间戳',
  `updater` int(11) DEFAULT NULL COMMENT '更新者',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间戳',
  `state` varchar(20) DEFAULT '1' COMMENT '删除标志，1表示正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of t_user
-- ----------------------------
INSERT INTO `t_user` VALUES ('1', 'admin', '123456', '123456', 'admin', 'admin', '0', 'admin@admin.com', '15766666666', null, null, '0', null, '2019-08-04 01:02:39', '1', '1', '2019-08-04 00:53:54', '1', '2019-08-04 00:54:00', '1');
INSERT INTO `t_user` VALUES ('2', 'ronaldoc', '3ca40ecfee1cef49b10541276dd8a6aa', 'YpMrJD', '阿达', '阿达', '0', '', '', '', null, '0', null, null, null, null, null, null, null, '1');
INSERT INTO `t_user` VALUES ('3', 'ronaldocc', 'ddba6fc16d0b6fd8a5cdde51508b0fd0', '98vlHS', '阿达', '阿达', '0', '', '', '', null, '0', null, null, null, '1', '2019-08-04 18:44:28', '1', '2019-08-04 18:44:28', '1');
