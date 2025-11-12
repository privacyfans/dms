-- ========================================================================
-- SQL SETUP untuk Team Verifikator Users
-- ========================================================================
-- Database: DMS Bank Woori Saudara
-- Purpose: Insert user team verifikator dan verifikasi setup
-- ========================================================================

-- ========================================================================
-- 1. VERIFIKASI MASTER PRODUK
-- ========================================================================
-- Cek mapping produk yang memerlukan team verifikator
SELECT
    id,
    produk,
    jenis_produk,
    CASE
        WHEN jenis_produk = 2 THEN 'KUPEN HYBRID'
        WHEN jenis_produk = 3 THEN 'KUPEG (Kredit Umum Pegawai)'
        WHEN jenis_produk = 8 THEN 'KUPEN HYBRID GO (Grace Period Option)'
        ELSE 'LAINNYA'
    END AS nama_jenis
FROM master_produk
WHERE jenis_produk IN (2, 3, 8)
ORDER BY jenis_produk;

-- Jika ada produk yang mapping-nya salah, update dengan query ini:
/*
-- Contoh update jika diperlukan:
UPDATE master_produk SET jenis_produk = 2 WHERE produk = 'KUPEN HYBRID';
UPDATE master_produk SET jenis_produk = 3 WHERE produk = 'KUPEG';
UPDATE master_produk SET jenis_produk = 8 WHERE produk LIKE '%HYBRID GO%';
*/

-- ========================================================================
-- 2. INSERT USER TEAM VERIFIKATOR (PRODUCTION)
-- ========================================================================
-- SESUAIKAN NIK, NAMA, EMAIL, dan CABANG sesuai data sebenarnya!

-- Team Verifikator Level 1
INSERT INTO users (
    nik,
    role,
    name,
    email,
    cabang,
    created_at,
    updated_at
) VALUES (
    'NIK_VERIF_LVL1',  -- GANTI dengan NIK sebenarnya
    'team_verifikator_lvl1',
    'Nama Team Verifikator Level 1',  -- GANTI dengan nama sebenarnya
    'verifikator_lvl1@bws.co.id',  -- GANTI dengan email sebenarnya
    'HQ',  -- GANTI dengan kode cabang sebenarnya
    NOW(),
    NOW()
);

-- Team Verifikator Level 2
INSERT INTO users (
    nik,
    role,
    name,
    email,
    cabang,
    created_at,
    updated_at
) VALUES (
    'NIK_VERIF_LVL2',  -- GANTI dengan NIK sebenarnya
    'team_verifikator_lvl2',
    'Nama Team Verifikator Level 2',  -- GANTI dengan nama sebenarnya
    'verifikator_lvl2@bws.co.id',  -- GANTI dengan email sebenarnya
    'HQ',  -- GANTI dengan kode cabang sebenarnya
    NOW(),
    NOW()
);

-- ========================================================================
-- 3. INSERT USER TEST (untuk TESTING ONLY)
-- ========================================================================
-- User ini hanya untuk testing, HAPUS setelah selesai testing!

-- Test User - Team Verifikator Level 1
INSERT INTO users (
    nik,
    role,
    name,
    email,
    cabang,
    created_at,
    updated_at
) VALUES (
    'TEST_VERIF1',
    'team_verifikator_lvl1',
    'TEST - Verifikator Level 1',
    'test.verif1@test.com',
    '001',
    NOW(),
    NOW()
);

-- Test User - Team Verifikator Level 2
INSERT INTO users (
    nik,
    role,
    name,
    email,
    cabang,
    created_at,
    updated_at
) VALUES (
    'TEST_VERIF2',
    'team_verifikator_lvl2',
    'TEST - Verifikator Level 2',
    'test.verif2@test.com',
    '001',
    NOW(),
    NOW()
);

-- ========================================================================
-- 4. VERIFIKASI USER BERHASIL DITAMBAHKAN
-- ========================================================================
SELECT
    nik,
    role,
    name,
    email,
    cabang,
    created_at
FROM users
WHERE role IN ('team_verifikator_lvl1', 'team_verifikator_lvl2')
ORDER BY role;

-- ========================================================================
-- 5. HAPUS TEST USER (setelah selesai testing)
-- ========================================================================
/*
DELETE FROM users WHERE nik IN ('TEST_VERIF1', 'TEST_VERIF2');
*/

-- ========================================================================
-- 6. QUERY UNTUK CEK LOAN YANG PERLU VERIFIKATOR
-- ========================================================================
-- Query untuk cek loan yang menunggu Team Verifikator Level 1
SELECT
    df.loan_app_no,
    df.nama_debitur,
    df.produk,
    mp.jenis_produk,
    df.kode_cabang,
    df.date_input,
    df.final_status_spv2,
    df.final_status_spv3,
    df.final_status_verif1,
    df.final_status_verif2
