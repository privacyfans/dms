/*
 Navicat Premium Data Transfer

 Source Server         : 172.28.100.44 - DMS BARU
 Source Server Type    : MySQL
 Source Server Version : 50735 (5.7.35)
 Source Host           : 172.28.100.44:3306
 Source Schema         : dmsx

 Target Server Type    : MySQL
 Target Server Version : 50735 (5.7.35)
 File Encoding         : 65001

 Date: 02/10/2025 07:56:18
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for master_klasifikasi_debitur
-- ----------------------------
DROP TABLE IF EXISTS `master_klasifikasi_debitur`;
CREATE TABLE `master_klasifikasi_debitur`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `klasifikasi_debitur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of master_klasifikasi_debitur
-- ----------------------------
INSERT INTO `master_klasifikasi_debitur` VALUES (1, 'KUPEN BWS ASABRI ');
INSERT INTO `master_klasifikasi_debitur` VALUES (2, 'KUPEN BWS TASPEN ');
INSERT INTO `master_klasifikasi_debitur` VALUES (3, 'KUPEN HYBRID PNS ');
INSERT INTO `master_klasifikasi_debitur` VALUES (4, 'KUPEN HYBRID POLRI ');
INSERT INTO `master_klasifikasi_debitur` VALUES (5, 'KUPEN HYBRID TNI MILITER ');
INSERT INTO `master_klasifikasi_debitur` VALUES (6, 'KUPEN DAPEN');

SET FOREIGN_KEY_CHECKS = 1;
