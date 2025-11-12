/*
 Navicat Premium Data Transfer

 Source Server         : 172.28.100.44 - DMS BARU
 Source Server Type    : MySQL
 Source Server Version : 50735 (5.7.35)
 Source Host           : 172.28.100.44:3306
 Source Schema         : dms

 Target Server Type    : MySQL
 Target Server Version : 50735 (5.7.35)
 File Encoding         : 65001

 Date: 07/11/2025 09:17:38
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for branch_tbo
-- ----------------------------
DROP TABLE IF EXISTS `branch_tbo`;
CREATE TABLE `branch_tbo`  (
  `branch_code` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `branch_type` int(255) NULL DEFAULT NULL,
  `jml` int(11) NULL DEFAULT NULL,
  `approve_flag` int(11) NULL DEFAULT NULL,
  `purpose_jml` int(11) NULL DEFAULT NULL,
  `user_approve` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `approve_date` datetime NULL DEFAULT NULL,
  `total_pengajuan` int(11) NULL DEFAULT 0,
  `total_pencairan` int(11) NULL DEFAULT 0,
  `tbo_overdue` int(11) NULL DEFAULT 0,
  `limit_persen_tbo_overdue` int(11) NULL DEFAULT 0,
  `total_limit_overdue` int(11) NULL DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`branch_code`) USING BTREE,
  INDEX `branch_type`(`branch_type`) USING BTREE,
  CONSTRAINT `branch_tbo_ibfk_1` FOREIGN KEY (`branch_type`) REFERENCES `branch_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for branch_type
-- ----------------------------
DROP TABLE IF EXISTS `branch_type`;
CREATE TABLE `branch_type`  (
  `id` int(11) NOT NULL,
  `branch_type_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for branchlist
-- ----------------------------
DROP TABLE IF EXISTS `branchlist`;
CREATE TABLE `branchlist`  (
  `branch_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `branch_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `parent_branch` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `branch_type` int(255) NULL DEFAULT 0,
  `wilayah` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`branch_code`) USING BTREE,
  UNIQUE INDEX `branch_code`(`branch_code`) USING BTREE,
  INDEX `branch_name`(`branch_name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_app_no` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `comment` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `user_name` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `level_spv` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `comment_date` datetime NULL DEFAULT NULL,
  `flag_spv` int(255) NULL DEFAULT NULL,
  `tbo_date` datetime NULL DEFAULT NULL,
  `reason` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag_process` int(2) NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `loan_app_no`(`loan_app_no`) USING BTREE,
  INDEX `lvl_spv`(`level_spv`) USING BTREE,
  INDEX `flag_spv`(`flag_spv`) USING BTREE,
  INDEX `flag_process`(`flag_process`) USING BTREE,
  INDEX `comment_date`(`comment_date`) USING BTREE,
  INDEX `tbo_date`(`tbo_date`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1819414 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for cut_off
-- ----------------------------
DROP TABLE IF EXISTS `cut_off`;
CREATE TABLE `cut_off`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cut_off` time NOT NULL,
  `approve_flag` int(11) NULL DEFAULT 0,
  `purpose_cut_off` time NULL DEFAULT NULL,
  `user_approve` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `approve_date` datetime NULL DEFAULT NULL,
  `branch_code` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 164 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for cut_off_hq
-- ----------------------------
DROP TABLE IF EXISTS `cut_off_hq`;
CREATE TABLE `cut_off_hq`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cut_off` time NULL DEFAULT NULL,
  `approve_flag` int(11) NULL DEFAULT 0,
  `user_input` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_input` datetime NULL DEFAULT NULL,
  `purpose_cut_off` time NULL DEFAULT NULL,
  `user_approve` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `approve_date` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for cut_off_hq_history
-- ----------------------------
DROP TABLE IF EXISTS `cut_off_hq_history`;
CREATE TABLE `cut_off_hq_history`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cut_off` time NULL DEFAULT NULL,
  `approve_flag` int(11) NULL DEFAULT 0,
  `user_input` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_input` datetime NULL DEFAULT NULL,
  `purpose_cut_off` time NULL DEFAULT NULL,
  `user_approve` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `approve_date` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for data_file
-- ----------------------------
DROP TABLE IF EXISTS `data_file`;
CREATE TABLE `data_file`  (
  `modul` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_cabang` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `loan_app_no` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_cif` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `no_ktp` varchar(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_debitur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ttl` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `alamat_rumah` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_tlp_rumah` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `instansi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `alamat_kantor` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_tlp_kantor` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `plafond` decimal(15, 0) NULL DEFAULT NULL,
  `jangka_waktu` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rate` float(11, 2) NULL DEFAULT NULL,
  `angsuran` decimal(10, 0) NULL DEFAULT NULL,
  `tanggal_jatuh_tempo` int(11) NULL DEFAULT NULL,
  `produk` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `user_input` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `branch_input` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_input` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_spv1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `final_status_spv1` int(255) NULL DEFAULT 0,
  `date_flag_spv1` datetime NULL DEFAULT NULL,
  `user_spv2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `final_status_spv2` int(255) NULL DEFAULT 0,
  `date_flag_spv2` datetime NULL DEFAULT NULL,
  `user_spv3` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `final_status_spv3` int(255) NULL DEFAULT 0,
  `date_flag_spv3` datetime NULL DEFAULT NULL,
  `final_status` int(255) NULL DEFAULT 0,
  `processed` int(11) NOT NULL DEFAULT 0,
  `updated_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NULL DEFAULT NULL,
  `status_pernikahan` int(11) NULL DEFAULT NULL,
  `pekerjaan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fasilitas` int(11) NULL DEFAULT NULL,
  `flag_process` int(3) NULL DEFAULT NULL,
  `early` int(255) NULL DEFAULT 0,
  `take_over` int(11) NULL DEFAULT 0,
  `take_over_hari_ini` int(11) NULL DEFAULT 0,
  `status_usia_debitur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status_deviasi` int(11) NULL DEFAULT 0,
  `status_kantor` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status_pegawai` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status_fronting_agent` int(11) NULL DEFAULT 0,
  `debtor_classification_code` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `debtor_classification_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`loan_app_no`, `processed`) USING BTREE,
  UNIQUE INDEX `ind`(`loan_app_no`) USING BTREE,
  INDEX `cif`(`no_cif`) USING BTREE,
  INDEX `final_status`(`final_status`, `final_status_spv3`, `final_status_spv2`, `final_status_spv1`) USING BTREE,
  INDEX `user_spv`(`user_spv1`, `date_flag_spv1`, `user_spv2`, `date_flag_spv2`, `user_spv3`, `date_flag_spv3`) USING BTREE,
  INDEX `date_input`(`date_input`) USING BTREE,
  INDEX `kode_cabang`(`kode_cabang`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for detail_file
-- ----------------------------
DROP TABLE IF EXISTS `detail_file`;
CREATE TABLE `detail_file`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `loan_app_no` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `file` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `branch_dir` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `alias` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag` int(3) NULL DEFAULT NULL,
  `flag_exist` int(3) NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NULL DEFAULT NULL,
  `score` int(11) NULL DEFAULT NULL,
  `validation_status` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `validation_status_date` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`, `file`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  INDEX `file`(`file`) USING BTREE,
  INDEX `loan_app_number`(`loan_app_no`) USING BTREE,
  INDEX `branch_dir`(`branch_dir`) USING BTREE,
  INDEX `flag_exist`(`flag_exist`) USING BTREE,
  INDEX `created_at`(`created_at`) USING BTREE,
  INDEX `updated_at`(`updated_at`) USING BTREE,
  INDEX `alias`(`alias`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10666052 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for dokumen_early
-- ----------------------------
DROP TABLE IF EXISTS `dokumen_early`;
CREATE TABLE `dokumen_early`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_dokumen` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for faq
-- ----------------------------
DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `answer` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for flag_spv
-- ----------------------------
DROP TABLE IF EXISTS `flag_spv`;
CREATE TABLE `flag_spv`  (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for internal_memo
-- ----------------------------
DROP TABLE IF EXISTS `internal_memo`;
CREATE TABLE `internal_memo`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for internal_memo_files
-- ----------------------------
DROP TABLE IF EXISTS `internal_memo_files`;
CREATE TABLE `internal_memo_files`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `internal_memo_id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `internal_memo_files_internal_memo_id_foreign`(`internal_memo_id`) USING BTREE,
  CONSTRAINT `internal_memo_files_ibfk_1` FOREIGN KEY (`internal_memo_id`) REFERENCES `internal_memo` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kebijakan_sop
-- ----------------------------
DROP TABLE IF EXISTS `kebijakan_sop`;
CREATE TABLE `kebijakan_sop`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for list_pickup
-- ----------------------------
DROP TABLE IF EXISTS `list_pickup`;
CREATE TABLE `list_pickup`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_app_no` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `user_spv` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `spv_lvl` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` int(11) NULL DEFAULT 0,
  `nik` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'NIK user yang sedang mengakses',
  `locked_by_level` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'Level SPV yang lock (spv1/spv2/spv3)',
  `locked_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu lock dibuat',
  `expired_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu lock akan expire (15 menit dari locked_at)',
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `loan_app_no`(`loan_app_no`, `user_spv`, `spv_lvl`, `status`, `created_at`, `updated_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 232162 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for log_reminder_tbo
-- ----------------------------
DROP TABLE IF EXISTS `log_reminder_tbo`;
CREATE TABLE `log_reminder_tbo`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_cabang` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `loan_app_no` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_cif` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_ktp` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_debitur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tbo_date` date NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `level_spv` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jenis_produk` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `body` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `create_date` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17994 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for log_reminder_tbo_3
-- ----------------------------
DROP TABLE IF EXISTS `log_reminder_tbo_3`;
CREATE TABLE `log_reminder_tbo_3`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_cabang` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `loan_app_no` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_cif` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_ktp` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_debitur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tbo_date` date NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `level_spv` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jenis_produk` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `body` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `create_date` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30071 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for log_reminder_tbo_7
-- ----------------------------
DROP TABLE IF EXISTS `log_reminder_tbo_7`;
CREATE TABLE `log_reminder_tbo_7`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_cabang` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `loan_app_no` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_cif` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_ktp` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_debitur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tbo_date` date NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `level_spv` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jenis_produk` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `body` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `create_date` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 37834 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for log_sla_bpr
-- ----------------------------
DROP TABLE IF EXISTS `log_sla_bpr`;
CREATE TABLE `log_sla_bpr`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_app_no` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nik` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `level_spv` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `isLocked` int(11) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `loan_app_no`(`loan_app_no`) USING BTREE,
  INDEX `nik`(`nik`) USING BTREE,
  INDEX `lvl_spv`(`level_spv`) USING BTREE,
  INDEX `create_at`(`created_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 620188 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for map_produk_dokumen
-- ----------------------------
DROP TABLE IF EXISTS `map_produk_dokumen`;
CREATE TABLE `map_produk_dokumen`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_jenis_produk` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_dokumen` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mandatory` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx`(`id_jenis_produk`, `nama_dokumen`, `mandatory`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 226 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for master_instansi
-- ----------------------------
DROP TABLE IF EXISTS `master_instansi`;
CREATE TABLE `master_instansi`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instansi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `klasifikasi_debitur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 38 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for master_jenis_produk
-- ----------------------------
DROP TABLE IF EXISTS `master_jenis_produk`;
CREATE TABLE `master_jenis_produk`  (
  `id` int(11) NOT NULL,
  `jenis_produk` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for master_klasifikasi_debitur
-- ----------------------------
DROP TABLE IF EXISTS `master_klasifikasi_debitur`;
CREATE TABLE `master_klasifikasi_debitur`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `klasifikasi_debitur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for master_produk
-- ----------------------------
DROP TABLE IF EXISTS `master_produk`;
CREATE TABLE `master_produk`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produk` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jenis_produk` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for master_wilayah
-- ----------------------------
DROP TABLE IF EXISTS `master_wilayah`;
CREATE TABLE `master_wilayah`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wilayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `file` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `popup` int(255) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 87 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for perubahan_jam_layanan
-- ----------------------------
DROP TABLE IF EXISTS `perubahan_jam_layanan`;
CREATE TABLE `perubahan_jam_layanan`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_input` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jam_layanan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `user_input` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_input` datetime NULL DEFAULT NULL,
  `user_spv` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag_spv` int(11) NULL DEFAULT 0,
  `date_flag_spv` datetime NULL DEFAULT NULL,
  `note_spv` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `user_spv1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag_spv1` int(11) NULL DEFAULT 0,
  `date_flag_spv1` datetime NULL DEFAULT NULL,
  `note_spv1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `final_flag` int(11) NULL DEFAULT 0,
  `approve_time` time NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5198 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for perubahan_jam_layanan_detail
-- ----------------------------
DROP TABLE IF EXISTS `perubahan_jam_layanan_detail`;
CREATE TABLE `perubahan_jam_layanan_detail`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_perubahan` int(11) NULL DEFAULT NULL,
  `loan_app_no` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_debitur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `note` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7439 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for perubahan_tgl_tbo
-- ----------------------------
DROP TABLE IF EXISTS `perubahan_tgl_tbo`;
CREATE TABLE `perubahan_tgl_tbo`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_input` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `user_input` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_input` datetime NULL DEFAULT NULL,
  `user_spv` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag_spv` int(11) NULL DEFAULT 0,
  `date_flag_spv` datetime NULL DEFAULT NULL,
  `note_spv` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `user_spv1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag_spv1` int(11) NULL DEFAULT 0,
  `date_flag_spv1` datetime NULL DEFAULT NULL,
  `note_spv1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `user_spv2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag_spv2` int(255) NULL DEFAULT 0,
  `date_flag_spv2` datetime NULL DEFAULT NULL,
  `note_spv2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `final_flag` int(11) NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 302 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for perubahan_tgl_tbo_detail
-- ----------------------------
DROP TABLE IF EXISTS `perubahan_tgl_tbo_detail`;
CREATE TABLE `perubahan_tgl_tbo_detail`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_perubahan` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `loan_app_no` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_debitur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dokumen_tbo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tgl_sebelum_perubahan` date NULL DEFAULT NULL,
  `tgl_sesudah_perubahan` date NULL DEFAULT NULL,
  `perubahan_ke` int(11) NULL DEFAULT 1,
  `note` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 494 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for reason
-- ----------------------------
DROP TABLE IF EXISTS `reason`;
CREATE TABLE `reason`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_flag` int(11) NULL DEFAULT NULL,
  `reason` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for registration
-- ----------------------------
DROP TABLE IF EXISTS `registration`;
CREATE TABLE `registration`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_app_no` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_register` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `processed` int(11) NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uc_no_register`(`no_register`) USING BTREE,
  INDEX `loan_app_no`(`loan_app_no`) USING BTREE,
  INDEX `processed`(`processed`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 166424 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role`  (
  `id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for s_dokumen
-- ----------------------------
DROP TABLE IF EXISTS `s_dokumen`;
CREATE TABLE `s_dokumen`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) NULL DEFAULT NULL,
  `nama_dokumen` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kupen` tinyint(1) NULL DEFAULT NULL,
  `is_mandatory_kupen` tinyint(1) NOT NULL DEFAULT 0,
  `kupeg` tinyint(1) NULL DEFAULT NULL,
  `is_mandatory_kupeg` tinyint(1) NOT NULL,
  `kupen_hybrid` tinyint(1) NULL DEFAULT NULL,
  `is_mandatory_kupen_hybrid` tinyint(1) NULL DEFAULT NULL,
  `label_only` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `s_dokumen_ibfk_1`(`kategori_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for s_dokumen_kategori
-- ----------------------------
DROP TABLE IF EXISTS `s_dokumen_kategori`;
CREATE TABLE `s_dokumen_kategori`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for s_sub_dokumen
-- ----------------------------
DROP TABLE IF EXISTS `s_sub_dokumen`;
CREATE TABLE `s_sub_dokumen`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dokumen_id` int(11) NULL DEFAULT NULL,
  `nama_sub_dokumen` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kupen` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `is_mandatory_kupen` tinyint(1) NULL DEFAULT NULL,
  `kupeg` tinyint(1) NULL DEFAULT NULL,
  `is_mandatory_kupeg` tinyint(1) NOT NULL DEFAULT 0,
  `kupen_hybrid` tinyint(1) NULL DEFAULT NULL,
  `is_mandatory_kupen_hybrid` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `dokumen_id`(`dokumen_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for setting_ai
-- ----------------------------
DROP TABLE IF EXISTS `setting_ai`;
CREATE TABLE `setting_ai`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enable` int(11) NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for setting_time
-- ----------------------------
DROP TABLE IF EXISTS `setting_time`;
CREATE TABLE `setting_time`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `waktu` time NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for t_log_aktifitas
-- ----------------------------
DROP TABLE IF EXISTS `t_log_aktifitas`;
CREATE TABLE `t_log_aktifitas`  (
  `nik` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ket` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `update_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nik`, `created_at`) USING BTREE,
  INDEX `idxuserflag`(`nik`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tbl_log_ai
-- ----------------------------
DROP TABLE IF EXISTS `tbl_log_ai`;
CREATE TABLE `tbl_log_ai`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_app_no` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `request` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `request_date` timestamp NULL DEFAULT NULL,
  `response` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `response_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `response_from_ai` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `request_param_from_ai` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `response_from_ai_date` timestamp NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 40885 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tbl_log_response
-- ----------------------------
DROP TABLE IF EXISTS `tbl_log_response`;
CREATE TABLE `tbl_log_response`  (
  `id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `loan_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `insert_date` datetime NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tmp_limit_overdue
-- ----------------------------
DROP TABLE IF EXISTS `tmp_limit_overdue`;
CREATE TABLE `tmp_limit_overdue`  (
  `kode_cabang` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `total_pengajuan` int(11) NULL DEFAULT 0,
  `total_pencairan` int(11) NULL DEFAULT 0,
  `hutang_tbo_overdue` int(11) NULL DEFAULT 0,
  `hutang_persen_tbo_overdue` int(11) NULL DEFAULT 0,
  `limit_persen_tbo_overdue` int(11) NULL DEFAULT 0,
  `total_persen` int(11) NULL DEFAULT 0,
  `total_limit_overdue` int(11) NULL DEFAULT 0,
  PRIMARY KEY (`kode_cabang`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tmp_resume_perhari
-- ----------------------------
DROP TABLE IF EXISTS `tmp_resume_perhari`;
CREATE TABLE `tmp_resume_perhari`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_cabang` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `branch_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `loan_app_no` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_debitur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `final_status` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_input` datetime NULL DEFAULT NULL,
  `user_spv1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `user_spv2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `user_spv3` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `<10` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `<12` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `<13` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `<15` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `<17` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `<19` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `>19` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `frequensi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `waktu_frequensi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pickup_bpr1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bpr1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pickup_bpr2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bpr2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `total_waktu` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `kode_cabang`(`kode_cabang`) USING BTREE,
  INDEX `loan_app_no`(`loan_app_no`) USING BTREE,
  INDEX `frekuensi`(`frequensi`) USING BTREE,
  INDEX `bpr`(`pickup_bpr1`, `bpr1`, `pickup_bpr2`, `bpr2`) USING BTREE,
  INDEX `total_waktu`(`total_waktu`) USING BTREE,
  INDEX `waktu`(`<10`, `<12`, `<13`, `<15`, `<17`, `>19`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tmp_resume_perhari_new
-- ----------------------------
DROP TABLE IF EXISTS `tmp_resume_perhari_new`;
CREATE TABLE `tmp_resume_perhari_new`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_cabang` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `branch_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `loan_app_no` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_debitur` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `final_status` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date_input` datetime NULL DEFAULT NULL,
  `user_spv1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `user_spv2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `<10` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `<12` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `<13` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `<15` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `<17` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `<19` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `>19` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `frequensi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `waktu_frequensi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pickup_dcrm` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dcrm` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `total_waktu` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tmp_sla_agregate
-- ----------------------------
DROP TABLE IF EXISTS `tmp_sla_agregate`;
CREATE TABLE `tmp_sla_agregate`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NULL DEFAULT NULL,
  `kurang_10` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_kurang10` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kurang_12` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_kurang12` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kurang_13` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_kurang13` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kurang_15` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_kurang15` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kurang_17` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_kurang17` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kurang_19` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_kurang19` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lebih_19` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_lebih19` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `total_pengajuan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `total_waktu_pengajuan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `frekuensi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `frekuensi_waktu` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `waktu_tunggu_bpr1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `waktu_bpr1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `waktu_tunggu_bpr2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `waktu_bpr2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `total_waktu_keseluruhan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `perbatch_ub` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pemenuhan_kekurangan_ub` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rata_waktu_tunggu_bpr1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pengerjaan_bpr1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rata_waktu_tunggu_bpr2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pengerjaan_bpr2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rata_waktu_perhari` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rata_waktu_perhari_ideal` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rata_waktu_perhari_dcrm` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `waktu_terlama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `waktu_tercepat` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tmp_sla_agregate_new
-- ----------------------------
DROP TABLE IF EXISTS `tmp_sla_agregate_new`;
CREATE TABLE `tmp_sla_agregate_new`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NULL DEFAULT NULL,
  `kurang_10` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_kurang10` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kurang_12` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_kurang12` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kurang_13` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_kurang13` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kurang_15` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_kurang15` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kurang_17` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_kurang17` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kurang_19` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_kurang19` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lebih_19` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sum_lebih19` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `total_pengajuan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `total_waktu_pengajuan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `frekuensi` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `frekuensi_waktu` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `waktu_tunggu_dcrm` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `waktu_dcrm` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `total_waktu_keseluruhan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `perbatch_ub` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pemenuhan_kekurangan_ub` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rata_waktu_tunggu_dcrm` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pengerjaan_dcrm` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rata_waktu_perhari` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rata_waktu_perhari_ideal` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rata_waktu_perhari_dcrm` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `waktu_terlama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `waktu_tercepat` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `role` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'your_email_name@domain.ext',
  `name` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `cabang` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
  `nik` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ' ',
  `updated_at` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `blocked_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`nik`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- View structure for v_master_data
-- ----------------------------
DROP VIEW IF EXISTS `v_master_data`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `v_master_data` AS select `data_file`.`loan_app_no` AS `loan_app_no`,`data_file`.`kode_cabang` AS `kode_cabang`,`data_file`.`nama_debitur` AS `nama_debitur`,(case when (`comment`.`level_spv` = 'spv1') then 'SPV Branch' when (`comment`.`level_spv` = 'spv2') then 'BPR1' when (`comment`.`level_spv` = 'spv3') then 'BPR2' end) AS `level_spv`,`comment`.`user_name` AS `user_name`,`users`.`name` AS `name`,(select `c`.`flag_spv` from `comment` `c` where ((`c`.`loan_app_no` = `comment`.`loan_app_no`) and (`c`.`comment_date` = max(`comment`.`comment_date`))) group by `data_file`.`loan_app_no`,`comment`.`level_spv`) AS `flag_spv1`,(case when ((select `c`.`flag_spv` from `comment` `c` where ((`c`.`loan_app_no` = `comment`.`loan_app_no`) and (`c`.`comment_date` = max(`comment`.`comment_date`))) group by `data_file`.`loan_app_no`,`comment`.`level_spv`) = '0') then 'Pending' when ((select `c`.`flag_spv` from `comment` `c` where ((`c`.`loan_app_no` = `comment`.`loan_app_no`) and (`c`.`comment_date` = max(`comment`.`comment_date`))) group by `data_file`.`loan_app_no`,`comment`.`level_spv`) = '1') then 'Verify' when ((select `c`.`flag_spv` from `comment` `c` where ((`c`.`loan_app_no` = `comment`.`loan_app_no`) and (`c`.`comment_date` = max(`comment`.`comment_date`))) group by `data_file`.`loan_app_no`,`comment`.`level_spv`) = '2') then 'Not Verify' when ((select `c`.`flag_spv` from `comment` `c` where ((`c`.`loan_app_no` = `comment`.`loan_app_no`) and (`c`.`comment_date` = max(`comment`.`comment_date`))) group by `data_file`.`loan_app_no`,`comment`.`level_spv`) = '3') then 'TBO' when ((select `c`.`flag_spv` from `comment` `c` where ((`c`.`loan_app_no` = `comment`.`loan_app_no`) and (`c`.`comment_date` = max(`comment`.`comment_date`))) group by `data_file`.`loan_app_no`,`comment`.`level_spv`) = '4') then 'Reject' end) AS `flag_spv_desc`,`data_file`.`date_input` AS `date_input`,max(`comment`.`comment_date`) AS `review_date` from ((`data_file` join `comment` on((`data_file`.`loan_app_no` = `comment`.`loan_app_no`))) left join `users` on((`comment`.`user_name` = `users`.`nik`))) where ((cast(`data_file`.`date_input` as date) between '2022-09-14' and '2022-09-14') and (`data_file`.`final_status` <> 4)) group by `data_file`.`loan_app_no`,`comment`.`level_spv` order by `data_file`.`date_input`,`comment`.`comment_date`;

-- ----------------------------
-- Function structure for BIG_SEC_TO_TIME
-- ----------------------------
DROP FUNCTION IF EXISTS `BIG_SEC_TO_TIME`;
delimiter ;;
CREATE FUNCTION `BIG_SEC_TO_TIME`(SECS DECIMAL(65,0))
 RETURNS text CHARSET latin1
  READS SQL DATA 
  DETERMINISTIC
BEGIN
    DECLARE HEURES DECIMAL(65,0);
    DECLARE MINUTES DECIMAL(65,0);
    DECLARE SECONDES DECIMAL(65,0);
    DECLARE FORMATTED_HOURS TEXT;
    
    IF (SECS IS NULL) THEN 
        RETURN NULL; 
    END IF;
    
    -- Hitung total jam tanpa modulo 24
    SET HEURES = FLOOR(SECS / 3600);
    
    -- Hitung menit
    SET MINUTES = MOD(FLOOR(SECS / 60), 60);
    
    -- Hitung detik
    SET SECONDES = MOD(SECS, 60);
    
    -- Format jam dengan minimal 2 digit tapi tidak dibatasi maksimalnya
    IF HEURES < 10 THEN
        SET FORMATTED_HOURS = CONCAT('0', HEURES);
    ELSE
        SET FORMATTED_HOURS = CAST(HEURES AS CHAR);
    END IF;
    
    RETURN CONCAT(
        FORMATTED_HOURS, ':',
        LPAD(CAST(MINUTES AS CHAR), 2, '0'), ':',
        LPAD(CAST(SECONDES AS CHAR), 2, '0')
    );
END
;;
delimiter ;

-- ----------------------------
-- Function structure for BIG_TIME_TO_SEC
-- ----------------------------
DROP FUNCTION IF EXISTS `BIG_TIME_TO_SEC`;
delimiter ;;
CREATE FUNCTION `BIG_TIME_TO_SEC`(TIME TEXT)
 RETURNS decimal(65,0)
  READS SQL DATA 
  DETERMINISTIC
BEGIN
    DECLARE HEURES DECIMAL(65,0);
    DECLARE MINUTES DECIMAL(65,0);
    DECLARE SECONDES DECIMAL(65,0);
    DECLARE total_seconds DECIMAL(65,0);
    
    IF (TIME IS NULL) THEN 
        RETURN NULL; 
    END IF;
    
    -- Ekstrak jam, menit, detik dari format waktu
    SET HEURES = CAST(SUBSTRING_INDEX(TIME, ':', 1) AS DECIMAL(65,0));
    SET MINUTES = CAST(SUBSTRING(TIME FROM -5 FOR 2) AS DECIMAL(65,0));
    SET SECONDES = CAST(SUBSTRING(TIME FROM -2 FOR 2) AS DECIMAL(65,0));
    
    -- Hitung total detik
    SET total_seconds = SECONDES;
    SET total_seconds = total_seconds + (MINUTES * 60);
    SET total_seconds = total_seconds + (HEURES * 3600);
    
    RETURN total_seconds;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for check_open
-- ----------------------------
DROP PROCEDURE IF EXISTS `check_open`;
delimiter ;;
CREATE PROCEDURE `check_open`(IN `v_loan_app_no` varchar(255),IN `v_nik` varchar(255))
BEGIN
	SELECT
	log_sla_bpr.created_at
FROM
	data_file
	INNER JOIN
	log_sla_bpr
	ON 
	data_file.loan_app_no = log_sla_bpr.loan_app_no
	where data_file.loan_app_no=v_loan_app_no and nik=v_nik
	GROUP BY data_file.loan_app_no	
	ORDER BY created_at;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for check_role
-- ----------------------------
DROP PROCEDURE IF EXISTS `check_role`;
delimiter ;;
CREATE PROCEDURE `check_role`(IN `nik_` varchar(8))
BEGIN
	#Routine body goes here...
	select role from users where nik = nik_;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for check_time_null
-- ----------------------------
DROP FUNCTION IF EXISTS `check_time_null`;
delimiter ;;
CREATE FUNCTION `check_time_null`(`time` integer)
 RETURNS varchar(20) CHARSET latin1
BEGIN
	DECLARE t VARCHAR(20);
	#Routine body goes here...
	IF time is null THEN
		SET t = "00:00:00";
	ELSE
		SET t=time;
	END IF;
	RETURN t;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for check_time_null_to_int
-- ----------------------------
DROP FUNCTION IF EXISTS `check_time_null_to_int`;
delimiter ;;
CREATE FUNCTION `check_time_null_to_int`(`time` int)
 RETURNS int(11)
BEGIN
	#Routine body goes here...
DECLARE t int;
	#Routine body goes here...
	IF time is null THEN
		SET t = 0;
	ELSE
		SET t=time;
	END IF;
	RETURN t;
	RETURN 0;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for daily_bpr1
-- ----------------------------
DROP PROCEDURE IF EXISTS `daily_bpr1`;
delimiter ;;
CREATE PROCEDURE `daily_bpr1`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
select sum(a) as a,sum(b) as b,sum(c) as c,sum(d) as d,sum(e)as e ,sum(f)as f,sum(g)as g  from
(
select user_spv2 as nik, 
count(IF( time(date_flag_spv1) <= '10:00:00', 1, NULL )) as a,
count(IF( time(date_flag_spv1) > '10:00:00' and time(date_flag_spv1) <= '12:00:00', 1, NULL )) as b,
count(IF( time(date_flag_spv1) > '12:00:00' and time(date_flag_spv1) <= '13:00:00', 1, NULL )) as c,
count(IF( time(date_flag_spv1) > '13:00:00' and time(date_flag_spv1) <= '15:00:00', 1, NULL )) as d,
count(IF( time(date_flag_spv1) > '15:00:00' and time(date_flag_spv1) <= '17:00:00', 1, NULL )) as e,
count(IF( time(date_flag_spv1) > '17:00:00' and time(date_flag_spv1) <= '19:00:00', 1, NULL )) as f,
count(IF( time(date_flag_spv1) > '19:00:00', 1, NULL )) as g
from data_file
where
date(date_input) BETWEEN dari AND sampai
and user_spv2 is not null
GROUP BY 1
) x;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for daily_bpr2
-- ----------------------------
DROP PROCEDURE IF EXISTS `daily_bpr2`;
delimiter ;;
CREATE PROCEDURE `daily_bpr2`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
select sum(a) as a,sum(b) as b,sum(c) as c,sum(d) as d,sum(e)as e,sum(f)as f,sum(g)as g from
(
select user_spv3 as nik, 
count(IF( time(date_flag_spv2) <= '10:00:00', 1, NULL )) as a,
count(IF( time(date_flag_spv2) > '10:00:00' and time(date_flag_spv2) <= '12:00:00', 1, NULL )) as b,
count(IF( time(date_flag_spv2) > '12:00:00' and time(date_flag_spv2) <= '13:00:00', 1, NULL )) as c,
count(IF( time(date_flag_spv2) > '13:00:00' and time(date_flag_spv2) <= '15:00:00', 1, NULL )) as d,
count(IF( time(date_flag_spv2) > '15:00:00' and time(date_flag_spv2) <= '17:00:00', 1, NULL )) as e,
count(IF( time(date_flag_spv2) > '17:00:00' and time(date_flag_spv2) <= '19:00:00', 1, NULL )) as f,
count(IF( time(date_flag_spv2) > '19:00:00', 1, NULL )) as g
from data_file
where
date(date_input) BETWEEN dari AND sampai
and user_spv3 is not null
GROUP BY 1
) x;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for daily_dcrm
-- ----------------------------
DROP PROCEDURE IF EXISTS `daily_dcrm`;
delimiter ;;
CREATE PROCEDURE `daily_dcrm`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
select sum(a) as a,sum(b) as b,sum(c) as c,sum(d) as d,sum(e)as e,sum(f)as f,sum(g)as g from
(
select user_spv3 as nik, 
count(IF( time(date_flag_spv1) <= '10:00:00', 1, NULL )) as a,
count(IF( time(date_flag_spv1) > '10:00:00' and time(date_flag_spv3) <= '12:00:00', 1, NULL )) as b,
count(IF( time(date_flag_spv1) > '12:00:00' and time(date_flag_spv3) <= '13:00:00', 1, NULL )) as c,
count(IF( time(date_flag_spv1) > '13:00:00' and time(date_flag_spv3) <= '15:00:00', 1, NULL )) as d,
count(IF( time(date_flag_spv1) > '15:00:00' and time(date_flag_spv3) <= '17:00:00', 1, NULL )) as e,
count(IF( time(date_flag_spv1) > '17:00:00' and time(date_flag_spv3) <= '19:00:00', 1, NULL )) as f,
count(IF( time(date_flag_spv1) > '19:00:00', 1, NULL )) as g
from data_file
where
date(date_input) BETWEEN dari AND sampai
and user_spv3 is not null
GROUP BY 1
) x;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for f_check_open
-- ----------------------------
DROP FUNCTION IF EXISTS `f_check_open`;
delimiter ;;
CREATE FUNCTION `f_check_open`(`v_loan_app_no` varchar(255),`v_nik` varchar(255), approve_date DATETIME)
 RETURNS datetime
BEGIN
	
  DECLARE m_createddate DATETIME;
	SET m_createddate = (
	SELECT
	log_sla_bpr.created_at
FROM
	data_file
	INNER JOIN
	log_sla_bpr
	ON 
	data_file.loan_app_no = log_sla_bpr.loan_app_no
	where data_file.loan_app_no=v_loan_app_no and nik=v_nik and log_sla_bpr.created_at < approve_date
	ORDER BY created_at desc
	limit 1
	);
	RETURN m_createddate;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for f_check_open_copy1
-- ----------------------------
DROP FUNCTION IF EXISTS `f_check_open_copy1`;
delimiter ;;
CREATE FUNCTION `f_check_open_copy1`(`v_loan_app_no` varchar(255),`v_nik` varchar(255), approve_date DATETIME)
 RETURNS datetime
BEGIN
	
  DECLARE m_createddate DATETIME;
	SET m_createddate = (
	SELECT
	log_sla_bpr.created_at
FROM
	data_file
	INNER JOIN
	log_sla_bpr
	ON 
	data_file.loan_app_no = log_sla_bpr.loan_app_no
	where data_file.loan_app_no=v_loan_app_no and nik=v_nik and date(log_sla_bpr.created_at) = date(approve_date)
	 
	GROUP BY data_file.loan_app_no	
	ORDER BY created_at
	);
	RETURN m_createddate;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for f_check_role
-- ----------------------------
DROP FUNCTION IF EXISTS `f_check_role`;
delimiter ;;
CREATE FUNCTION `f_check_role`(`nik_` varchar(8))
 RETURNS varchar(4) CHARSET latin1
BEGIN
	
 DECLARE roles VARCHAR(20);
 SET roles = (select role from users where nik = nik_);
	RETURN roles;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for f_get_bpr_time
-- ----------------------------
DROP FUNCTION IF EXISTS `f_get_bpr_time`;
delimiter ;;
CREATE FUNCTION `f_get_bpr_time`(`date1` datetime,`date2` datetime)
 RETURNS text CHARSET latin1
BEGIN
	#Routine body goes here...
		DECLARE jam int;
		DECLARE menit int;
		DECLARE detik int;
		DECLARE selisih TEXT;
		SET jam = TIMESTAMPDIFF(HOUR,date1,date2);
		SET menit = MOD(TIMESTAMPDIFF(MINUTE,date1,date2),60);
		SET detik = (TIMESTAMPDIFF(SECOND,date1,date2)%3600)%60 ;

	  SET selisih = CONCAT(
		IF(jam <0 OR jam is null ,"00",
			 IF(LENGTH(jam) < 2, LPAD(jam,2,"0"),jam)) ,":",
		IF(menit <0 OR menit is null ,"00",
			 IF(LENGTH(menit) < 2, LPAD(menit,2,"0"),menit)) ,":",
		IF(detik <0 OR detik is null ,"00",
			 IF(LENGTH(detik) < 2, LPAD(detik,2,"0"),detik)));
		
	RETURN selisih;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for f_get_bpr_time_today
-- ----------------------------
DROP FUNCTION IF EXISTS `f_get_bpr_time_today`;
delimiter ;;
CREATE FUNCTION `f_get_bpr_time_today`(`loan_app_id` varchar(30),`lvl_spv` varchar(10))
 RETURNS datetime
BEGIN
	#Routine body goes here...
	DECLARE tgl datetime;
set tgl =
(select 
	comment_date 
 from comment x inner join data_file y on x.loan_app_no = y.loan_app_no
where x.loan_app_no = loan_app_id and date(x.comment_date) = date(y.date_input) and level_spv = lvl_spv order by x.comment_date desc limit 1);
	RETURN tgl;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for f_get_comment_date_spv1
-- ----------------------------
DROP FUNCTION IF EXISTS `f_get_comment_date_spv1`;
delimiter ;;
CREATE FUNCTION `f_get_comment_date_spv1`(`loan_app_no` varchar(30))
 RETURNS varchar(50) CHARSET latin1
BEGIN
	#Routine body goes here...
	DECLARE selisih VARCHAR(50);
	SET @cnt = 0;
	SET selisih = 
	(
	-- select timediff(x.comment_date, y.date_input
	select CONCAT(
IF(LENGTH(TIMESTAMPDIFF(HOUR,y.date_input,x.comment_date)) < 2,
   LPAD(TIMESTAMPDIFF(HOUR,y.date_input,x.comment_date),2,"0"), 
	 TIMESTAMPDIFF(HOUR,y.date_input,x.comment_date)) ,":",
LPAD(MOD(TIMESTAMPDIFF(MINUTE,y.date_input,x.comment_date),60),2,"0"),":",
LPAD((TIMESTAMPDIFF(SECOND,y.date_input,x.comment_date)%3600)%60 ,2,"0") 
)
as selisih from (
select *, (@cnt := @cnt + 1) AS `rowNumber` from comment v where v.loan_app_no = loan_app_no ) x
inner join data_file y on x.loan_app_no = y.loan_app_no 
where x.rowNumber=1 and date(x.comment_date)=date(y.date_input));

 IF selisih is NULL THEN
 set selisih = "00:00:00";
 end if;
 
	RETURN selisih;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for f_get_dcrm_time_today
-- ----------------------------
DROP FUNCTION IF EXISTS `f_get_dcrm_time_today`;
delimiter ;;
CREATE FUNCTION `f_get_dcrm_time_today`(loan_app_id varchar(30), lvl_spv varchar(10))
 RETURNS datetime
BEGIN
    DECLARE tgl datetime;
    
    IF lvl_spv = 'spv3' THEN
        -- Kondisi khusus untuk spv3
        SET tgl = (
            SELECT 
                comment_date 
            FROM comment x 
            INNER JOIN data_file y ON x.loan_app_no = y.loan_app_no
            WHERE x.loan_app_no = loan_app_id 
                AND DATE(x.comment_date) = DATE(y.date_input) 
                AND level_spv IN ('spv3', 'spv4') 
            ORDER BY x.comment_date DESC 
            LIMIT 1
        );
    ELSE
        -- Kondisi default untuk level_spv lainnya
        SET tgl = (
            SELECT 
                comment_date 
            FROM comment x 
            INNER JOIN data_file y ON x.loan_app_no = y.loan_app_no
            WHERE x.loan_app_no = loan_app_id 
                AND DATE(x.comment_date) = DATE(y.date_input) 
                AND level_spv = lvl_spv 
            ORDER BY x.comment_date DESC 
            LIMIT 1
        );
    END IF;
    
    RETURN tgl;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for f_get_final_status
-- ----------------------------
DROP FUNCTION IF EXISTS `f_get_final_status`;
delimiter ;;
CREATE FUNCTION `f_get_final_status`(`loan_app_no` varchar(20))
 RETURNS varchar(20) CHARSET latin1
BEGIN
	#Routine body goes here...
DECLARE flag varchar(100);
set flag =
(select 
	CASE
    WHEN x.final_status = "0" THEN "Pending"            
    WHEN x.final_status = "1" THEN "Verify"
    WHEN x.final_status = "2" THEN "Not Verify"
		WHEN x.final_status = "3" THEN "TBO"
		WHEN x.final_status = "4" THEN "Reject"
	END as flag_spv
 from data_file x
where x.loan_app_no = loan_app_no);
	RETURN flag;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for f_get_last_comment_spv3
-- ----------------------------
DROP FUNCTION IF EXISTS `f_get_last_comment_spv3`;
delimiter ;;
CREATE FUNCTION `f_get_last_comment_spv3`(`loan_app_no` varchar(20))
 RETURNS varchar(20) CHARSET latin1
BEGIN
	#Routine body goes here...
DECLARE flag varchar(100);
set flag =
(select 
	CASE
    WHEN x.flag_spv = "0" THEN "Pending"            
    WHEN x.flag_spv = "1" THEN "Verify"
    WHEN x.flag_spv = "2" THEN "Not Verify"
		WHEN x.flag_spv = "3" THEN "TBO"
		WHEN x.flag_spv = "4" THEN "Reject"
	END as flag_spv
 from comment x inner join data_file y on x.loan_app_no = y.loan_app_no
where x.loan_app_no = loan_app_no and date(x.comment_date) = date(y.date_input) order by x.comment_date desc limit 1);
	RETURN flag;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for f_get_waktu_freq
-- ----------------------------
DROP FUNCTION IF EXISTS `f_get_waktu_freq`;
delimiter ;;
CREATE FUNCTION `f_get_waktu_freq`(`loan_app_no` varchar(30))
 RETURNS varchar(100) CHARSET latin1
BEGIN
	#Routine body goes here...
DECLARE selisih varchar(100);

SET @cnt = 0;


SET selisih  = (
select CONCAT(
IF(LENGTH(TIMESTAMPDIFF(HOUR,(select v.comment_date from `comment` v where v.loan_app_no = loan_app_no limit 1),MAX(x.comment_date))) < 1,
   LPAD(TIMESTAMPDIFF(HOUR,(select v.comment_date from `comment` v where v.loan_app_no = loan_app_no limit 1),MAX(x.comment_date)),2,"0"), 
	 TIMESTAMPDIFF(HOUR,(select v.comment_date from `comment` v where v.loan_app_no = loan_app_no limit 1),MAX(x.comment_date))) ,":",
LPAD(MOD(TIMESTAMPDIFF(MINUTE,(select v.comment_date from `comment` v where v.loan_app_no = loan_app_no limit 1),MAX(x.comment_date)),60),2,"0"),":",
LPAD((TIMESTAMPDIFF(SECOND,(select v.comment_date from `comment` v where v.loan_app_no = loan_app_no limit 1),MAX(x.comment_date))%3600)%60 ,2,"0") 
)
AS selisih from (
select *, (@cnt := @cnt + 1) AS `rowNumber` from comment v where v.loan_app_no = loan_app_no ) x
inner join data_file y on x.loan_app_no = y.loan_app_no
where date(x.comment_date)=date(y.date_input) and x.rowNumber not in (1,(select count(1)-1 from comment z where z.loan_app_no =x.loan_app_no and date(z.comment_date)=date(y.date_input) GROUP BY z.loan_app_no),(select count(1) from comment z where z.loan_app_no =x.loan_app_no  and date(z.comment_date)=date(y.date_input) GROUP BY z.loan_app_no))
);
 
 IF selisih is NULL THEN
 set selisih = "00:00:00";
 end if;

	RETURN selisih;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for f_get_waktu_freq_new
-- ----------------------------
DROP FUNCTION IF EXISTS `f_get_waktu_freq_new`;
delimiter ;;
CREATE FUNCTION `f_get_waktu_freq_new`(var_loan_app_no varchar(30))
 RETURNS time
BEGIN
    DECLARE start_time DATETIME;
    DECLARE end_time DATETIME;
    DECLARE total_rows INT;
    DECLARE time_diff TIME;
    DECLARE input_date DATE;
    
    -- Ambil tanggal input dari data_file
    SELECT DATE(date_input) INTO input_date
    FROM data_file
    WHERE loan_app_no = var_loan_app_no
    LIMIT 1;
    
    -- Hitung jumlah baris untuk loan_app_no tersebut pada tanggal yang sama
    SELECT COUNT(*) INTO total_rows
    FROM comment
    WHERE loan_app_no = var_loan_app_no
    AND DATE(comment_date) = input_date;
    
    IF total_rows <= 1 THEN
        RETURN '00:00:00';
    END IF;
    
    -- Ambil waktu awal (baris pertama) pada tanggal yang sama
    SELECT comment_date INTO start_time
    FROM comment
    WHERE loan_app_no = var_loan_app_no
    AND DATE(comment_date) = input_date
    ORDER BY comment_date ASC
    LIMIT 1;
    
    -- Ambil waktu akhir (selalu baris total_rows - 1) pada tanggal yang sama
    SELECT comment_date INTO end_time
    FROM (
        SELECT comment_date, 
               @row_num := @row_num + 1 AS row_num
        FROM comment, 
             (SELECT @row_num := 0) r
        WHERE loan_app_no = var_loan_app_no
        AND DATE(comment_date) = input_date
        ORDER BY comment_date ASC
    ) ranked
    WHERE row_num = (total_rows - 1);
    
    -- Hitung selisih waktu
    SET time_diff = TIMEDIFF(end_time, start_time);
    
    -- Return 00:00:00 jika hasilnya NULL
    RETURN IFNULL(time_diff, '00:00:00');
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for get_comment_date_spv1
-- ----------------------------
DROP PROCEDURE IF EXISTS `get_comment_date_spv1`;
delimiter ;;
CREATE PROCEDURE `get_comment_date_spv1`(IN `loan_app_no` varchar(30))
BEGIN
	#Routine body goes here...
SET @cnt = 0;
select timediff(x.comment_date, y.date_input) as selisih from (
select *, (@cnt := @cnt + 1) AS `rowNumber` from comment v where v.loan_app_no = loan_app_no ) x
inner join data_file y on x.loan_app_no = y.loan_app_no
where x.rowNumber=1;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for get_document
-- ----------------------------
DROP PROCEDURE IF EXISTS `get_document`;
delimiter ;;
CREATE PROCEDURE `get_document`(IN `loan_app_no1` varchar(255))
BEGIN
select * from comment where loan_app_no = loan_app_no1 and tbo_date = date(now() + INTERVAL 1 WEEK);
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for get_document_0_day
-- ----------------------------
DROP PROCEDURE IF EXISTS `get_document_0_day`;
delimiter ;;
CREATE PROCEDURE `get_document_0_day`(IN `loan_app_no1` varchar(255))
BEGIN
select reason from comment where loan_app_no=loan_app_no1 and tbo_date = date(now());
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for get_document_3_day
-- ----------------------------
DROP PROCEDURE IF EXISTS `get_document_3_day`;
delimiter ;;
CREATE PROCEDURE `get_document_3_day`(IN `loan_app_no1` varchar(255))
BEGIN
select reason from comment where loan_app_no=loan_app_no1 and tbo_date = date(now() + INTERVAL 3 DAY);
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for get_limit_tbo_overdue
-- ----------------------------
DROP PROCEDURE IF EXISTS `get_limit_tbo_overdue`;
delimiter ;;
CREATE PROCEDURE `get_limit_tbo_overdue`()
BEGIN

update branch_tbo set limit_persen_tbo_overdue = jml;

	#Routine body goes here...
-- get total pengajuan
insert into branch_tbo (branch_code, total_pengajuan)
select kode_cabang,count(1) as total_pengajuan from data_file where YEAR(date_input) >= YEAR(CURDATE())-1 GROUP BY kode_cabang
ON DUPLICATE KEY UPDATE total_pengajuan = VALUES(total_pengajuan);

-- get droping
insert into branch_tbo (branch_code, total_pencairan)
select kode_cabang,count(1) from data_file where YEAR(date_input) >= YEAR(CURDATE())-1  and (final_status =1 or final_status =3) GROUP BY kode_cabang
ON DUPLICATE KEY UPDATE total_pencairan = VALUES(total_pencairan);


-- get tbo overdue
insert into branch_tbo (branch_code, tbo_overdue)
SELECT
    df.kode_cabang,
    COUNT(x.loan_app_no) as tbo_overdue
FROM
    data_file df
LEFT JOIN (
    SELECT
        comment.loan_app_no,
        max(tbo_date) AS tbo_date 
    FROM
        COMMENT 
    WHERE
        comment.loan_app_no IN (SELECT data_file.loan_app_no FROM data_file WHERE final_status = 3) 
    GROUP BY
        comment.loan_app_no 
    HAVING
        date(max(tbo_date)) < date(now())
) x ON df.loan_app_no = x.loan_app_no
GROUP BY df.kode_cabang
ON DUPLICATE KEY UPDATE tbo_overdue = VALUES(tbo_overdue);

-- set total limit overdue
update branch_tbo set total_limit_overdue = (total_pencairan*limit_persen_tbo_overdue)/100;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for get_limit_tbo_overdue_bak
-- ----------------------------
DROP PROCEDURE IF EXISTS `get_limit_tbo_overdue_bak`;
delimiter ;;
CREATE PROCEDURE `get_limit_tbo_overdue_bak`()
BEGIN

update branch_tbo set limit_persen_tbo_overdue = jml;

	#Routine body goes here...
-- get total pengajuan
insert into branch_tbo (branch_code, total_pengajuan)
select df.kode_cabang as branch_code,count(1) as total_pengajuan from data_file df GROUP BY df.kode_cabang
ON DUPLICATE KEY UPDATE total_pengajuan = VALUES(total_pengajuan);

-- get droping
insert into branch_tbo (branch_code, total_pencairan)
select df.kode_cabang,count(1) as total_pencairan from data_file df where df.final_status =1 or df.final_status =3 GROUP BY df.kode_cabang
ON DUPLICATE KEY UPDATE total_pencairan = VALUES(total_pencairan);


-- get tbo overdue
insert into branch_tbo (branch_code, tbo_overdue)
SELECT
	data_file.kode_cabang,count(x.loan_app_no) as tbo_overdue
FROM
	(
	SELECT
		comment.loan_app_no,
		max( tbo_date ) AS tbo_date 
	FROM
	COMMENT 
	WHERE
		comment.loan_app_no IN ( SELECT data_file.loan_app_no FROM data_file WHERE final_status = 3 ) 
	GROUP BY
		comment.loan_app_no 
	HAVING
		date(
			max( tbo_date )) < date(
		now()) 
	) x 
	LEFT JOIN data_file on data_file.loan_app_no = x.loan_app_no
	GROUP BY data_file.kode_cabang
ON DUPLICATE KEY UPDATE tbo_overdue = VALUES(tbo_overdue);

-- set total limit overdue
update branch_tbo set total_limit_overdue = (total_pencairan*limit_persen_tbo_overdue)/100;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for get_waktu_pengerjaan_dcrm
-- ----------------------------
DROP FUNCTION IF EXISTS `get_waktu_pengerjaan_dcrm`;
delimiter ;;
CREATE FUNCTION `get_waktu_pengerjaan_dcrm`(`var_loan_app_no` VARCHAR(30))
 RETURNS varchar(30) CHARSET latin1
BEGIN
   DECLARE selisih_waktu VARCHAR(30);

    -- Hitung selisih waktu dalam detik dan konversi ke format hh:mm:ss
    SELECT
        BIG_SEC_TO_TIME(BIG_TIME_TO_SEC(TIMEDIFF(updated_at, created_at)))
    INTO selisih_waktu
    FROM
        list_pickup
    WHERE
        loan_app_no = var_loan_app_no
        AND (spv_lvl = 'spv3' OR spv_lvl = 'spv4')
    ORDER BY created_at DESC
    LIMIT 1;
		
		if selisih_waktu IS NULL THEN 
					
			SELECT 
            CASE
                WHEN df.date_flag_spv2 IS NULL OR df.date_flag_spv1 IS NULL THEN
                    '00:00:00'
                ELSE
                    BIG_SEC_TO_TIME(BIG_TIME_TO_SEC(TIMEDIFF(df.date_flag_spv2, df.date_flag_spv1)))
            END AS selisih_default
        INTO selisih_waktu 
        FROM data_file df 
        WHERE df.loan_app_no = var_loan_app_no;
				
		END IF;

    -- Kembalikan hasil
    RETURN selisih_waktu;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for get_waktu_pengerjaan_spv2
-- ----------------------------
DROP FUNCTION IF EXISTS `get_waktu_pengerjaan_spv2`;
delimiter ;;
CREATE FUNCTION `get_waktu_pengerjaan_spv2`(`var_loan_app_no` VARCHAR(30))
 RETURNS varchar(30) CHARSET latin1
BEGIN
   DECLARE selisih_waktu VARCHAR(30);

    -- Hitung selisih waktu dalam detik dan konversi ke format hh:mm:ss
    SELECT
        SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(updated_at, created_at)))
    INTO selisih_waktu
    FROM
        list_pickup
    WHERE
        loan_app_no = var_loan_app_no
        AND spv_lvl = 'spv2'
    ORDER BY created_at DESC
    LIMIT 1;
		
		if selisih_waktu IS NULL THEN 
					
			SELECT 
            CASE
                WHEN df.date_flag_spv2 IS NULL OR df.date_flag_spv1 IS NULL THEN
                    '00:00:00'
                ELSE
                    SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(df.date_flag_spv2, df.date_flag_spv1)))
            END AS selisih_default
        INTO selisih_waktu 
        FROM data_file df 
        WHERE df.loan_app_no = var_loan_app_no;
				
		END IF;

    -- Kembalikan hasil
    RETURN selisih_waktu;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for get_waktu_pengerjaan_spv3
-- ----------------------------
DROP FUNCTION IF EXISTS `get_waktu_pengerjaan_spv3`;
delimiter ;;
CREATE FUNCTION `get_waktu_pengerjaan_spv3`(`var_loan_app_no` VARCHAR(30))
 RETURNS varchar(30) CHARSET latin1
BEGIN
    DECLARE selisih_waktu VARCHAR(30);

    -- Hitung selisih waktu dalam detik dan konversi ke format hh:mm:ss
    SELECT
        SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(updated_at, created_at)))
    INTO selisih_waktu
    FROM
        list_pickup
    WHERE
        loan_app_no = var_loan_app_no
        AND (spv_lvl = 'spv3' OR spv_lvl = 'spv4')
    ORDER BY created_at DESC
    LIMIT 1;
		
		if selisih_waktu IS NULL THEN 
					
			SELECT 
            CASE
                WHEN df.date_flag_spv3 IS NULL OR df.date_flag_spv2 IS NULL THEN
                    '00:00:00'
                ELSE
                    SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(df.date_flag_spv3, df.date_flag_spv2)))
            END AS selisih_default
        INTO selisih_waktu 
        FROM data_file df 
        WHERE df.loan_app_no = var_loan_app_no;
				
		END IF;

    -- Kembalikan hasil
    RETURN selisih_waktu;
    END
;;
delimiter ;

-- ----------------------------
-- Function structure for get_waktu_tunggu_dcrm
-- ----------------------------
DROP FUNCTION IF EXISTS `get_waktu_tunggu_dcrm`;
delimiter ;;
CREATE FUNCTION `get_waktu_tunggu_dcrm`(`var_loan_app_no` VARCHAR(30))
 RETURNS varchar(30) CHARSET latin1
BEGIN
        DECLARE selisih_detik INT;
				DECLARE selisih_waktu VARCHAR(30);

				-- Hitung selisih waktu dalam detik
				SELECT
						-- TIME_TO_SEC(TIMEDIFF(lp.created_at, df.date_flag_spv1))
						CASE
							WHEN (lp.created_at> df.date_flag_spv1) THEN 
								TIME_TO_SEC(TIMEDIFF(lp.created_at, df.date_flag_spv1))
							ELSE
							0
						END as selisih
				INTO selisih_detik
				FROM
						data_file df
				JOIN
						list_pickup lp
				ON
						df.loan_app_no = lp.loan_app_no
				WHERE
						df.loan_app_no = var_loan_app_no
						AND (lp.spv_lvl = 'spv3' OR lp.spv_lvl = 'spv4')
				ORDER BY lp.created_at DESC
				LIMIT 1;
				
				if selisih_detik IS NULL THEN 
			SELECT 
            CASE
                WHEN df.date_flag_spv2 IS NULL OR df.date_flag_spv1 IS NULL THEN
                    0
                ELSE
                    TIME_TO_SEC(TIMEDIFF(df.date_flag_spv2, df.date_flag_spv1))
            END AS selisih_default
        INTO selisih_detik 
        FROM data_file df 
        WHERE df.loan_app_no = var_loan_app_no;
		END IF;

				-- Periksa apakah selisih_detik negatif dan set ke 0 jika negatif
				IF selisih_detik > 302399 THEN
						SET selisih_detik = 302399;
				END IF;
				
				IF selisih_detik < 0 THEN
						SET selisih_detik = 0;
				END IF;

				-- Konversi detik ke format hh:mm:ss
				SET selisih_waktu = SEC_TO_TIME(selisih_detik);

				-- Kembalikan hasil
				RETURN selisih_waktu;
    END
;;
delimiter ;

-- ----------------------------
-- Function structure for get_waktu_tunggu_spv2
-- ----------------------------
DROP FUNCTION IF EXISTS `get_waktu_tunggu_spv2`;
delimiter ;;
CREATE FUNCTION `get_waktu_tunggu_spv2`(`var_loan_app_no` VARCHAR(30))
 RETURNS varchar(30) CHARSET latin1
BEGIN
        DECLARE selisih_detik INT;
				DECLARE selisih_waktu VARCHAR(30);

				-- Hitung selisih waktu dalam detik
				SELECT
						-- TIME_TO_SEC(TIMEDIFF(lp.created_at, df.date_flag_spv1))
						CASE
							WHEN (lp.created_at> df.date_flag_spv1) THEN 
								TIME_TO_SEC(TIMEDIFF(lp.created_at, df.date_flag_spv1))
							ELSE
							0
						END as selisih
				INTO selisih_detik
				FROM
						data_file df
				JOIN
						list_pickup lp
				ON
						df.loan_app_no = lp.loan_app_no
				WHERE
						df.loan_app_no = var_loan_app_no
						AND lp.spv_lvl = 'spv2'
				ORDER BY lp.created_at DESC
				LIMIT 1;
				
				if selisih_detik IS NULL THEN 
			SELECT 
            CASE
                WHEN df.date_flag_spv2 IS NULL OR df.date_flag_spv1 IS NULL THEN
                    0
                ELSE
                    TIME_TO_SEC(TIMEDIFF(df.date_flag_spv2, df.date_flag_spv1))
            END AS selisih_default
        INTO selisih_detik 
        FROM data_file df 
        WHERE df.loan_app_no = var_loan_app_no;
		END IF;

				-- Periksa apakah selisih_detik negatif dan set ke 0 jika negatif
				IF selisih_detik > 302399 THEN
						SET selisih_detik = 302399;
				END IF;

				-- Konversi detik ke format hh:mm:ss
				SET selisih_waktu = SEC_TO_TIME(selisih_detik);

				-- Kembalikan hasil
				RETURN selisih_waktu;
    END
;;
delimiter ;

-- ----------------------------
-- Function structure for get_waktu_tunggu_spv3
-- ----------------------------
DROP FUNCTION IF EXISTS `get_waktu_tunggu_spv3`;
delimiter ;;
CREATE FUNCTION `get_waktu_tunggu_spv3`(`var_loan_app_no` VARCHAR(30))
 RETURNS varchar(30) CHARSET latin1
BEGIN
        DECLARE selisih_waktu VARCHAR(30);
    DECLARE selisih_detik INT;

    -- Hitung selisih waktu dalam detik
    SELECT
        	CASE
							WHEN (lp.created_at> df.date_flag_spv2) THEN 
								TIME_TO_SEC(TIMEDIFF(lp.created_at, df.date_flag_spv2))
							ELSE
							0
						END as selisih
    INTO selisih_detik
    FROM
        data_file df
    JOIN
        list_pickup lp
    ON
        df.loan_app_no = lp.loan_app_no
    WHERE
        df.loan_app_no = var_loan_app_no
        AND (lp.spv_lvl = 'spv3' OR lp.spv_lvl = 'spv4')
    ORDER BY lp.created_at DESC
    LIMIT 1;
		
		if selisih_detik IS NULL THEN 
			 SET selisih_detik = 0;
		END IF;


    -- Periksa apakah selisih_detik negatif
    IF selisih_detik > 302399 THEN
        SET selisih_detik = 302399;
    END IF;

    -- Konversi detik ke format hh:mm:ss
    SET selisih_waktu = SEC_TO_TIME(selisih_detik);

    -- Kembalikan hasil
    RETURN selisih_waktu;
    END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for insert_pickup
-- ----------------------------
DROP PROCEDURE IF EXISTS `insert_pickup`;
delimiter ;;
CREATE PROCEDURE `insert_pickup`(IN user_id varchar(30), IN spv_lvl varchar(30), OUT loan_no_result varchar(30))
BEGIN
    DECLARE loan_app_no_var varchar(30);
    DECLARE user_id_var varchar(30);
    DECLARE spv_lvl_var varchar(30);
    DECLARE status_var INT;
    DECLARE created_at_var datetime;
    DECLARE ai_enabled INT;

    -- Declare cursor to fetch loan_no
    DECLARE cur CURSOR FOR
        SELECT loan_app_no FROM list_pickup WHERE user_spv = user_id AND status = 0 ORDER BY RAND() LIMIT 1 FOR UPDATE;
    -- Declare handler for NOT FOUND condition
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET loan_no_result = NULL;

    -- Get the AI setting
    SELECT enable INTO ai_enabled FROM setting_ai LIMIT 1;

    START TRANSACTION;
    -- Fetch loan_no from cursor
    OPEN cur;
    FETCH cur INTO loan_app_no_var;
    CLOSE cur; -- Close cursor after fetching

    IF loan_app_no_var IS NULL THEN
        IF spv_lvl = 'spv2' THEN
            -- Fetch data_file columns into variables for insertion
            SELECT df.loan_app_no, user_id, spv_lvl, 0, now()
            INTO loan_app_no_var, user_id_var, spv_lvl_var, status_var, created_at_var
            FROM data_file df
            INNER JOIN branchlist AS br ON df.kode_cabang = br.branch_code 
            WHERE (df.final_status_spv1 = 1 OR df.final_status_spv1 = 3) AND df.final_status_spv2 = 0
                AND df.loan_app_no NOT IN (SELECT loan_app_no FROM list_pickup WHERE status = 0)
                AND (
                    -- Data hari ini dan kemarin
                    (DATE(df.date_input) IN (CURDATE(), CURDATE() - INTERVAL 1 DAY))
                    OR 
                    -- Data Jumat jika hari ini Senin
                    (
                        DAYOFWEEK(NOW()) = 2 -- Hari Senin
                        AND DATE(df.date_input) = DATE(SUBDATE(NOW(), 3)) -- Jumat
                        AND DATE(df.date_flag_spv1) = CURDATE()
                    )
                    AND df.final_status <> 4
                )
                AND (
                    ai_enabled = 0
                    OR (ai_enabled = 1 AND EXISTS (
                        SELECT 1 FROM detail_file dtf
                        WHERE dtf.loan_app_no = df.loan_app_no
                        AND (dtf.score IS NOT NULL OR TIMESTAMPDIFF(MINUTE,df.date_flag_spv1, NOW()) > 5)
                    ))
                )
            ORDER BY
            df.take_over DESC,
            FIELD(modul, 'KPH','KPKB-WNI', 'KPKB-WNA') DESC, 
            IF(FIELD(modul, 'KPKB-WNI', 'KPKB-WNA', 'KPH') = 0, date_flag_spv1, NULL),
            date_flag_spv1 ASC LIMIT 1;
        ELSEIF spv_lvl = 'spv3' OR spv_lvl = 'spv4' THEN
            -- Fetch data_file columns into variables for insertion
            SELECT df.loan_app_no, user_id, spv_lvl, 0, now()
            INTO loan_app_no_var, user_id_var, spv_lvl_var, status_var, created_at_var
            FROM data_file df
            INNER JOIN branchlist AS br ON df.kode_cabang = br.branch_code 
            WHERE (df.final_status_spv2 = 1 OR df.final_status_spv2 = 3) AND df.final_status_spv3 = 0
                AND df.loan_app_no NOT IN (SELECT loan_app_no FROM list_pickup WHERE status = 0)
                AND (
                    -- Data hari ini dan kemarin
                    (DATE(df.date_input) IN (CURDATE(), CURDATE() - INTERVAL 1 DAY))
                    OR 
                    -- Data Jumat jika hari ini Senin
                    (
                        DAYOFWEEK(NOW()) = 2 -- Hari Senin
                        AND DATE(df.date_input) = DATE(SUBDATE(NOW(), 3)) -- Jumat
                        AND DATE(df.date_flag_spv2) = CURDATE()
                    )
                    AND df.final_status <> 4
                )
                AND (
                    ai_enabled = 0
                    OR (ai_enabled = 1 AND EXISTS (
                        SELECT 1 FROM detail_file dtf
                        WHERE dtf.loan_app_no = df.loan_app_no
                        AND (dtf.score IS NOT NULL OR TIMESTAMPDIFF(MINUTE, df.date_flag_spv1, NOW()) > 5)
                    ))
                )
            ORDER BY
            df.take_over DESC,
            FIELD(modul, 'KPH','KPKB-WNI', 'KPKB-WNA') DESC, 
            IF(FIELD(modul, 'KPKB-WNI', 'KPKB-WNA', 'KPH') = 0, date_flag_spv2, NULL),
            date_flag_spv2 ASC LIMIT 1;
        END IF;
        -- Insert data into list_pickup table
        INSERT INTO list_pickup (loan_app_no, user_spv, spv_lvl, status, created_at)
        VALUES (loan_app_no_var, user_id_var, spv_lvl_var, status_var, created_at_var);
				
        UPDATE registration SET processed = 1 WHERE loan_app_no = loan_app_no_var AND processed = 0;
        SET loan_no_result = loan_app_no_var;
    ELSE
        SET loan_no_result = loan_app_no_var;
    END IF;
    COMMIT;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for insert_pickup_copy1
-- ----------------------------
DROP FUNCTION IF EXISTS `insert_pickup_copy1`;
delimiter ;;
CREATE FUNCTION `insert_pickup_copy1`(user_id varchar(30), spv_lvl varchar(30))
 RETURNS varchar(30) CHARSET latin1
BEGIN
   DECLARE loan_no varchar(30);

    SET loan_no = (SELECT loan_app_no FROM list_pickup WHERE user_spv = user_id AND status = 0 LIMIT 1);

    IF loan_no IS NULL THEN
        IF spv_lvl = 'spv2' THEN
            INSERT INTO list_pickup (loan_app_no, user_spv, spv_lvl, status, created_at)
            SELECT df.loan_app_no, user_id, spv_lvl, 0, NOW()
            FROM data_file df
            WHERE (df.final_status_spv1 = 1 or df.final_status_spv1 = 3) AND df.final_status_spv2 = 0
                AND df.loan_app_no NOT IN (SELECT loan_app_no FROM list_pickup WHERE status = 0)
            ORDER BY df.date_input LIMIT 1;
        ELSEIF spv_lvl = 'spv3' OR spv_lvl = 'spv4' THEN
            INSERT INTO list_pickup (loan_app_no, user_spv, spv_lvl, status, created_at)
            SELECT df.loan_app_no, user_id, spv_lvl, 0, NOW()
            FROM data_file df
            WHERE (df.final_status_spv2 = 1 or df.final_status_spv2 = 3) AND df.final_status_spv3 = 0
                AND df.loan_app_no NOT IN (SELECT loan_app_no FROM list_pickup WHERE status = 0)
            ORDER BY df.date_input LIMIT 1;
        END IF;

        SET loan_no = (SELECT loan_app_no FROM list_pickup WHERE user_spv = user_id AND status = 0 LIMIT 1);
    END IF;

    RETURN loan_no;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for insert_pickup_new
-- ----------------------------
DROP PROCEDURE IF EXISTS `insert_pickup_new`;
delimiter ;;
CREATE PROCEDURE `insert_pickup_new`(IN user_id varchar(30), IN spv_lvl varchar(30), OUT loan_no_result varchar(30))
BEGIN
    DECLARE loan_app_no_var varchar(30);
    DECLARE user_id_var varchar(30);
    DECLARE spv_lvl_var varchar(30);
    DECLARE status_var INT;
    DECLARE created_at_var datetime;
    DECLARE ai_enabled INT;

    -- Declare cursor to fetch loan_no
    DECLARE cur CURSOR FOR
        SELECT loan_app_no FROM list_pickup WHERE user_spv = user_id AND status = 0 ORDER BY RAND() LIMIT 1 FOR UPDATE;
    -- Declare handler for NOT FOUND condition
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET loan_no_result = NULL;

    -- Get the AI setting
    SELECT enable INTO ai_enabled FROM setting_ai LIMIT 1;

    START TRANSACTION;
    -- Fetch loan_no from cursor
    OPEN cur;
    FETCH cur INTO loan_app_no_var;
    CLOSE cur; -- Close cursor after fetching

    IF loan_app_no_var IS NULL THEN
        IF spv_lvl = 'spv2' OR spv_lvl = 'spv3' OR spv_lvl = 'spv4' THEN
            -- Fetch data_file columns into variables for insertion
            SELECT df.loan_app_no, user_id, spv_lvl, 0, now()
            INTO loan_app_no_var, user_id_var, spv_lvl_var, status_var, created_at_var
            FROM data_file df
            INNER JOIN branchlist AS br ON df.kode_cabang = br.branch_code 
            WHERE (df.final_status_spv1 = 1 OR df.final_status_spv1 = 3) AND df.final_status_spv2 = 0
                AND df.loan_app_no NOT IN (SELECT loan_app_no FROM list_pickup WHERE status = 0)
                AND (
                    -- Data hari ini dan kemarin
                    (DATE(df.date_input) IN (CURDATE(), CURDATE() - INTERVAL 1 DAY))
                    OR 
                    -- Data Jumat jika hari ini Senin
                    (
                        DAYOFWEEK(NOW()) = 2 -- Hari Senin
                        AND DATE(df.date_input) = DATE(SUBDATE(NOW(), 3)) -- Jumat
                        AND DATE(df.date_flag_spv1) = CURDATE()
                    )
                    AND df.final_status <> 4
                )
                AND (
                    ai_enabled = 0
                    OR (ai_enabled = 1 AND EXISTS (
                        SELECT 1 FROM detail_file dtf
                        WHERE dtf.loan_app_no = df.loan_app_no
                        AND (dtf.score IS NOT NULL OR TIMESTAMPDIFF(MINUTE,df.date_flag_spv1, NOW()) > 5)
                    ))
                )
            ORDER BY
            -- df.take_over_hari_ini DESC, -- ganti order kupen kupeg
            FIELD(modul, 'KPH','KPKB-WNI', 'KPKB-WNA') DESC, 
            IF(FIELD(modul, 'KPKB-WNI', 'KPKB-WNA', 'KPH') = 0, date_flag_spv1, NULL),
            date_flag_spv1 ASC LIMIT 1;
        END IF;
        -- Insert data into list_pickup table
        INSERT INTO list_pickup (loan_app_no, user_spv, spv_lvl, status, created_at)
        VALUES (loan_app_no_var, user_id_var, spv_lvl_var, status_var, created_at_var);
				
        UPDATE registration SET processed = 1 WHERE loan_app_no = loan_app_no_var AND processed = 0;
        SET loan_no_result = loan_app_no_var;
    ELSE
        SET loan_no_result = loan_app_no_var;
    END IF;
    COMMIT;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for insert_pickup_tbo
-- ----------------------------
DROP FUNCTION IF EXISTS `insert_pickup_tbo`;
delimiter ;;
CREATE FUNCTION `insert_pickup_tbo`(user_id varchar(30), spv_lvl varchar(30))
 RETURNS varchar(30) CHARSET latin1
BEGIN
   DECLARE loan_no varchar(30);

    SET loan_no = (SELECT loan_app_no FROM list_pickup WHERE user_spv = user_id AND status = 0 LIMIT 1);

    IF loan_no IS NULL THEN
        IF spv_lvl = 'spv2' THEN
            INSERT INTO list_pickup (loan_app_no, user_spv, spv_lvl, status, created_at)
            SELECT df.loan_app_no, user_id, spv_lvl, 0, NOW()
            FROM data_file df
            WHERE df.final_status_spv1 = 3 AND df.final_status_spv2 = 0
                AND df.loan_app_no NOT IN (SELECT loan_app_no FROM list_pickup WHERE status = 0)
            ORDER BY df.date_input LIMIT 1;
        ELSEIF spv_lvl = 'spv3' OR spv_lvl = 'spv4' THEN
            INSERT INTO list_pickup (loan_app_no, user_spv, spv_lvl, status, created_at)
            SELECT df.loan_app_no, user_id, spv_lvl, 0, NOW()
            FROM data_file df
            WHERE df.final_status_spv2 = 3 AND df.final_status_spv3 = 0
                AND df.loan_app_no NOT IN (SELECT loan_app_no FROM list_pickup WHERE status = 0)
            ORDER BY df.date_input LIMIT 1;
        END IF;

        SET loan_no = (SELECT loan_app_no FROM list_pickup WHERE user_spv = user_id AND status = 0 LIMIT 1);
    END IF;

    RETURN loan_no;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for insert_registration
-- ----------------------------
DROP PROCEDURE IF EXISTS `insert_registration`;
delimiter ;;
CREATE PROCEDURE `insert_registration`(IN `p_loan_app_no` varchar(30))
BEGIN
	  DECLARE v_last_date DATE;
    DECLARE v_new_register_num INT;
    DECLARE v_no_register VARCHAR(30);
    
    -- Mengambil tanggal terakhir dari no_register
    SELECT SUBSTRING_INDEX(no_register, '-', 1) INTO v_last_date
    FROM registration
    ORDER BY id DESC
    LIMIT 1;
    
    -- Jika tanggal terakhir tidak sama dengan tanggal hari ini, reset nomor urut menjadi 1
    IF v_last_date != CURDATE() THEN
        SET v_new_register_num = 1;
    ELSE
        -- Mengambil nomor terakhir dari no_register
        SELECT SUBSTRING_INDEX(no_register, '-', -1) INTO v_new_register_num
        FROM registration
        ORDER BY id DESC
        LIMIT 1;
        SET v_new_register_num = v_new_register_num + 1;
    END IF;
    
    -- Membuat string no_register dengan format yyyymmdd-nomor_urut
    SET v_no_register = CONCAT(DATE_FORMAT(NOW(), '%Y%m%d'), '-', v_new_register_num);
    
    -- Memasukkan data baru ke tabel registration
    INSERT INTO registration (loan_app_no, no_register, created_at)
    VALUES (p_loan_app_no, v_no_register, now());
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for p_master_data
-- ----------------------------
DROP PROCEDURE IF EXISTS `p_master_data`;
delimiter ;;
CREATE PROCEDURE `p_master_data`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	`data_file`.`loan_app_no` AS `loan_app_no`,
	`data_file`.`kode_cabang` AS `kode_cabang`,
	`data_file`.`nama_debitur` AS `nama_debitur`,(
	CASE
			
			WHEN ( `comment`.`level_spv` = 'spv1' ) THEN
			'SPV Branch' 
			WHEN ( `comment`.`level_spv` = 'spv2' ) THEN
			'BPR1' 
			WHEN ( `comment`.`level_spv` = 'spv3'  || `comment`.`level_spv` = 'spv4') THEN
			'BPR2' 
		END 
		) AS `level_spv`,
		`comment`.`user_name` AS `user_name`,
		`users`.`name` AS `name`,(
		SELECT
			`c`.`flag_spv` 
		FROM
			`comment` `c` 
		WHERE
			((
					`c`.`loan_app_no` = `comment`.`loan_app_no` 
					) 
				AND (
				`c`.`comment_date` = max( `comment`.`comment_date` ))) 
		GROUP BY
			`data_file`.`loan_app_no`,
			`comment`.`level_spv` 
			) AS `flag_spv1`,(
		CASE
				
				WHEN ((
					SELECT
						`c`.`flag_spv` 
					FROM
						`comment` `c` 
					WHERE
						((
								`c`.`loan_app_no` = `comment`.`loan_app_no` 
								) 
							AND (
							`c`.`comment_date` = max( `comment`.`comment_date` ))) 
					GROUP BY
						`data_file`.`loan_app_no`,
						`comment`.`level_spv` 
						) = '0' 
					) THEN
					'Pending' 
					WHEN ((
						SELECT
							`c`.`flag_spv` 
						FROM
							`comment` `c` 
						WHERE
							((
									`c`.`loan_app_no` = `comment`.`loan_app_no` 
									) 
								AND (
								`c`.`comment_date` = max( `comment`.`comment_date` ))) 
						GROUP BY
							`data_file`.`loan_app_no`,
							`comment`.`level_spv` 
							) = '1' 
						) THEN
						'Verify' 
						WHEN ((
							SELECT
								`c`.`flag_spv` 
							FROM
								`comment` `c` 
							WHERE
								((
										`c`.`loan_app_no` = `comment`.`loan_app_no` 
										) 
									AND (
									`c`.`comment_date` = max( `comment`.`comment_date` ))) 
							GROUP BY
								`data_file`.`loan_app_no`,
								`comment`.`level_spv` 
								) = '2' 
							) THEN
							'Not Verify' 
							WHEN ((
								SELECT
									`c`.`flag_spv` 
								FROM
									`comment` `c` 
								WHERE
									((
											`c`.`loan_app_no` = `comment`.`loan_app_no` 
											) 
										AND (
										`c`.`comment_date` = max( `comment`.`comment_date` ))) 
								GROUP BY
									`data_file`.`loan_app_no`,
									`comment`.`level_spv` 
									) = '3' 
								) THEN
								'TBO' 
								WHEN ((
									SELECT
										`c`.`flag_spv` 
									FROM
										`comment` `c` 
									WHERE
										((
												`c`.`loan_app_no` = `comment`.`loan_app_no` 
												) 
											AND (
											`c`.`comment_date` = max( `comment`.`comment_date` ))) 
									GROUP BY
										`data_file`.`loan_app_no`,
										`comment`.`level_spv` 
										) = '4' 
									) THEN
									'Reject' 
								END 
								) AS `flag_spv_desc`,
								`data_file`.`date_input` AS `date_input`,
								max( `comment`.`comment_date` ) AS `review_date` ,
								CASE
									WHEN ( `comment`.`level_spv` = 'spv1' ) THEN
											case 
											when TIME_TO_SEC(timediff(max( `comment`.`comment_date` ),`data_file`.`date_input`)) > 0 then timediff(max( `comment`.`comment_date` ),`data_file`.`date_input`) 											else '00:00:00'
											end
									WHEN ( `comment`.`level_spv` = 'spv2' ) THEN
										  case 
											when TIME_TO_SEC(timediff(max( `comment`.`comment_date` ),(select 	max( `comment`.`comment_date` ) from comment where level_spv='spv1' and comment.loan_app_no = data_file.`loan_app_no` GROUP BY `comment`.loan_app_no  ))) > 0 THEN
											timediff(max( `comment`.`comment_date` ),(select 	max( `comment`.`comment_date` ) from comment where level_spv='spv1' and comment.loan_app_no = data_file.`loan_app_no` GROUP BY `comment`.loan_app_no  ))
											else '00:00:00'
											end
									WHEN ( `comment`.`level_spv` = 'spv3' || `comment`.`level_spv` = 'spv4') THEN
										  case 
											when TIME_TO_SEC(timediff(max( `comment`.`comment_date` ),(select 	max( `comment`.`comment_date` ) from comment where level_spv='spv2' and comment.loan_app_no = data_file.`loan_app_no` GROUP BY `comment`.loan_app_no  ))) > 0 THEN
											timediff(max( `comment`.`comment_date` ),(select 	max( `comment`.`comment_date` ) from comment where level_spv='spv2' and comment.loan_app_no = data_file.`loan_app_no` GROUP BY `comment`.loan_app_no  ))
											ELSE '00:00:00'
											end
								END as waktu
							
							FROM
								((
										`data_file`
										JOIN `comment` ON ((
												`data_file`.`loan_app_no` = `comment`.`loan_app_no` 
											)))
									LEFT JOIN `users` ON ((
											`comment`.`user_name` = `users`.`nik` 
										))) 
							WHERE
								((
										cast( `data_file`.`date_input` AS date ) BETWEEN dari
										AND sampai
										) 
								AND ( `data_file`.`final_status` <> 4 )) 
							GROUP BY
								`data_file`.`loan_app_no`,
								`comment`.`level_spv` 
							ORDER BY
							`data_file`.`date_input`,
	`comment`.`comment_date`;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for reject_perpanjangan_waktu
-- ----------------------------
DROP PROCEDURE IF EXISTS `reject_perpanjangan_waktu`;
delimiter ;;
CREATE PROCEDURE `reject_perpanjangan_waktu`()
BEGIN
	#Routine body goes here...

DECLARE var_waktu time;
SELECT waktu INTO var_waktu FROM setting_time LIMIT 1;
 IF CURTIME() > var_waktu THEN
update perubahan_jam_layanan set user_spv='system', flag_spv = 2 , date_flag_spv = now(), note_spv=CONCAT('Reject Otomatis By System Request Perpanjangan Jam Layanan maksimal Pukul ',var_waktu),
 user_spv1='system', flag_spv1 = 2 , date_flag_spv1 = now(), note_spv1=CONCAT('Reject Otomatis By System Request Perpanjangan Jam Layanan maksimal Pukul ',var_waktu), final_flag=2
 where flag_spv = 0;
 END IF;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for reset_cut_off
-- ----------------------------
DROP PROCEDURE IF EXISTS `reset_cut_off`;
delimiter ;;
CREATE PROCEDURE `reset_cut_off`()
BEGIN
	#Routine body goes here...
update cut_off set cut_off='16:30:00',purpose_cut_off='16:30:00', approve_flag=1;
update cut_off_hq set cut_off='16:30:00',purpose_cut_off='16:30:00', approve_flag=1;
-- update setting_time set waktu='16:30:00';
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for reviewer_bpr1
-- ----------------------------
DROP PROCEDURE IF EXISTS `reviewer_bpr1`;
delimiter ;;
CREATE PROCEDURE `reviewer_bpr1`(IN `stat` int,IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	user_spv2 as nik, 
	`users`.`name`,
	count(1) AS jml
FROM
	data_file
	left JOIN
	`users`
	ON 
		data_file.user_spv2 = `users`.nik
WHERE
	final_status_spv2 = stat AND
	date(date_input) BETWEEN dari AND sampai
GROUP BY
	user_spv2
ORDER BY
	jml DESC;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for reviewer_bpr2
-- ----------------------------
DROP PROCEDURE IF EXISTS `reviewer_bpr2`;
delimiter ;;
CREATE PROCEDURE `reviewer_bpr2`(IN `stat` int,IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	user_spv3, 
	`users`.`name`,
	count(1) AS jml
FROM
	data_file
	left JOIN
	`users`
	ON 
		data_file.user_spv3 = `users`.nik
WHERE
	final_status_spv3 = stat AND
	date(date_input) BETWEEN dari AND sampai
GROUP BY
	user_spv3
ORDER BY
	jml DESC;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for SEC_TO_TIMEB
-- ----------------------------
DROP FUNCTION IF EXISTS `SEC_TO_TIMEB`;
delimiter ;;
CREATE FUNCTION `SEC_TO_TIMEB`(in_seconds bigint)
 RETURNS varchar(15) CHARSET latin1
BEGIN                                                                                     
    DECLARE hours VARCHAR(9);                                                 
    DECLARE minutes CHAR(2);                                               
    DECLARE seconds CHAR(2);                                               

    SET hours   := FLOOR(in_seconds / 3600);                                              
    SET hours   := IF(hours < 10,CONCAT('0',hours),hours);                                

    SET minutes := FLOOR(MOD(in_seconds,3600) / 60);                                      
    SET minutes := IF(minutes < 10,CONCAT('0',minutes),minutes);                          

    SET seconds := MOD(MOD(in_seconds,3600),60);                                          
    SET seconds := IF(seconds < 10,CONCAT('0',seconds),seconds);

    RETURN CONCAT( hours, ':', minutes, ':', LPAD(MOD(seconds,60),2, '0'));                                        
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for sla_bpr1
-- ----------------------------
DROP PROCEDURE IF EXISTS `sla_bpr1`;
delimiter ;;
CREATE PROCEDURE `sla_bpr1`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	users.`name` as nik, 
	count(tmp_resume_perhari.loan_app_no) as total_doc , 
	BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(tmp_resume_perhari.bpr1)))) as sla,
	BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(tmp_resume_perhari.bpr1))/count(tmp_resume_perhari.loan_app_no ))) as average
FROM
	tmp_resume_perhari
	INNER JOIN
	users
	ON 
		tmp_resume_perhari.user_spv2 = users.nik
WHERE
	date( date_input ) >= dari
	AND date( date_input ) <= sampai
	AND final_status is not null
	GROUP BY tmp_resume_perhari.user_spv2
ORDER BY
	total_doc desc;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for sla_bpr2
-- ----------------------------
DROP PROCEDURE IF EXISTS `sla_bpr2`;
delimiter ;;
CREATE PROCEDURE `sla_bpr2`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	users.`name` as nik, 
	count(tmp_resume_perhari.loan_app_no) as total_doc , 
	BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(tmp_resume_perhari.bpr2)))) as sla,
	BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(tmp_resume_perhari.bpr2))/count(tmp_resume_perhari.loan_app_no ))) as average
FROM
	tmp_resume_perhari
	INNER JOIN
	users
	ON 
		tmp_resume_perhari.user_spv3 = users.nik
WHERE
	date( date_input ) >= dari
	AND date( date_input ) <= sampai
	AND final_status is not null
	GROUP BY tmp_resume_perhari.user_spv3
ORDER BY
	total_doc desc;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for sla_bpr2_detail
-- ----------------------------
DROP PROCEDURE IF EXISTS `sla_bpr2_detail`;
delimiter ;;
CREATE PROCEDURE `sla_bpr2_detail`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	users.`name` as nik, 
	count(tmp_resume_perhari.loan_app_no) as total_doc , 
	BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(tmp_resume_perhari.bpr2)))) as sla,
	BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(tmp_resume_perhari.bpr2))/count(tmp_resume_perhari.loan_app_no ))) as average
FROM
	tmp_resume_perhari
	INNER JOIN
	users
	ON 
		tmp_resume_perhari.user_spv3 = users.nik
WHERE
	date( date_input ) >= dari
	AND date( date_input ) <= sampai
	AND final_status is not null
	GROUP BY tmp_resume_perhari.user_spv3
ORDER BY
	total_doc desc;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for sla_dcrm
-- ----------------------------
DROP PROCEDURE IF EXISTS `sla_dcrm`;
delimiter ;;
CREATE PROCEDURE `sla_dcrm`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	users.`name` as nik, 
	count(temp_resume_perhari.loan_app_no) as total_doc , 
	BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(temp_resume_perhari.dcrm)))) as sla,
	BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(temp_resume_perhari.dcrm))/count(temp_resume_perhari.loan_app_no ))) as average
FROM
	temp_resume_perhari
	INNER JOIN
	users
	ON 
		temp_resume_perhari.user_spv2 = users.nik
WHERE
	date( date_input ) >= dari
	AND date( date_input ) <= sampai
	AND final_status is not null
	GROUP BY temp_resume_perhari.user_spv2
ORDER BY
	total_doc desc;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for sla_pickup
-- ----------------------------
DROP PROCEDURE IF EXISTS `sla_pickup`;
delimiter ;;
CREATE PROCEDURE `sla_pickup`(IN `tanggal_dari` date,IN `tanggal_sampai` date)
BEGIN
	SELECT
	users.`name`,
	COUNT(loan_app_no) AS total_loan_applications, 
	SEC_TO_TIME(FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(list_pickup.updated_at, list_pickup.created_at))))) AS average_processing_time, 
	SEC_TO_TIME(MIN(TIME_TO_SEC(TIMEDIFF(list_pickup.updated_at, list_pickup.created_at)))) AS min_processing_time, 
	SEC_TO_TIME(MAX(TIME_TO_SEC(TIMEDIFF(list_pickup.updated_at, list_pickup.created_at)))) AS max_processing_time
	
FROM
	list_pickup
	INNER JOIN
	users
	ON 
		list_pickup.user_spv = users.nik
WHERE
	`status` = 1 AND
	date(list_pickup.created_at) >= tanggal_dari AND
	date(list_pickup.created_at) <= tanggal_sampai
GROUP BY
	list_pickup.user_spv
ORDER BY
	total_loan_applications DESC, 
	average_processing_time ASC;
		

	
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for stacked_bpr1
-- ----------------------------
DROP PROCEDURE IF EXISTS `stacked_bpr1`;
delimiter ;;
CREATE PROCEDURE `stacked_bpr1`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	-- concat(user_spv2,' - ',`users`.`name`) as name,
	`users`.`name` as name,
	count(IF( final_status_spv2 =1, final_status_spv2, NULL )) as verify,
	count(IF( final_status_spv2 =2, final_status_spv2, NULL )) as not_verify,
	count(IF( final_status_spv2 =3, final_status_spv2, NULL )) as tbo,
	count(IF( final_status_spv2 =4, final_status_spv2, NULL )) as reject,
	count(final_status_spv2) as total
FROM
	data_file
	left JOIN
	`users`
	ON 
		data_file.user_spv2 = `users`.nik
WHERE
	date(date_input) BETWEEN dari AND sampai
	and user_spv2 is not null
GROUP BY
	user_spv2
order by total desc;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for stacked_bpr1_history
-- ----------------------------
DROP PROCEDURE IF EXISTS `stacked_bpr1_history`;
delimiter ;;
CREATE PROCEDURE `stacked_bpr1_history`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	`users`.`name` as name,
	count(IF( `comment`.flag_spv =1, `comment`.flag_spv, NULL )) as verify,
	count(IF( `comment`.flag_spv =2, `comment`.flag_spv, NULL )) as not_verify,
	count(IF( `comment`.flag_spv =3, `comment`.flag_spv, NULL )) as tbo,
	count(IF( `comment`.flag_spv =4, `comment`.flag_spv, NULL )) as reject,
	count(`comment`.flag_spv) as total
FROM
	`comment`
	INNER JOIN users ON `comment`.user_name = users.nik 
	INNER JOIN
	data_file
	ON 
		`comment`.loan_app_no = data_file.loan_app_no
WHERE
	date( comment_date ) BETWEEN dari
	AND sampai
	and `comment`.level_spv = 'spv2'
	and date(data_file.date_input) NOT BETWEEN dari AND sampai
GROUP BY
	user_name
order by total desc;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for stacked_bpr2
-- ----------------------------
DROP PROCEDURE IF EXISTS `stacked_bpr2`;
delimiter ;;
CREATE PROCEDURE `stacked_bpr2`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	-- concat(user_spv3,' - ',`users`.`name`) as name,
	`users`.`name` as name,
	count(IF( final_status_spv3 =1, final_status_spv3, NULL )) as verify,
	count(IF( final_status_spv3 =2, final_status_spv3, NULL )) as not_verify,
	count(IF( final_status_spv3 =3, final_status_spv3, NULL )) as tbo,
	count(IF( final_status_spv3 =4, final_status_spv3, NULL )) as reject,
	count(final_status_spv3) as total
FROM
	data_file
	left JOIN
	`users`
	ON 
		data_file.user_spv3 = `users`.nik
WHERE
	date(date_input) BETWEEN dari AND sampai
	and final_status_spv3<>0
GROUP BY
	user_spv3
order by total desc;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for stacked_bpr2_history
-- ----------------------------
DROP PROCEDURE IF EXISTS `stacked_bpr2_history`;
delimiter ;;
CREATE PROCEDURE `stacked_bpr2_history`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	`users`.`name` as name,
	count(IF( `comment`.flag_spv =1, `comment`.flag_spv, NULL )) as verify,
	count(IF( `comment`.flag_spv =2, `comment`.flag_spv, NULL )) as not_verify,
	count(IF( `comment`.flag_spv =3, `comment`.flag_spv, NULL )) as tbo,
	count(IF( `comment`.flag_spv =4, `comment`.flag_spv, NULL )) as reject,
	count(`comment`.flag_spv) as total
FROM
	`comment`
	INNER JOIN users ON `comment`.user_name = users.nik 
	INNER JOIN
	data_file
	ON 
		`comment`.loan_app_no = data_file.loan_app_no
WHERE
	date( comment_date ) BETWEEN dari
	AND sampai
	and (`comment`.level_spv = 'spv3' or `comment`.level_spv = 'spv4')
	and date(data_file.date_input) NOT BETWEEN dari AND sampai
GROUP BY
	user_name
order by total desc;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for staging_resume
-- ----------------------------
DROP PROCEDURE IF EXISTS `staging_resume`;
delimiter ;;
CREATE PROCEDURE `staging_resume`(IN `dari` date, IN `sampai` date)
BEGIN
    # Routine body goes here...
    DROP TEMPORARY TABLE IF EXISTS temp_resume_perhari;
    DROP TEMPORARY TABLE IF EXISTS temp_sla_agregate;
    DROP TEMPORARY TABLE IF EXISTS temp_aggregated_data;
    DROP TEMPORARY TABLE IF EXISTS temp_waktu_tercepat;

    CREATE TEMPORARY TABLE IF NOT EXISTS temp_resume_perhari LIKE tmp_resume_perhari;
    CREATE TEMPORARY TABLE IF NOT EXISTS temp_sla_agregate LIKE tmp_sla_agregate;

insert into `temp_resume_perhari` (kode_cabang,
branch_name,
loan_app_no,
nama_debitur,
final_status,
date_input,
user_spv1,
user_spv2,
user_spv3,
`<10`,
`<12`,
`<13`,
`<15`,
`<17`,
`<19`,
`>19`,
frequensi,
waktu_frequensi,
pickup_bpr1,
bpr1,
pickup_bpr2,
bpr2,
total_waktu)
		SELECT
	a.kode_cabang, 
	branchlist.branch_name,a.loan_app_no,a.nama_debitur,f_get_final_status(a.loan_app_no) as final_status,a.date_input,user_spv1,user_spv2,user_spv3,	
		if(time(a.date_input) <= '10:00:00',  f_get_comment_date_spv1(a.loan_app_no) ,NULL) as '<10',
		if(time(a.date_input) > '10:00:00' and time(a.date_input) <= '12:00:00',f_get_comment_date_spv1(a.loan_app_no),NULL) as '<12',
		if(time(a.date_input) > '12:00:00' and time(a.date_input) <= '13:00:00',f_get_comment_date_spv1(a.loan_app_no),NULL) as '<13',
		if(time(a.date_input) > '13:00:00' and time(a.date_input) <= '15:00:00', f_get_comment_date_spv1(a.loan_app_no),NULL) as '<15',
		if(time(a.date_input) > '15:00:00' and time(a.date_input) <= '17:00:00', f_get_comment_date_spv1(a.loan_app_no),NULL) as '<17',
		if(time(a.date_input) > '17:00:00' and time(a.date_input) <= '19:00:00', f_get_comment_date_spv1(a.loan_app_no),NULL) as '<19',
	  if(time(a.date_input) > '19:00:00', f_get_comment_date_spv1(a.loan_app_no),NULL) as '>19',
	(select count(1) from comment b where b.loan_app_no = a.loan_app_no and date(b.comment_date) = date(a.date_input) ) AS frequensi, 
	CASE
		WHEN a.final_status = 2 OR a.final_status = 4 THEN
			case 
				when f_get_bpr_time_today(a.loan_app_no, 'pcp') is null then
					BIG_SEC_TO_TIME(
					BIG_TIME_TO_SEC(f_get_waktu_freq(a.loan_app_no)) + 
					BIG_TIME_TO_SEC(f_get_bpr_time(f_get_bpr_time_today(a.loan_app_no, 'spv1'),f_get_bpr_time_today(a.loan_app_no, 'spv2'))) +
					BIG_TIME_TO_SEC(f_get_bpr_time(f_get_bpr_time_today(a.loan_app_no, 'spv2'),f_get_bpr_time_today(a.loan_app_no, 'spv3'))) 
				)
				else
					BIG_SEC_TO_TIME(
					BIG_TIME_TO_SEC(f_get_waktu_freq(a.loan_app_no)) + 
					BIG_TIME_TO_SEC(f_get_bpr_time(f_get_bpr_time_today(a.loan_app_no, 'pcp'),f_get_bpr_time_today(a.loan_app_no, 'spv2'))) +
					BIG_TIME_TO_SEC(f_get_bpr_time(f_get_bpr_time_today(a.loan_app_no, 'spv2'),f_get_bpr_time_today(a.loan_app_no, 'spv3'))) 
				)
			end 
		
		ELSE
		f_get_waktu_freq(a.loan_app_no)
	END as waktu_frequensi,
	-- f_get_waktu_freq(a.loan_app_no) as waktu_frequensi,
	get_waktu_tunggu_spv2(a.loan_app_no) as pickup_bpr1,
	CASE
		WHEN a.final_status = 2 OR a.final_status = 4 THEN
		'00:00:00'
		ELSE
		-- '00:00:00'
	  get_waktu_pengerjaan_spv2(a.loan_app_no)
	END as bpr1,
	-- f_get_bpr_time(f_get_bpr_time_today(a.loan_app_no, 'spv1'),f_get_bpr_time_today(a.loan_app_no, 'spv2')) as bpr1,
	get_waktu_tunggu_spv3(a.loan_app_no) as pickup_bpr2,
	CASE
		WHEN a.final_status = 2 OR a.final_status = 4 THEN
		'00:00:00'
		ELSE
		get_waktu_pengerjaan_spv3(a.loan_app_no)
	END as bpr2,
	-- f_get_bpr_time(f_get_bpr_time_today(a.loan_app_no, 'spv2'),f_get_bpr_time_today(a.loan_app_no, 'spv3')) as bpr2,
	BIG_SEC_TO_TIME(
	BIG_TIME_TO_SEC(f_get_comment_date_spv1(a.loan_app_no))+
	BIG_TIME_TO_SEC(f_get_waktu_freq(a.loan_app_no))+
	BIG_TIME_TO_SEC(get_waktu_tunggu_spv2(a.loan_app_no))+
	BIG_TIME_TO_SEC(get_waktu_pengerjaan_spv2(a.loan_app_no))+
	BIG_TIME_TO_SEC(get_waktu_tunggu_spv3(a.loan_app_no))+
	BIG_TIME_TO_SEC(get_waktu_pengerjaan_spv3(a.loan_app_no))
	) as total_waktu
	FROM
	data_file AS a
	INNER JOIN
	branchlist
	ON 
		a.kode_cabang = branchlist.branch_code
WHERE date(a.date_input) BETWEEN dari and sampai
-- and final_status =1 
-- and (select count(1) from comment b where b.loan_app_no = a.loan_app_no) = 3 -- and a.loan_app_no='ID022100656'
	ORDER BY a.date_input;
	

    # Create aggregated temporary table
    CREATE TEMPORARY TABLE temp_aggregated_data AS
    SELECT 
        date(date_input) as tanggal,
count(`<10`) as kurang_10,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<10`)))) as 'sum_kurang10',
count(`<12`) as kurang_12,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<12`)))) as 'sum_kurang12',
count(`<13`) as kurang_13,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<13`)))) as 'sum_kurang13',
count(`<15`) as kurang_15,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<15`)))) as 'sum_kurang15',
count(`<17`) as kurang_17,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<17`)))) as 'sum_kurang17',
count(`<19`) as kurang_19,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<19`)))) as 'sum_kurang19',
count(`>19`) as lebih_19,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`>19`)))) as 'sum_lebih19',
count(date_input) as total_pengajuan,
BIG_SEC_TO_TIME(
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<10`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<12`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<13`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<15`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<17`)))+
  check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<19`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`>19`)))
) as total_waktu_pengajuan,
sum(frequensi) as frequensi,
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(waktu_frequensi)))) as frequensi_waktu,
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(pickup_bpr1)))) AS waktu_tunggu_bpr1, 
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(bpr1)))) AS waktu_bpr1, 
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(pickup_bpr2)))) AS waktu_tunggu_bpr2, 
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(bpr2)))) AS waktu_bpr2,
BIG_SEC_TO_TIME(
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<10`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<12`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<13`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<15`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<17`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<19`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`>19`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(waktu_frequensi)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(pickup_bpr1)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(bpr1)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(pickup_bpr2)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(bpr2)))
)as total_waktu_keseluruhan,
BIG_SEC_TO_TIME(
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(`<10`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<12`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<13`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<15`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<17`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<19`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`>19`)))
	)/count(date_input)
) as perbatch_ub,
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(waktu_frequensi)))/sum(frequensi)) as pemenuhan_kekurangan_ub,
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(pickup_bpr1)))/count(date_input))  as rata_waktu_tunggu_bpr1,
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(bpr1)))/count(date_input))  as pengerjaan_bpr1,
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(pickup_bpr2)))/count(date_input))  as rata_waktu_tunggu_bpr2,
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(bpr2)))/count(date_input))  as pengerjaan_bpr2,
BIG_SEC_TO_TIME(
	((
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<10`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<12`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<13`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<15`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<17`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<19`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`>19`)))
	)/count(date_input))+
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(waktu_frequensi)))/sum(frequensi))+
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(pickup_bpr1)))/count(date_input))+
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(bpr1)))/count(date_input))+
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(pickup_bpr2)))/count(date_input))+
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(bpr2)))/count(date_input))
) as rata_waktu_perhari,

BIG_SEC_TO_TIME(
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(pickup_bpr1)))/count(date_input))+
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(bpr1)))/count(date_input))+
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(pickup_bpr2)))/count(date_input))+
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(bpr2)))/count(date_input))
) as rata_waktu_perhari_ideal,
BIG_SEC_TO_TIME(
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(bpr1)))/count(date_input))+
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(bpr2)))/count(date_input))
) as rata_waktu_perhari_dcrm,
BIG_SEC_TO_TIME(MAX(BIG_TIME_TO_SEC(total_waktu)))as waktu_terlama
    FROM 
        temp_resume_perhari
    GROUP BY date(date_input);

    # Calculate waktu_tercepat separately
    CREATE TEMPORARY TABLE temp_waktu_tercepat AS
    SELECT 
        date_input,
        BIG_SEC_TO_TIME(MIN(BIG_TIME_TO_SEC(total_waktu))) AS waktu_tercepat
    FROM 
        temp_resume_perhari
    WHERE 
        final_status IN ('Verify', 'TBO')
    GROUP BY date(date_input);

--     # Insert into final temp_sla_agregate table
    INSERT INTO temp_sla_agregate
		(
		tanggal,
		kurang_10,
		sum_kurang10,
		kurang_12,
		sum_kurang12,
		kurang_13,
		sum_kurang13,
		kurang_15,
		sum_kurang15,
		kurang_17,
		sum_kurang17,
		kurang_19,
		sum_kurang19,
		lebih_19,
		sum_lebih19,
		total_pengajuan,
		total_waktu_pengajuan,
		frekuensi,
		frekuensi_waktu,
		waktu_tunggu_bpr1,
		waktu_bpr1,
		waktu_tunggu_bpr2,
		waktu_bpr2,
		total_waktu_keseluruhan,
		perbatch_ub,
		pemenuhan_kekurangan_ub,
		rata_waktu_tunggu_bpr1,
		pengerjaan_bpr1,
		rata_waktu_tunggu_bpr2,
		pengerjaan_bpr2,
		rata_waktu_perhari,
		rata_waktu_perhari_ideal,
		rata_waktu_perhari_dcrm,
		waktu_terlama,
		waktu_tercepat
		)
    SELECT 
        a.tanggal,
        a.kurang_10,
        a.sum_kurang10,
        a.kurang_12,
        a.sum_kurang12,
        a.kurang_13,
        a.sum_kurang13,
        a.kurang_15,
        a.sum_kurang15,
        a.kurang_17,
        a.sum_kurang17,
        a.kurang_19,
        a.sum_kurang19,
				a.lebih_19,
        a.sum_lebih19,
        a.total_pengajuan,
        a.total_waktu_pengajuan,
        a.frequensi,
        a.frequensi_waktu,
        a.waktu_tunggu_bpr1,
        a.waktu_bpr1,
        a.waktu_tunggu_bpr2,
        a.waktu_bpr2,
        a.total_waktu_keseluruhan,
        a.perbatch_ub,
        a.pemenuhan_kekurangan_ub,
        a.rata_waktu_tunggu_bpr1,
        a.pengerjaan_bpr1,
        a.rata_waktu_tunggu_bpr2,
        a.pengerjaan_bpr2,
        a.rata_waktu_perhari,
        a.rata_waktu_perhari_ideal,
        a.rata_waktu_perhari_dcrm,
        a.waktu_terlama,
        -- b.waktu_tercepat
				 (SELECT 
							BIG_SEC_TO_TIME(MIN(BIG_TIME_TO_SEC(total_waktu))) AS waktu_tercepat
					FROM 
							temp_resume_perhari
					WHERE 
							final_status IN ('Verify', 'TBO') and date(date_input) = a.tanggal
					GROUP BY date(date_input)) as waktu_tercepat
   
    FROM 
        temp_aggregated_data a;
--     LEFT JOIN 
--         temp_resume_perhari b
--     ON 
--         a.tanggal = b.date_input;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for staging_resume_dcrm
-- ----------------------------
DROP PROCEDURE IF EXISTS `staging_resume_dcrm`;
delimiter ;;
CREATE PROCEDURE `staging_resume_dcrm`(IN `dari` date, IN `sampai` date)
BEGIN
    # Routine body goes here...
    DROP TEMPORARY TABLE IF EXISTS temp_resume_perhari;
    DROP TEMPORARY TABLE IF EXISTS temp_sla_agregate;
    DROP TEMPORARY TABLE IF EXISTS temp_aggregated_data;
    DROP TEMPORARY TABLE IF EXISTS temp_waktu_tercepat;

    CREATE TEMPORARY TABLE IF NOT EXISTS temp_resume_perhari LIKE tmp_resume_perhari_new;
    CREATE TEMPORARY TABLE IF NOT EXISTS temp_sla_agregate LIKE tmp_sla_agregate_new;

insert into `temp_resume_perhari` (kode_cabang,
branch_name,
loan_app_no,
nama_debitur,
final_status,
date_input,
user_spv1,
user_spv2,
`<10`,
`<12`,
`<13`,
`<15`,
`<17`,
`<19`,
`>19`,
frequensi,
waktu_frequensi,
pickup_dcrm,
dcrm,
total_waktu)
		SELECT
	a.kode_cabang, 
	branchlist.branch_name,a.loan_app_no,a.nama_debitur,
	f_get_final_status(a.loan_app_no) as final_status,
	a.date_input,user_spv1,user_spv2,
   if(time(a.date_input) <= '10:00:00',  f_get_comment_date_spv1(a.loan_app_no) ,NULL) as '<10',
		if(time(a.date_input) > '10:00:00' and time(a.date_input) <= '12:00:00',f_get_comment_date_spv1(a.loan_app_no),NULL) as '<12',
		if(time(a.date_input) > '12:00:00' and time(a.date_input) <= '13:00:00',f_get_comment_date_spv1(a.loan_app_no),NULL) as '<13',
		if(time(a.date_input) > '13:00:00' and time(a.date_input) <= '15:00:00', f_get_comment_date_spv1(a.loan_app_no),NULL) as '<15',
		if(time(a.date_input) > '15:00:00' and time(a.date_input) <= '17:00:00', f_get_comment_date_spv1(a.loan_app_no),NULL) as '<17',
		if(time(a.date_input) > '17:00:00' and time(a.date_input) <= '19:00:00', f_get_comment_date_spv1(a.loan_app_no),NULL) as '<19',
	  if(time(a.date_input) > '19:00:00', f_get_comment_date_spv1(a.loan_app_no),NULL) as '>19',
	(select count(1) from comment b where b.loan_app_no = a.loan_app_no and date(b.comment_date) = date(a.date_input) ) AS frequensi, 
	CASE
		WHEN a.final_status = 2 OR a.final_status = 4 THEN
			
					BIG_SEC_TO_TIME(
					BIG_TIME_TO_SEC(f_get_waktu_freq_new(a.loan_app_no)) + 
					BIG_TIME_TO_SEC(f_get_bpr_time(f_get_dcrm_time_today(a.loan_app_no, 'staff'),f_get_dcrm_time_today(a.loan_app_no, 'spv3')))
					)
			
		
		ELSE
		f_get_waktu_freq_new(a.loan_app_no)
	END as waktu_frequensi,
	-- f_get_waktu_freq(a.loan_app_no) as waktu_frequensi,
	get_waktu_tunggu_dcrm(a.loan_app_no) as pickup_dcrm,
	CASE
		WHEN a.final_status = 2 OR a.final_status = 4 THEN
		'00:00:00'
		ELSE
		-- '00:00:00'
	  get_waktu_pengerjaan_dcrm(a.loan_app_no)
	END as dcrm,
	BIG_SEC_TO_TIME(
	BIG_TIME_TO_SEC(f_get_waktu_freq_new(a.loan_app_no))+
	BIG_TIME_TO_SEC(get_waktu_tunggu_dcrm(a.loan_app_no))+
	BIG_TIME_TO_SEC(get_waktu_pengerjaan_dcrm(a.loan_app_no))
	) as total_waktu
	FROM
	data_file AS a
	INNER JOIN
	branchlist
	ON 
		a.kode_cabang = branchlist.branch_code
WHERE date(a.date_input) BETWEEN dari and sampai
-- and final_status =1 
-- and (select count(1) from comment b where b.loan_app_no = a.loan_app_no) = 3 -- and a.loan_app_no='ID022100656'
	ORDER BY a.date_input;
	
-- 
--     # Create aggregated temporary table
    CREATE TEMPORARY TABLE temp_aggregated_data AS
    SELECT 
        date(date_input) as tanggal,
count(`<10`) as kurang_10,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<10`)))) as 'sum_kurang10',
count(`<12`) as kurang_12,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<12`)))) as 'sum_kurang12',
count(`<13`) as kurang_13,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<13`)))) as 'sum_kurang13',
count(`<15`) as kurang_15,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<15`)))) as 'sum_kurang15',
count(`<17`) as kurang_17,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<17`)))) as 'sum_kurang17',
count(`<19`) as kurang_19,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<19`)))) as 'sum_kurang19',
count(`>19`) as lebih_19,
BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`>19`)))) as 'sum_lebih19',
count(date_input) as total_pengajuan,
	BIG_SEC_TO_TIME(check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<10`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<12`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<13`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<15`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<17`)))+
  check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<19`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`>19`)))
) as total_waktu_pengajuan,
sum(frequensi) as frequensi,
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(waktu_frequensi)))) as frequensi_waktu,
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(pickup_dcrm)))) AS waktu_tunggu_dcrm,
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(dcrm)))) AS waktu_dcrm,
BIG_SEC_TO_TIME(
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<10`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<12`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<13`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<15`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<17`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<19`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`>19`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(waktu_frequensi)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(pickup_dcrm)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(dcrm)))
)as total_waktu_keseluruhan,
BIG_SEC_TO_TIME(
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(`<10`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<12`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<13`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<15`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<17`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<19`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`>19`)))
	)/count(date_input)
) as perbatch_ub,
BIG_SEC_TO_TIME(
    (sum(BIG_TIME_TO_SEC(waktu_frequensi))) / NULLIF(sum(frequensi), 0)
) as pemenuhan_kekurangan_ub,
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(pickup_dcrm)))/count(date_input))  as rata_waktu_tunggu_dcrm,
BIG_SEC_TO_TIME((sum(BIG_TIME_TO_SEC(dcrm)))/count(date_input))  as pengerjaan_dcrm,
BIG_SEC_TO_TIME(
	((
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<10`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<12`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<13`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<15`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<17`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`<19`)))+
	check_time_null_to_int(sum(BIG_TIME_TO_SEC(`>19`)))
	)/count(date_input))+
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(waktu_frequensi)))/NULLIF(sum(frequensi), 0))+
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(pickup_dcrm)))/count(date_input))+
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(dcrm)))/count(date_input))
) as rata_waktu_perhari,

BIG_SEC_TO_TIME(
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(pickup_dcrm)))/count(date_input))+
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(dcrm)))/count(date_input))
) as rata_waktu_perhari_ideal,
BIG_SEC_TO_TIME(
	check_time_null_to_int((sum(BIG_TIME_TO_SEC(dcrm)))/count(date_input))
) as rata_waktu_perhari_dcrm,
BIG_SEC_TO_TIME(MAX(BIG_TIME_TO_SEC(total_waktu)))as waktu_terlama
    FROM 
        temp_resume_perhari
    GROUP BY date(date_input);

    # Calculate waktu_tercepat separately
    CREATE TEMPORARY TABLE temp_waktu_tercepat AS
    SELECT 
        date_input,
        BIG_SEC_TO_TIME(MIN(BIG_TIME_TO_SEC(total_waktu))) AS waktu_tercepat
    FROM 
        temp_resume_perhari
    WHERE 
        final_status IN ('Verify', 'TBO')
    GROUP BY date(date_input);

--     # Insert into final temp_sla_agregate table
    INSERT INTO temp_sla_agregate
		(
		tanggal,
		kurang_10,
		sum_kurang10,
		kurang_12,
		sum_kurang12,
		kurang_13,
		sum_kurang13,
		kurang_15,
		sum_kurang15,
		kurang_17,
		sum_kurang17,
		kurang_19,
		sum_kurang19,
		lebih_19,
		sum_lebih19,
		total_pengajuan,
		total_waktu_pengajuan,
		frekuensi,
		frekuensi_waktu,
		waktu_tunggu_dcrm,
		waktu_dcrm,
		total_waktu_keseluruhan,
		perbatch_ub,
		pemenuhan_kekurangan_ub,
		rata_waktu_tunggu_dcrm,
		pengerjaan_dcrm,
		rata_waktu_perhari,
		rata_waktu_perhari_ideal,
		rata_waktu_perhari_dcrm,
		waktu_terlama,
		waktu_tercepat
		)
    SELECT 
        a.tanggal,
        a.kurang_10,
        a.sum_kurang10,
        a.kurang_12,
        a.sum_kurang12,
        a.kurang_13,
        a.sum_kurang13,
        a.kurang_15,
        a.sum_kurang15,
        a.kurang_17,
        a.sum_kurang17,
        a.kurang_19,
        a.sum_kurang19,
				a.lebih_19,
        a.sum_lebih19,
        a.total_pengajuan,
        a.total_waktu_pengajuan,
        a.frequensi,
        a.frequensi_waktu,
        a.waktu_tunggu_dcrm,
        a.waktu_dcrm,
        a.total_waktu_keseluruhan,
        a.perbatch_ub,
        a.pemenuhan_kekurangan_ub,
        a.rata_waktu_tunggu_dcrm,
        a.pengerjaan_dcrm,
        a.rata_waktu_perhari,
        a.rata_waktu_perhari_ideal,
        a.rata_waktu_perhari_dcrm,
        a.waktu_terlama,
        -- b.waktu_tercepat
				 (SELECT 
							BIG_SEC_TO_TIME(MIN(BIG_TIME_TO_SEC(total_waktu))) AS waktu_tercepat
					FROM 
							temp_resume_perhari
					WHERE 
							final_status IN ('Verify', 'TBO') and date(date_input) = a.tanggal
					GROUP BY date(date_input)) as waktu_tercepat
   
    FROM 
        temp_aggregated_data a;
--     LEFT JOIN 
--         temp_resume_perhari b
--     ON 
--         a.tanggal = b.date_input;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for total_perpanjangan
-- ----------------------------
DROP PROCEDURE IF EXISTS `total_perpanjangan`;
delimiter ;;
CREATE PROCEDURE `total_perpanjangan`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	branchlist.branch_name,
	count( 1 ) as jml
FROM
	perubahan_jam_layanan
	INNER JOIN
	branchlist
	ON 
		perubahan_jam_layanan.branch_input = branchlist.branch_code
WHERE
	date( date_input ) BETWEEN dari
	AND sampai
GROUP BY
	branch_input
	order by jml desc limit 10;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for total_produk
-- ----------------------------
DROP PROCEDURE IF EXISTS `total_produk`;
delimiter ;;
CREATE PROCEDURE `total_produk`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	modul,
	count( 1 ) as jml
FROM
	data_file 
WHERE
	date( date_input ) BETWEEN dari
	AND sampai
	AND user_spv2 is not null
GROUP BY
	modul
	order by jml;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for total_produk_bar
-- ----------------------------
DROP PROCEDURE IF EXISTS `total_produk_bar`;
delimiter ;;
CREATE PROCEDURE `total_produk_bar`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
	modul,
	count( 1 ) as jml
FROM
	data_file 
WHERE
	date( date_input ) BETWEEN dari
	AND sampai
GROUP BY
	modul
	order by jml;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for total_reject
-- ----------------------------
DROP PROCEDURE IF EXISTS `total_reject`;
delimiter ;;
CREATE PROCEDURE `total_reject`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT 
    branchlist.branch_name,
    COUNT(*) AS total_jumlah_data, 
   SUM(CASE 
        WHEN final_status = 4 THEN 1 
        ELSE 0 
    END) AS reject,
    (SUM(CASE 
        WHEN final_status = 4 THEN 1 
        ELSE 0 
    END) / COUNT(*)) * 100 AS persentase_reject,
		SUM(CASE 
        WHEN final_status = 2 THEN 1 
        ELSE 0 
    END) AS not_verify,
    (SUM(CASE 
        WHEN final_status = 2 THEN 1 
        ELSE 0 
    END) / COUNT(*)) * 100 AS persentase_not_verify
FROM 
    data_file 
		LEFT JOIN branchlist on branchlist.branch_code = data_file.branch_input
WHERE
	date( date_input ) BETWEEN dari
	AND sampai
GROUP BY
	branch_input
	order by persentase_reject desc limit 10;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for total_reviewer_bpr1
-- ----------------------------
DROP PROCEDURE IF EXISTS `total_reviewer_bpr1`;
delimiter ;;
CREATE PROCEDURE `total_reviewer_bpr1`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT

	IF
		(
			final_status_spv2 = 1,
			'Verify',
		IF
			(
				final_status_spv2 = 2,
				'Not Verify',
			IF
				(
					final_status_spv2 = 3,
					'TBO',
				IF
					( final_status_spv2 = 4, 'Reject', "" )))) AS status,
				count( 1 ) AS jml 
				FROM
					data_file
					LEFT JOIN `users` ON data_file.user_spv2 = `users`.nik 
				WHERE
					date( date_input ) BETWEEN dari 
					AND sampai
					and user_spv2 is not null
				GROUP BY
					final_status_spv2 
			ORDER BY
	jml DESC;
	
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for total_reviewer_bpr1_history
-- ----------------------------
DROP PROCEDURE IF EXISTS `total_reviewer_bpr1_history`;
delimiter ;;
CREATE PROCEDURE `total_reviewer_bpr1_history`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
IF
	(
		`comment`.flag_spv = 1,
		'Verify',
	IF
		(
			`comment`.flag_spv = 2,
			'Not Verify',
		IF
			(
				`comment`.flag_spv = 3,
				'TBO',
			IF
			( `comment`.flag_spv = 4, 'Reject', "" )))) AS status,
	count(1) as jml
FROM
	`comment`
	INNER JOIN users ON `comment`.user_name = users.nik 
	INNER JOIN
	data_file
	ON 
		`comment`.loan_app_no = data_file.loan_app_no
WHERE
	date( comment_date ) BETWEEN dari
	AND sampai 
	and `comment`.level_spv = 'spv2'
	and date(data_file.date_input) NOT BETWEEN dari AND sampai
GROUP BY `comment`.flag_spv
			ORDER BY
	`comment`.flag_spv asc;
	
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for total_reviewer_bpr1_inc_pending
-- ----------------------------
DROP PROCEDURE IF EXISTS `total_reviewer_bpr1_inc_pending`;
delimiter ;;
CREATE PROCEDURE `total_reviewer_bpr1_inc_pending`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
IF
	(
		final_status_spv2 = 0,
		'Pending',
	IF
		(
			final_status_spv2 = 1,
			'Verify',
		IF
			(
				final_status_spv2 = 2,
				'Not Verify',
			IF
				(
					final_status_spv2 = 3,
					'TBO',
				IF
					( final_status_spv2 = 4, 'Reject', "" ))))) AS status,
				count( 1 ) AS jml 
				FROM
					data_file
					LEFT JOIN `users` ON data_file.user_spv2 = `users`.nik 
				WHERE
					date( date_input ) BETWEEN dari 
					AND sampai
				GROUP BY
					final_status_spv2 
			ORDER BY
	jml DESC;
	
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for total_reviewer_bpr2
-- ----------------------------
DROP PROCEDURE IF EXISTS `total_reviewer_bpr2`;
delimiter ;;
CREATE PROCEDURE `total_reviewer_bpr2`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT

	IF
		(
			final_status_spv3 = 1,
			'Verify',
		IF
			(
				final_status_spv3 = 2,
				'Not Verify',
			IF
				(
					final_status_spv3 = 3,
					'TBO',
				IF
					( final_status_spv3 = 4, 'Reject', "" )))) AS status,
				count( 1 ) AS jml 
				FROM
					data_file
					LEFT JOIN `users` ON data_file.user_spv3 = `users`.nik 
				WHERE
					date( date_input ) BETWEEN dari 
					AND sampai
					and final_status_spv3 <> 0
				GROUP BY
					final_status_spv3
			ORDER BY
	jml DESC;
	
	
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for total_reviewer_bpr2_history
-- ----------------------------
DROP PROCEDURE IF EXISTS `total_reviewer_bpr2_history`;
delimiter ;;
CREATE PROCEDURE `total_reviewer_bpr2_history`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
IF
	(
		`comment`.flag_spv = 1,
		'Verify',
	IF
		(
			`comment`.flag_spv = 2,
			'Not Verify',
		IF
			(
				`comment`.flag_spv = 3,
				'TBO',
			IF
			( `comment`.flag_spv = 4, 'Reject', "" )))) AS status,
	count(1) as jml
FROM
	`comment`
	INNER JOIN users ON `comment`.user_name = users.nik 
	INNER JOIN
	data_file
	ON 
		`comment`.loan_app_no = data_file.loan_app_no
WHERE
	date( comment_date ) BETWEEN dari
	AND sampai 
	and (`comment`.level_spv = 'spv3' or `comment`.level_spv = 'spv4')
	and date(data_file.date_input) NOT BETWEEN dari AND sampai
GROUP BY `comment`.flag_spv
			ORDER BY
	`comment`.flag_spv asc;
	
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for total_reviewer_bpr2_inc_pending
-- ----------------------------
DROP PROCEDURE IF EXISTS `total_reviewer_bpr2_inc_pending`;
delimiter ;;
CREATE PROCEDURE `total_reviewer_bpr2_inc_pending`(IN `dari` date,IN `sampai` date)
BEGIN
	#Routine body goes here...
SELECT
IF
	(
		final_status_spv3 = 0,
		'Pending',
	IF
		(
			final_status_spv3 = 1,
			'Verify',
		IF
			(
				final_status_spv3 = 2,
				'Not Verify',
			IF
				(
					final_status_spv3 = 3,
					'TBO',
				IF
					( final_status_spv3 = 4, 'Reject', "" ))))) AS status,
				count( 1 ) AS jml 
				FROM
					data_file
					LEFT JOIN `users` ON data_file.user_spv3 = `users`.nik 
				WHERE
					date( date_input ) BETWEEN dari 
					AND sampai
				GROUP BY
					final_status_spv3
			ORDER BY
	jml DESC;
	
	
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for update_no_registrasi
-- ----------------------------
DROP PROCEDURE IF EXISTS `update_no_registrasi`;
delimiter ;;
CREATE PROCEDURE `update_no_registrasi`()
BEGIN

DECLARE    loan_app_no VARCHAR(100);
DECLARE    date_input DATETIME;
DECLARE    flag_spv INT;
DECLARE    date_flag_spv1 DATETIME;
DECLARE    no_registrasi VARCHAR(100);
DECLARE    current_date_var DATETIME;
DECLARE    current_number INT;


    SELECT loan_app_no, date_input, flag_spv, date_flag_spv1, no_registrasi
    INTO loan_app_no, date_input, flag_spv, date_flag_spv1, no_registrasi
    FROM data_file_sample;

    SET current_date_var = current_date();
    SET current_number = 1;

    IF flag_spv = 1 OR flag_spv = 3 THEN
    
        IF date_flag_spv1 <> current_date_var THEN
            SET current_number = 1;
        END IF;
    
        SET no_registrasi = CONCAT(YEAR(date_flag_spv1), LPAD(MONTH(date_flag_spv1), 2, 0), LPAD(DAY(date_flag_spv1), 2, 0), '-', current_number);
    
        UPDATE data_file_sample
        SET no_registrasi = no_registrasi
        WHERE loan_app_no = loan_app_no;
    END IF;


END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for waktu_review_spv
-- ----------------------------
DROP PROCEDURE IF EXISTS `waktu_review_spv`;
delimiter ;;
CREATE PROCEDURE `waktu_review_spv`(IN `start_date` timestamp,IN `end_date` timestamp)
BEGIN
	#Routine body goes here...
SELECT
	a.branch_dir,
	branchlist.branch_name,
	a.loan_app_no,
	(
	SELECT
		COUNT( x.loan_app_no ) 
	FROM
		detail_file x 
	WHERE
		x.loan_app_no = a.loan_app_no 
		AND x.created_at BETWEEN start_date 
		AND end_date 
	GROUP BY
		x.loan_app_no 
	ORDER BY
		x.created_at ASC 
	) AS jml_dok,
	(
	SELECT
		x.created_at 
	FROM
		detail_file x 
	WHERE
		x.loan_app_no = a.loan_app_no 
		AND x.created_at BETWEEN start_date 
		AND end_date 
	ORDER BY
		x.created_at ASC 
		LIMIT 1 
	) AS start_upload_time,
	(
	SELECT
		x.created_at 
	FROM
		detail_file x 
	WHERE
		x.loan_app_no = a.loan_app_no 
		AND x.created_at BETWEEN start_date 
		AND end_date 
	ORDER BY
		x.created_at DESC 
		LIMIT 1 
	) AS end_upload_time,
	timediff((
		SELECT
			x.created_at 
		FROM
			detail_file x 
		WHERE
			x.loan_app_no = a.loan_app_no 
			AND x.created_at BETWEEN start_date 
			AND end_date 
		ORDER BY
			x.created_at DESC 
			LIMIT 1 
			),
		(
		SELECT
			x.created_at 
		FROM
			detail_file x 
		WHERE
			x.loan_app_no = a.loan_app_no 
			AND x.created_at BETWEEN start_date 
			AND end_date 
		ORDER BY
			x.created_at ASC 
			LIMIT 1 
		)) AS waktu,
	( SELECT x.user_spv1 FROM data_file x WHERE x.loan_app_no = a.loan_app_no ) AS user_spv1,
	( SELECT x.final_status_spv1 FROM data_file x WHERE x.loan_app_no = a.loan_app_no ) AS final_status_spv1,
	( SELECT x.date_flag_spv1 FROM data_file x WHERE x.loan_app_no = a.loan_app_no ) AS date_flag_spv1,
	timediff((
		SELECT
			x.date_flag_spv1 
		FROM
			data_file x 
		WHERE
			x.loan_app_no = a.loan_app_no 
			),(
		SELECT
			x.created_at 
		FROM
			detail_file x 
		WHERE
			x.loan_app_no = a.loan_app_no 
			AND x.created_at BETWEEN start_date 
			AND end_date 
		ORDER BY
			x.created_at DESC 
			LIMIT 1 
		)) AS waktu_review_spv1
	
FROM
	detail_file a
	INNER JOIN branchlist ON a.branch_dir = branchlist.branch_code 
WHERE
	a.created_at BETWEEN start_date 
	AND end_date 
GROUP BY
	a.loan_app_no 
ORDER BY
	waktu ASC;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for waktu_upload
-- ----------------------------
DROP PROCEDURE IF EXISTS `waktu_upload`;
delimiter ;;
CREATE PROCEDURE `waktu_upload`(IN `start_date` timestamp,IN `end_date` timestamp)
BEGIN
	#Routine body goes here...
SELECT
	
	a.branch_dir, 
	
	branchlist.branch_name,
	a.loan_app_no,
	(select COUNT(x.loan_app_no) from detail_file x where x.loan_app_no = a.loan_app_no and x.created_at BETWEEN start_date
AND end_date
GROUP BY x.loan_app_no ORDER BY x.created_at asc) as jml_dok,
	(select x.created_at from detail_file x where x.loan_app_no = a.loan_app_no and x.created_at BETWEEN start_date
AND end_date ORDER BY x.created_at asc limit 1) as start_upload_time,
	(select x.created_at from detail_file x where x.loan_app_no = a.loan_app_no and x.created_at BETWEEN start_date
AND end_date ORDER BY x.created_at desc limit 1) as end_upload_time,
	timediff((select x.created_at from detail_file x where x.loan_app_no = a.loan_app_no and x.created_at BETWEEN start_date
AND end_date ORDER BY x.created_at desc limit 1), (select x.created_at from detail_file x where x.loan_app_no = a.loan_app_no and x.created_at BETWEEN start_date
AND end_date  ORDER BY x.created_at asc limit 1)) as waktu
FROM
	detail_file a
	INNER JOIN
	branchlist
	ON 
		a.branch_dir = branchlist.branch_code
WHERE
a.created_at BETWEEN start_date
AND end_date
GROUP BY a.loan_app_no
ORDER BY
	waktu ASC;
	
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for wkatu_upload
-- ----------------------------
DROP PROCEDURE IF EXISTS `wkatu_upload`;
delimiter ;;
CREATE PROCEDURE `wkatu_upload`(IN `start_date` timestamp,IN `end_date` timestamp)
BEGIN
	#Routine body goes here...
SELECT
	
	a.branch_dir, 
	
	branchlist.branch_name,
	a.loan_app_no,
	(select COUNT(x.loan_app_no) from detail_file x where x.loan_app_no = a.loan_app_no and x.created_at BETWEEN start_date
AND end_date
GROUP BY x.loan_app_no ORDER BY x.created_at asc) as jml_dok,
	(select x.created_at from detail_file x where x.loan_app_no = a.loan_app_no and x.created_at BETWEEN start_date
AND end_date ORDER BY x.created_at asc limit 1) as start_upload_time,
	(select x.created_at from detail_file x where x.loan_app_no = a.loan_app_no and x.created_at BETWEEN start_date
AND end_date ORDER BY x.created_at desc limit 1) as end_upload_time,
	timediff((select x.created_at from detail_file x where x.loan_app_no = a.loan_app_no and x.created_at BETWEEN start_date
AND end_date ORDER BY x.created_at desc limit 1), (select x.created_at from detail_file x where x.loan_app_no = a.loan_app_no and x.created_at BETWEEN start_date
AND end_date  ORDER BY x.created_at asc limit 1)) as waktu
FROM
	detail_file a
	INNER JOIN
	branchlist
	ON 
		a.branch_dir = branchlist.branch_code
WHERE
a.created_at BETWEEN start_date
AND end_date
GROUP BY a.loan_app_no
ORDER BY
	waktu ASC;
	
END
;;
delimiter ;

-- ----------------------------
-- Event structure for get_limit_tbo_overdue
-- ----------------------------
DROP EVENT IF EXISTS `get_limit_tbo_overdue`;
delimiter ;;
CREATE EVENT `get_limit_tbo_overdue`
ON SCHEDULE
EVERY '1' MINUTE STARTS '2023-02-20 00:00:00'
DO call get_limit_tbo_overdue()
;;
delimiter ;

-- ----------------------------
-- Event structure for reject_perpanjangan_waktu
-- ----------------------------
DROP EVENT IF EXISTS `reject_perpanjangan_waktu`;
delimiter ;;
CREATE EVENT `reject_perpanjangan_waktu`
ON SCHEDULE
EVERY '1' MINUTE STARTS '2024-02-19 14:00:00'
DO call reject_perpanjangan_waktu()
;;
delimiter ;

-- ----------------------------
-- Event structure for reset_cut_off
-- ----------------------------
DROP EVENT IF EXISTS `reset_cut_off`;
delimiter ;;
CREATE EVENT `reset_cut_off`
ON SCHEDULE
EVERY '1' DAY STARTS '2022-11-16 01:00:00'
DO call reset_cut_off()
;;
delimiter ;

-- ----------------------------
-- Event structure for update_limit_tbo_overdue
-- ----------------------------
DROP EVENT IF EXISTS `update_limit_tbo_overdue`;
delimiter ;;
CREATE EVENT `update_limit_tbo_overdue`
ON SCHEDULE
EVERY '1' MINUTE STARTS '2023-02-20 00:00:00'
DO call get_limit_tbo_overdue()
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
