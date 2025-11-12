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

 Date: 02/10/2025 07:56:06
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for master_instansi
-- ----------------------------
DROP TABLE IF EXISTS `master_instansi`;
CREATE TABLE `master_instansi`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instansi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `klasifikasi_debitur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of master_instansi
-- ----------------------------
INSERT INTO `master_instansi` VALUES (1, 'RB KUPEN ASABRI REHAB FAS', 'KUPEN BWS ASABRI');
INSERT INTO `master_instansi` VALUES (2, 'RB KUPEN ASABRI SIKEREN FAS', 'KUPEN BWS ASABRI');
INSERT INTO `master_instansi` VALUES (3, 'RB KUPEN ASABRI FAS', 'KUPEN BWS ASABRI');
INSERT INTO `master_instansi` VALUES (4, 'RB KUPEN TASPEN REHAB FAS', 'KUPEN BWS TASPEN');
INSERT INTO `master_instansi` VALUES (5, 'RB KUPEN TASPEN SIKEREN FAS', 'KUPEN BWS TASPEN');
INSERT INTO `master_instansi` VALUES (6, 'RB KUPEN TASPEN FAS', 'KUPEN BWS TASPEN');
INSERT INTO `master_instansi` VALUES (7, 'RB KUPEN DAPEN', 'KUPEN DAPEN');
INSERT INTO `master_instansi` VALUES (8, 'RB KUPEN HYBRID NON PKS TASPEN SIKEREN', 'KUPEN HYBRID PNS');
INSERT INTO `master_instansi` VALUES (9, 'RB KUPEN HYBRID TASPEN NON PKS FAS 2', 'KUPEN HYBRID PNS');
INSERT INTO `master_instansi` VALUES (10, 'RB KUPEN HYBRID NON PKS TASPEN', 'KUPEN HYBRID PNS');
INSERT INTO `master_instansi` VALUES (11, 'RB KUPEN HYBRID GP TASPEN TC KHUSUS', 'KUPEN HYBRID PNS');
INSERT INTO `master_instansi` VALUES (12, 'RB KUPEN HYBRID TASPEN NON PKS TC KHUSUS', 'KUPEN HYBRID PNS');
INSERT INTO `master_instansi` VALUES (13, 'RB KUPEN HYBRID GP TASPEN', 'KUPEN HYBRID PNS');
INSERT INTO `master_instansi` VALUES (14, 'RB KUPEN HYBRID PKS ASN', 'KUPEN HYBRID PNS');
INSERT INTO `master_instansi` VALUES (15, 'RB KUPEN HYBRID NON PKS ASN', 'KUPEN HYBRID PNS');
INSERT INTO `master_instansi` VALUES (16, 'RB THT ASN', 'KUPEN HYBRID PNS');
INSERT INTO `master_instansi` VALUES (17, 'RB KUPEN HYBRID NON PKS ASABRI SIKEREN', 'KUPEN HYBRID POLRI');
INSERT INTO `master_instansi` VALUES (18, 'RB KUPEN HYBRID ASABRI NON PKS FAS 2', 'KUPEN HYBRID POLRI');
INSERT INTO `master_instansi` VALUES (19, 'RB KUPEN HYBRID NON PKS ASABRI', 'KUPEN HYBRID POLRI');
INSERT INTO `master_instansi` VALUES (20, 'RB KUPEN HYBRID GP ASABRI TC KHUSUS', 'KUPEN HYBRID POLRI');
INSERT INTO `master_instansi` VALUES (21, 'RB KUPEN HYBRID ASABRI NON PKS TC KHUSUS', 'KUPEN HYBRID POLRI');
INSERT INTO `master_instansi` VALUES (22, 'RB KUPEN HYBRID GP ASABRI', 'KUPEN HYBRID POLRI');
INSERT INTO `master_instansi` VALUES (23, 'RB KUPEN HYBRID PKS POLRI', 'KUPEN HYBRID POLRI');
INSERT INTO `master_instansi` VALUES (24, 'RB KUPEN HYBRID NON PKS POLRI', 'KUPEN HYBRID POLRI');
INSERT INTO `master_instansi` VALUES (25, 'RB THT POLRI', 'KUPEN HYBRID POLRI');
INSERT INTO `master_instansi` VALUES (26, 'RB KUPEN HYBRID NON PKS ASABRI SIKEREN', 'KUPEN HYBRID TNI MILITER');
INSERT INTO `master_instansi` VALUES (27, 'RB KUPEN HYBRID ASABRI NON PKS FAS 2', 'KUPEN HYBRID TNI MILITER');
INSERT INTO `master_instansi` VALUES (28, 'RB KUPEN HYBRID NON PKS ASABRI', 'KUPEN HYBRID TNI MILITER');
INSERT INTO `master_instansi` VALUES (29, 'RB KUPEN HYBRID GP ASABRI TC KHUSUS', 'KUPEN HYBRID TNI MILITER');
INSERT INTO `master_instansi` VALUES (30, 'RB KUPEN HYBRID ASABRI NON PKS TC KHUSUS', 'KUPEN HYBRID TNI MILITER');
INSERT INTO `master_instansi` VALUES (31, 'RB KUPEN HYBRID GP ASABRI', 'KUPEN HYBRID TNI MILITER');
INSERT INTO `master_instansi` VALUES (32, 'RB KUPEN HYBRID PKS TNI', 'KUPEN HYBRID TNI MILITER');
INSERT INTO `master_instansi` VALUES (33, 'RB KUPEN HYBRID NON PKS TNI', 'KUPEN HYBRID TNI MILITER');
INSERT INTO `master_instansi` VALUES (34, 'RB THT TNI', 'KUPEN HYBRID TNI MILITER');

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