FROM data_file df
INNER JOIN master_produk mp ON df.produk = mp.produk
WHERE mp.jenis_produk IN (2, 3, 8)  -- Produk yang perlu verifikator
  AND ((df.final_status_spv2 = 1 AND df.final_status_spv3 = 1)  -- Verify
       OR (df.final_status_spv2 = 3 AND df.final_status_spv3 = 3))  -- TBO
  AND df.final_status_verif1 = 0  -- Belum direview verifikator level 1
ORDER BY df.date_input ASC
LIMIT 10;

-- Query untuk cek loan yang menunggu Team Verifikator Level 2
SELECT
    df.loan_app_no,
    df.nama_debitur,
    df.produk,
    mp.jenis_produk,
    df.kode_cabang,
    df.date_input,
    df.final_status_verif1,
    df.user_verif1,
    df.date_flag_verif1,
    df.final_status_verif2
FROM data_file df
INNER JOIN master_produk mp ON df.produk = mp.produk
WHERE mp.jenis_produk IN (2, 3, 8)
  AND df.final_status_verif1 IN (5, 6)  -- Sudah direview level 1
  AND df.final_status_verif2 = 0  -- Belum direview level 2
ORDER BY df.date_input ASC
LIMIT 10;

-- ========================================================================
-- 7. QUERY UNTUK MONITORING STATUS
-- ========================================================================
-- Summary status verifikator per produk
SELECT
    mp.produk,
    COUNT(*) as total_loan,
    SUM(CASE WHEN df.final_status_verif1 = 0 THEN 1 ELSE 0 END) as pending_verif1,
    SUM(CASE WHEN df.final_status_verif1 = 5 THEN 1 ELSE 0 END) as approve_verif1,
    SUM(CASE WHEN df.final_status_verif1 = 6 THEN 1 ELSE 0 END) as not_approve_verif1,
    SUM(CASE WHEN df.final_status_verif2 = 0 THEN 1 ELSE 0 END) as pending_verif2,
    SUM(CASE WHEN df.final_status_verif2 = 5 THEN 1 ELSE 0 END) as approve_verif2,
    SUM(CASE WHEN df.final_status_verif2 = 6 THEN 1 ELSE 0 END) as not_approve_verif2
FROM data_file df
INNER JOIN master_produk mp ON df.produk = mp.produk
WHERE mp.jenis_produk IN (2, 3, 8)
  AND df.date_input >= DATE_SUB(NOW(), INTERVAL 30 DAY)  -- 30 hari terakhir
GROUP BY mp.produk
ORDER BY mp.produk;

-- ========================================================================
-- 8. QUERY UNTUK RESET STATUS (JIKA PERLU - HATI-HATI!)
-- ========================================================================
-- HANYA gunakan untuk testing atau jika ada error!
/*
-- Reset status verifikator untuk loan tertentu
UPDATE data_file
SET
    final_status_verif1 = 0,
    user_verif1 = NULL,
    date_flag_verif1 = NULL,
    final_status_verif2 = 0,
    user_verif2 = NULL,
    date_flag_verif2 = NULL,
    file_bukti_verifikator = NULL
WHERE loan_app_no = 'LOAN_APP_NO_HERE';
*/

-- ========================================================================
-- 9. QUERY UNTUK CEK HISTORY REVIEW
-- ========================================================================
-- Lihat history review team verifikator
SELECT
    c.loan_app_no,
    c.user_name,
    c.level_spv,
    c.comment,
    c.flag_spv,
    CASE
        WHEN c.flag_spv = 5 THEN 'Approve (Verifikator)'
        WHEN c.flag_spv = 6 THEN 'Not Approve (Verifikator)'
        ELSE 'Status Lain'
    END as status_desc,
    c.comment_date,
    c.reason
FROM comment c
WHERE c.level_spv IN ('team_verifikator_lvl1', 'team_verifikator_lvl2')
ORDER BY c.comment_date DESC
LIMIT 20;

-- ========================================================================
-- 10. QUERY UNTUK CEK FILE BUKTI VERIFIKATOR
-- ========================================================================
SELECT
    df.loan_app_no,
    df.nama_debitur,
    df.produk,
    df.final_status_verif2,
    df.file_bukti_verifikator,
    detail.file,
    detail.alias,
    detail.created_at
FROM data_file df
LEFT JOIN detail_file detail ON df.loan_app_no = detail.loan_app_no AND detail.alias = 'BUKTI_VERIFIKATOR'
WHERE df.file_bukti_verifikator IS NOT NULL
ORDER BY df.date_flag_verif2 DESC
LIMIT 10;

-- ========================================================================
-- CATATAN PENTING:
-- ========================================================================
-- 1. Sesuaikan NIK, nama, email untuk user production
-- 2. Verifikasi master_produk mapping sebelum mulai testing
-- 3. Gunakan test user untuk testing, hapus setelah selesai
-- 4. Monitor query di section 6-10 untuk tracking proses verifikator
-- 5. Backup database sebelum menjalankan query UPDATE/DELETE
-- ========================================================================
