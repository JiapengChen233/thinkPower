/*
 Navicat Premium Data Transfer

 Source Server         : 本地DB
 Source Server Type    : MySQL
 Source Server Version : 50717
 Source Host           : localhost:3306
 Source Schema         : thinkpower

 Target Server Type    : MySQL
 Target Server Version : 50717
 File Encoding         : 65001

 Date: 05/09/2019 14:25:37
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_power
-- ----------------------------
DROP TABLE IF EXISTS `t_power`;
CREATE TABLE `t_power`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '模块',
  `controller` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '控制器',
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '方法',
  `type` tinyint(1) NULL DEFAULT NULL COMMENT '1菜单2二级菜单3功能',
  `par_id` int(11) NULL DEFAULT 0 COMMENT '父级，0表示顶级',
  `enabled` tinyint(1) NULL DEFAULT NULL COMMENT '是否启用0停用1启用',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
  `creator` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建时间戳',
  `updater` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `update_time` datetime(0) NULL DEFAULT NULL COMMENT '更新时间戳',
  `state` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '删除标志，1表示正常',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_power
-- ----------------------------
INSERT INTO `t_power` VALUES (1, '管理员管理', 'admin', '', '', 1, 0, 1, '#xe726;', 1, '2019-08-27 18:37:51', 1, '2019-09-03 15:37:28', '1');
INSERT INTO `t_power` VALUES (2, '管理员列表', 'admin', 'User', '', 2, 1, 1, '', 1, '2019-08-27 18:58:22', 1, '2019-09-05 13:53:45', '1');
INSERT INTO `t_power` VALUES (3, '角色管理', 'admin', 'Role', '', 2, 1, 1, '', 1, '2019-08-27 19:00:40', 1, '2019-08-27 19:00:40', '1');
INSERT INTO `t_power` VALUES (4, '权限管理', 'admin', 'Power', '', 2, 1, 1, '', 1, '2019-08-27 19:02:09', 1, '2019-08-28 18:45:54', '1');
INSERT INTO `t_power` VALUES (5, '查询', 'admin', 'User', 'index', 3, 2, 1, '', 1, '2019-08-27 19:21:18', 1, '2019-09-05 13:56:53', '1');
INSERT INTO `t_power` VALUES (6, '新增', 'admin', 'User', 'add', 3, 2, 1, '', 1, '2019-08-27 19:56:15', 1, '2019-09-05 13:56:56', '1');
INSERT INTO `t_power` VALUES (7, '编辑', 'admin', 'User', 'edit', 3, 2, 1, '', 1, '2019-08-27 20:02:03', 1, '2019-09-05 13:56:58', '1');
INSERT INTO `t_power` VALUES (8, '删除', 'admin', 'User', 'delete', 3, 2, 1, '', 1, '2019-08-28 18:46:32', 1, '2019-09-05 13:56:59', '1');
INSERT INTO `t_power` VALUES (9, '查询', 'admin', 'Role', 'index', 3, 3, 1, '', 1, '2019-08-28 18:49:05', 1, '2019-08-28 18:49:05', '1');
INSERT INTO `t_power` VALUES (10, '新增', 'admin', 'Role', 'add', 3, 3, 1, '', 1, '2019-08-28 18:49:32', 1, '2019-08-28 18:49:32', '1');
INSERT INTO `t_power` VALUES (11, '编辑', 'admin', 'Role', 'edit', 3, 3, 1, '', 1, '2019-08-28 18:49:44', 1, '2019-08-28 18:49:44', '1');
INSERT INTO `t_power` VALUES (12, '删除', 'admin', 'Role', 'delete', 3, 3, 1, '', 1, '2019-08-28 18:50:25', 1, '2019-08-28 18:50:25', '1');
INSERT INTO `t_power` VALUES (13, '查询', 'admin', 'Power', 'index', 3, 4, 1, '', 1, '2019-08-28 18:54:04', 1, '2019-09-03 15:37:42', '1');
INSERT INTO `t_power` VALUES (14, '新增', 'admin', 'Power', 'add', 3, 4, 1, '', 1, '2019-08-28 18:54:21', 1, '2019-08-28 18:54:21', '1');
INSERT INTO `t_power` VALUES (15, '编辑', 'admin', 'Power', 'edit', 3, 4, 1, '', 1, '2019-08-28 18:54:33', 1, '2019-08-28 18:54:33', '1');
INSERT INTO `t_power` VALUES (16, '删除', 'admin', 'Power', 'delete', 3, 4, 1, '', 1, '2019-08-28 18:54:45', 1, '2019-08-28 19:22:16', '1');
INSERT INTO `t_power` VALUES (17, '启用停用', 'admin', 'User', 'userStop', 3, 2, 1, '', 1, '2019-08-28 18:55:14', 1, '2019-09-05 13:57:01', '1');
INSERT INTO `t_power` VALUES (18, '启用停用', 'admin', 'Role', 'roleStop', 3, 3, 1, '', 1, '2019-08-28 18:55:36', 1, '2019-08-28 18:55:36', '1');
INSERT INTO `t_power` VALUES (19, '启用停用', 'admin', 'Power', 'powerStop', 3, 4, 1, '', 1, '2019-08-28 18:55:51', 1, '2019-09-03 15:37:52', '1');
INSERT INTO `t_power` VALUES (20, '权限', 'admin', 'Role', 'distributePower', 3, 3, 1, '', 1, '2019-09-03 15:36:17', 1, '2019-09-03 15:36:17', '1');
INSERT INTO `t_power` VALUES (21, '授权', 'admin', 'Role', 'authorizeRole', 3, 3, 1, '', 1, '2019-09-03 15:37:09', 1, '2019-09-03 15:37:09', '1');

-- ----------------------------
-- Table structure for t_role
-- ----------------------------
DROP TABLE IF EXISTS `t_role`;
CREATE TABLE `t_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '描述',
  `enabled` tinyint(1) NULL DEFAULT 1 COMMENT '是否启用0停用1启用',
  `creator` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建时间戳',
  `updater` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `update_time` datetime(0) NULL DEFAULT NULL COMMENT '更新时间戳',
  `state` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '删除标志，1表示正常',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_role
-- ----------------------------
INSERT INTO `t_role` VALUES (1, '超级管理员', '拥有至高无上的权利', 1, 1, '2019-08-04 01:00:42', 1, '2019-08-04 01:00:45', '1');
INSERT INTO `t_role` VALUES (2, '测试管理员', '测试管理员', 1, 1, '2019-08-26 18:53:30', 1, '2019-09-03 11:37:22', '1');

-- ----------------------------
-- Table structure for t_role_power
-- ----------------------------
DROP TABLE IF EXISTS `t_role_power`;
CREATE TABLE `t_role_power`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` int(11) NULL DEFAULT NULL COMMENT '角色表id',
  `power_id` int(11) NULL DEFAULT NULL COMMENT '权限表id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 162 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_role_power
-- ----------------------------
INSERT INTO `t_role_power` VALUES (155, 2, 1);
INSERT INTO `t_role_power` VALUES (156, 2, 2);
INSERT INTO `t_role_power` VALUES (157, 2, 5);
INSERT INTO `t_role_power` VALUES (158, 2, 3);
INSERT INTO `t_role_power` VALUES (159, 2, 9);
INSERT INTO `t_role_power` VALUES (160, 2, 4);
INSERT INTO `t_role_power` VALUES (161, 2, 13);

-- ----------------------------
-- Table structure for t_user
-- ----------------------------
DROP TABLE IF EXISTS `t_user`;
CREATE TABLE `t_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `account` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '账号',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '密码',
  `salt` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '盐',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '姓名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '昵称',
  `gender` tinyint(1) NULL DEFAULT 0 COMMENT '0未知1男2女',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '邮箱',
  `phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '手机',
  `profile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '头像',
  `openid` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '微信用户openid',
  `locked` tinyint(1) NULL DEFAULT 0 COMMENT '是否锁定，0正常1锁定',
  `last_login_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '上次登陆IP',
  `last_login_time` datetime(0) NULL DEFAULT NULL COMMENT '上次登陆时间',
  `role_id` int(11) NULL DEFAULT NULL COMMENT '角色表id',
  `creator` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建时间戳',
  `updater` int(11) NULL DEFAULT NULL COMMENT '更新者',
  `update_time` datetime(0) NULL DEFAULT NULL COMMENT '更新时间戳',
  `state` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '1' COMMENT '删除标志，1表示正常',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 68 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_user
-- ----------------------------
INSERT INTO `t_user` VALUES (1, 'admin', '35e353901a3eba016e40f2ab3723ca1b', 'EjPO3E', 'admin', 'admin', 0, 'admin@admin.com', '15766666666', '20190903\\7cf48fe1814bc9e1f21f2c0783f9fa01.png', NULL, 0, '::1', '2019-09-05 13:42:49', 1, 1, '2019-08-04 00:53:54', 1, '2019-09-05 13:42:49', '1');
INSERT INTO `t_user` VALUES (63, 'aa12345', '31f926be358e6b724ea775c8fc38ceeb', 'rfBNKJ', 'ronaldoc', 'ronaldoc', 1, 'aa@qq.com', '15545523301', '20190903\\7cf48fe1814bc9e1f21f2c0783f9fa01.png', NULL, 0, '::1', '2019-09-05 12:11:15', 2, 1, '2019-09-03 11:32:09', 1, '2019-09-05 12:11:16', '1');

SET FOREIGN_KEY_CHECKS = 1;
