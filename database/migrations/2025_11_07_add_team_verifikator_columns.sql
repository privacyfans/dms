-- ========================================================================
-- SQL QUERY untuk Menambahkan Kolom Team Verifikator
-- Migration File: 2025_11_07_000001_add_team_verifikator_columns_to_data_file_table.php
-- ========================================================================
-- Database: DMS Bank Woori Saudara
-- Table: data_file
-- ========================================================================

-- FORWARD MIGRATION (UP)
-- Menambahkan kolom-kolom baru untuk Team Verifikator

ALTER TABLE `data_file`
ADD COLUMN `final_status_verif1` INT(11) DEFAULT 0 COMMENT 'Status Team Verifikator Level 1 (0=Pending, 5=Approve, 6=Not Approve)' AFTER `final_status_spv3`,
ADD COLUMN `user_verif1` VARCHAR(255) NULL COMMENT 'NIK Team Verifikator Level 1' AFTER `final_status_verif1`,
ADD COLUMN `date_flag_verif1` DATETIME NULL COMMENT 'Tanggal review Team Verifikator Level 1' AFTER `user_verif1`,
ADD COLUMN `final_status_verif2` INT(11) DEFAULT 0 COMMENT 'Status Team Verifikator Level 2 (0=Pending, 5=Approve, 6=Not Approve)' AFTER `date_flag_verif1`,
ADD COLUMN `user_verif2` VARCHAR(255) NULL COMMENT 'NIK Team Verifikator Level 2' AFTER `final_status_verif2`,
ADD COLUMN `date_flag_verif2` DATETIME NULL COMMENT 'Tanggal review Team Verifikator Level 2' AFTER `user_verif2`,
ADD COLUMN `file_bukti_verifikator` VARCHAR(255) NULL COMMENT 'Path file bukti hasil pemeriksaan' AFTER `date_flag_verif2`;

-- Menambahkan index untuk performa query
ALTER TABLE `data_file`
ADD INDEX `idx_verifikator` (`final_status_verif1`, `final_status_verif2`);

-- ========================================================================
-- VERIFIKASI KOLOM BERHASIL DITAMBAHKAN
-- ========================================================================
-- Jalankan query ini untuk memverifikasi kolom sudah ditambahkan:
-- SHOW COLUMNS FROM `data_file` LIKE '%verif%';
-- atau
-- DESC `data_file`;

-- ========================================================================
-- ROLLBACK MIGRATION (DOWN) - JIKA PERLU DIBATALKAN
-- ========================================================================
-- HANYA JALANKAN JIKA INGIN MENGHAPUS PERUBAHAN!
-- UNCOMMENT QUERY DI BAWAH INI:

/*
-- Drop index terlebih dahulu
ALTER TABLE `data_file` DROP INDEX `idx_verifikator`;

-- Hapus kolom-kolom yang ditambahkan
ALTER TABLE `data_file`
DROP COLUMN `final_status_verif1`,
DROP COLUMN `user_verif1`,
DROP COLUMN `date_flag_verif1`,
DROP COLUMN `final_status_verif2`,
DROP COLUMN `user_verif2`,
DROP COLUMN `date_flag_verif2`,
DROP COLUMN `file_bukti_verifikator`;
*/

-- ========================================================================
-- CATATAN PENTING:
-- ========================================================================
-- 1. Backup database terlebih dahulu sebelum menjalankan query ini
-- 2. Query ini akan menambahkan 7 kolom baru ke tabel data_file
-- 3. Pastikan tidak ada proses yang sedang berjalan pada tabel data_file
-- 4. Jika ada banyak data, ALTER TABLE mungkin memakan waktu beberapa menit
-- 5. Index idx_verifikator akan meningkatkan performa query untuk team verifikator
-- ========================================================================

-- ========================================================================
-- SETELAH MENJALANKAN QUERY INI:
-- ========================================================================
-- 1. Update entry di tabel migrations (jika ingin tracking):
/*
INSERT INTO migrations (migration, batch)
VALUES ('2025_11_07_000001_add_team_verifikator_columns_to_data_file_table',
        (SELECT COALESCE(MAX(batch), 0) + 1 FROM migrations));
*/

-- 2. Verifikasi struktur tabel:
-- SHOW CREATE TABLE `data_file`;

-- 3. Test query untuk loan yang perlu verifikator:
/*
SELECT
    loan_app_no,
    nama_debitur,
    produk,
    final_status_spv2,
    final_status_spv3,
    final_status_verif1,
    final_status_verif2,
    user_verif1,
    user_verif2,
    file_bukti_verifikator
FROM data_file df
INNER JOIN master_produk mp ON df.produk = mp.produk
WHERE mp.jenis_produk IN (2, 3, 8)  -- KUPEN Hybrid, KUPEG, KUPEN Hybrid GO
  AND (final_status_spv2 = 1 OR final_status_spv2 = 3)
  AND (final_status_spv3 = 1 OR final_status_spv3 = 3)
  AND final_status_verif1 = 0
ORDER BY date_input ASC;
*/

-- ========================================================================
-- END OF SQL MIGRATION
-- ========================================================================
