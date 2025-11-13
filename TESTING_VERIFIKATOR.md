# Testing Documentation - User Verifikator Level 1 & Level 2

## Overview
Dokumentasi ini menjelaskan proses testing untuk fitur user verifikator yang bertanggung jawab untuk melakukan pemeriksaan loan application sebelum diproses oleh SPV.

## Workflow Verifikator

```
┌─────────────────────────────────────────────────────────────────────┐
│                         WORKFLOW VERIFIKATOR                         │
└─────────────────────────────────────────────────────────────────────┘

1. STAFF uploads loan application
   ↓
2. SPV1/SPV2/SPV3 memberikan approval/TBO (jika need_verifikator = true)
   ↓
3. TEAM VERIFIKATOR LEVEL 1 (user_verif1/team_verifikator_lvl1)
   - Melihat loan di "Waiting Verifikator Lvl1 Recommendation"
   - Melakukan review dokumen
   - Memberikan REKOMENDASI: Approve (5) atau Not Approve (6)
   ↓
4. TEAM VERIFIKATOR LEVEL 2 (user_verif2/team_verifikator_lvl2)
   - Melihat loan di "Waiting Verifikator Lvl2 Decision"
   - Melihat rekomendasi dari Level 1
   - Memberikan KEPUTUSAN FINAL: Approve (5) atau Not Approve (6)
   ↓
5. TEAM VERIFIKATOR LEVEL 1 (kembali)
   - Upload File Bukti Pemeriksaan (WAJIB)
   - Setelah upload, loan otomatis diproses:
     * Jika Verif2 APPROVE → final_status mengikuti SPV (1=Verify/3=TBO)
     * Jika Verif2 NOT APPROVE → final_status = 6 (cascade semua status)
```

## Roles Involved

| Role | NIK | Description |
|------|-----|-------------|
| `team_verifikator_lvl1` | Various | User yang memberikan rekomendasi hasil pemeriksaan |
| `user_verif1` | Specific NIK | Alias untuk team_verifikator_lvl1 |
| `team_verifikator_lvl2` | Various | User yang memberikan keputusan final |
| `user_verif2` | Specific NIK | Alias untuk team_verifikator_lvl2 |

## Prerequisites Testing

### 1. Database Setup
Pastikan tabel berikut ada dan terisi:
- `data_file` - Data loan applications
- `detail_file` - Uploaded documents
- `s_dokumen` - Master document list (ID 1-32)
- `list_pickup` - Loan locking mechanism
- `comment` - Review comments

### 2. User Accounts
Buat user dengan role:
- `team_verifikator_lvl1` (minimal 2 user untuk test locking)
- `team_verifikator_lvl2` (minimal 1 user)
- `staff` (untuk upload loan)
- `spv2` atau `spv3` (untuk approve loan)

### 3. Sample Loan Data
Buat loan application dengan kondisi:
- `need_verifikator = 1` (true)
- `final_status_spv2 > 0` atau `final_status_spv3 > 0` (sudah di-approve SPV)
- `final_status_verif1 = 0` (belum di-review Verif1)
- `final_status_verif2 = 0` (belum di-review Verif2)
- `date_input >= '2025-10-27 17:00:00'` (untuk test document filtering)

Contoh SQL:
```sql
UPDATE data_file
SET need_verifikator = 1,
    final_status_spv2 = 1,
    final_status_verif1 = 0,
    final_status_verif2 = 0,
    date_input = '2025-10-28 10:00:00'
WHERE loan_app_no = 'ID025044642';
```

## Test Cases

### TEST CASE 1: Loan Locking Mechanism

**Objective**: Memastikan hanya satu user verifikator yang dapat mengakses loan pada satu waktu.

**Roles**: `team_verifikator_lvl1` (2 users), `team_verifikator_lvl2` (2 users)

**Steps**:

1. **Login sebagai User Verif1-A**
   - Navigate ke `/waiting-verifikator-lvl1-recommendation`
   - Verifikasi: Tabel menampilkan list loan yang menunggu review

2. **Klik tombol "Show" dengan lock**
   - Klik button "Show" pada loan tertentu
   - URL harus include parameter: `?lock=1`
   - Expected: Loan terbuka, data tersimpan di `list_pickup` table

   Verify di database:
   ```sql
   SELECT * FROM list_pickup
   WHERE loan_app_no = 'ID025044642'
   AND status = 1;
   ```
   Expected result:
   - `nik` = NIK User Verif1-A
   - `status` = 1
   - `expired_at` = current_time + 15 minutes

3. **Login sebagai User Verif1-B (browser/incognito lain)**
   - Navigate ke `/waiting-verifikator-lvl1-recommendation`
   - Klik button "Show" pada loan yang SAMA

   Expected:
   - ❌ Redirect kembali ke list page
   - ✅ Flash message muncul: "Loan sedang dikerjakan oleh [NIK User Verif1-A]"
   - ✅ Alert box berwarna merah (danger)

4. **Kembali ke User Verif1-A**
   - Navigate away (klik Back atau pindah halaman)
   - Lock otomatis released

   Verify di database:
   ```sql
   SELECT * FROM list_pickup
   WHERE loan_app_no = 'ID025044642'
   AND status = 1;
   ```
   Expected: No rows (lock cleared)

5. **User Verif1-B coba lagi**
   - Klik button "Show" pada loan yang sama
   - Expected: ✅ Berhasil membuka loan

6. **Test Lock Expiry**
   - User Verif1-A buka loan (generate lock)
   - Tunggu 15 menit
   - User Verif1-B coba akses
   - Expected: ✅ Berhasil (lock expired)

**Test sama berlaku untuk `team_verifikator_lvl2`** di page `/waiting-verifikator-lvl2`

---

### TEST CASE 2: Document Filtering by Role (ID 30, 31, 32)

**Objective**: Dokumen ID 30, 31, 32 hanya visible untuk staff, team_verifikator_lvl1, team_verifikator_lvl2.

**Dokumen yang ditest**:
- ID 30: Spesimen Juru Bayar
- ID 31: SK Pengangkatan Juru Bayar
- ID 32: Nomor Telepon Juru Bayar

**Roles to test**: `staff`, `team_verifikator_lvl1`, `team_verifikator_lvl2`, `spv2`, `spv3`, `spv4`

**Prerequisites**:
- Loan harus untuk produk KUPEN/KUPEG (bukan KPH/KPKB/THT/TAWON)
- Route: `/show-new/{loan_app_no}`

**Steps**:

1. **Login sebagai `team_verifikator_lvl1`**
   - Buka loan: `/show-new/ID025044642`
   - Scroll ke section "Documents"
   - Expected: ✅ Dokumen ID 30, 31, 32 **VISIBLE** dalam list

2. **Login sebagai `team_verifikator_lvl2`**
   - Buka loan: `/show-new/ID025044642`
   - Expected: ✅ Dokumen ID 30, 31, 32 **VISIBLE**

3. **Login sebagai `staff`**
   - Buka loan: `/show-new/ID025044642`
   - Expected: ✅ Dokumen ID 30, 31, 32 **VISIBLE**

4. **Login sebagai `spv2` atau `spv3`**
   - Buka loan: `/show-new/ID025044642`
   - Expected: ❌ Dokumen ID 30, 31, 32 **TIDAK MUNCUL**

5. **Verify document count**
   - Count total dokumen yang tampil untuk setiap role
   - Verifikator/Staff: harus lebih banyak 3 dokumen dibanding SPV

**Notes**:
- Test hanya berlaku di `show-new.blade.php`
- Tidak berlaku di `show.blade.php` (untuk KPH/KPKB/THT/TAWON)

---

### TEST CASE 3: Document Filtering by Date (ID 29)

**Objective**: Dokumen ID 29 (Akseptasi Persetujuan Mitra Asuransi) hanya muncul untuk loan dengan date_input >= 2025-10-27 17:00:00.

**Dokumen**: ID 29 - Akseptasi Persetujuan Mitra Asuransi

**Prerequisites**: 2 loans dengan kondisi berbeda

**Setup**:
```sql
-- Loan A: BEFORE cutoff (should HIDE document 29)
UPDATE data_file
SET date_input = '2025-10-27 16:59:59'
WHERE loan_app_no = 'ID025044642';

-- Loan B: AFTER cutoff (should SHOW document 29)
UPDATE data_file
SET date_input = '2025-10-27 17:00:00'
WHERE loan_app_no = 'ID025044643';

-- Loan C: WAY AFTER cutoff (should SHOW document 29)
UPDATE data_file
SET date_input = '2025-10-28 10:00:00'
WHERE loan_app_no = 'ID025044644';
```

**Steps**:

1. **Test Loan A (Before Cutoff)**
   - Login sebagai `team_verifikator_lvl1`
   - Buka: `/show-new/ID025044642`
   - Scroll ke section Documents
   - Expected: ❌ Dokumen ID 29 **TIDAK MUNCUL** sama sekali

2. **Test Loan B (Exact Cutoff)**
   - Buka: `/show-new/ID025044643`
   - Expected: ✅ Dokumen ID 29 **MUNCUL** dalam list

3. **Test Loan C (After Cutoff)**
   - Buka: `/show-new/ID025044644`
   - Expected: ✅ Dokumen ID 29 **MUNCUL** dalam list

4. **Verify behavior untuk role lain**
   - Test dengan `spv2`, `staff`
   - Expected: Same behavior (date-based, not role-based)

---

### TEST CASE 4: Team Verifikator Level 1 - Form Rekomendasi

**Objective**: Verif1 dapat memberikan rekomendasi Approve/Not Approve.

**Role**: `team_verifikator_lvl1` atau `user_verif1`

**Prerequisites**:
- Loan dengan `need_verifikator = 1`
- `final_status_spv2 > 0` atau `final_status_spv3 > 0`
- `final_status_verif1 = 0`

**Steps**:

1. **Access Review Page**
   - Login sebagai `team_verifikator_lvl1`
   - Navigate: `/waiting-verifikator-lvl1-recommendation`
   - Expected: Tabel menampilkan list loans

2. **Open Loan Detail**
   - Klik button "Show" pada loan
   - URL: `/show-new/ID025044642?lock=1`
   - Expected: Loan detail terbuka

3. **Verify Form Visibility**
   - Scroll ke section "Form Review - Team Verifikator Level 1 (Rekomendasi)"
   - Card header background: **ORANGE** (bg-warning)
   - Expected: Form dengan 2 radio buttons:
     * ⭕ APPROVE (Direkomendasikan) - value: 5
     * ⭕ NOT APPROVE (Tidak Direkomendasikan) - value: 6

4. **Verify SPV Review Display**
   - Check alert box showing SPV's previous review
   - Expected: Tampil nama SPV, status (Verify/TBO), tanggal

5. **Submit Recommendation: APPROVE**
   - Pilih radio: "APPROVE (Direkomendasikan)"
   - Input comment: "Dokumen lengkap dan sesuai"
   - Klik button "Submit Rekomendasi"
   - Expected:
     * ✅ Success message
     * ✅ Loan hilang dari list `/waiting-verifikator-lvl1-recommendation`
     * ✅ Loan muncul di list `/waiting-verifikator-lvl2` (untuk Verif2)

   Verify di database:
   ```sql
   SELECT final_status_verif1, user_verif1, date_flag_verif1
   FROM data_file
   WHERE loan_app_no = 'ID025044642';
   ```
   Expected:
   - `final_status_verif1` = 5
   - `user_verif1` = NIK user yang login
   - `date_flag_verif1` = current timestamp

6. **Submit Recommendation: NOT APPROVE** (buat loan baru)
   - Setup loan baru dengan kondisi sama
   - Pilih radio: "NOT APPROVE (Tidak Direkomendasikan)"
   - Input comment: "Dokumen tidak lengkap"
   - Submit
   - Expected: Same result, but `final_status_verif1` = 6

7. **Verify Form Hidden for SPV**
   - Login sebagai `spv2`
   - Buka loan yang sama
   - Expected: ❌ Form "Team Verifikator Level 1" **TIDAK MUNCUL**

---

### TEST CASE 5: Team Verifikator Level 2 - Form Keputusan Final

**Objective**: Verif2 dapat memberikan keputusan final berdasarkan rekomendasi Verif1.

**Role**: `team_verifikator_lvl2` atau `user_verif2`

**Prerequisites**:
- Loan sudah di-review oleh Verif1 (`final_status_verif1 > 0`)
- `final_status_verif2 = 0`

**Steps**:

1. **Access Decision Page**
   - Login sebagai `team_verifikator_lvl2`
   - Navigate: `/waiting-verifikator-lvl2`
   - Expected: Tabel menampilkan loans yang sudah mendapat rekomendasi

2. **Open Loan Detail**
   - Klik button "Show" pada loan
   - URL: `/show-new/ID025044642?lock=1`
   - Expected: Loan detail terbuka

3. **Verify Form Visibility**
   - Scroll ke "Form Review - Team Verifikator Level 2 (Keputusan Final)"
   - Card header background: **GREEN** (bg-success)
   - Expected: Form dengan 2 radio buttons:
     * ⭕ APPROVE - value: 5
     * ⭕ NOT APPROVE - value: 6

4. **Verify Verif1 Recommendation Display**
   - Check alert box showing Verif1's recommendation
   - Expected:
     * Warna: Green (if Approve) or Yellow (if Not Approve)
     * Info: Status, NIK Verif1, tanggal

5. **Submit Decision: APPROVE**
   - Pilih radio: "APPROVE"
   - Input comment: "Keputusan: Approve"
   - Klik button "Submit Keputusan Final"
   - Expected:
     * ✅ Success message
     * ✅ Loan hilang dari list `/waiting-verifikator-lvl2`
     * ✅ Loan muncul kembali di list `/waiting-verifikator-lvl1-recommendation`
       (untuk upload file bukti)

   Verify di database:
   ```sql
   SELECT final_status_verif2, user_verif2, date_flag_verif2
   FROM data_file
   WHERE loan_app_no = 'ID025044642';
   ```
   Expected:
   - `final_status_verif2` = 5
   - `user_verif2` = NIK user yang login
   - `date_flag_verif2` = current timestamp

6. **Submit Decision: NOT APPROVE** (buat loan baru)
   - Setup loan baru
   - Pilih radio: "NOT APPROVE"
   - Submit
   - Expected: Same, but `final_status_verif2` = 6

---

### TEST CASE 6: Upload File Bukti Pemeriksaan

**Objective**: Verif1 wajib upload file bukti setelah Verif2 memberikan keputusan.

**Role**: `team_verifikator_lvl1` atau `user_verif1`

**Prerequisites**:
- `final_status_verif1 > 0` (sudah beri rekomendasi)
- `final_status_verif2 > 0` (sudah ada keputusan dari Verif2)
- `file_bukti_verifikator` is NULL (belum upload)

**Steps**:

1. **Access Upload Form**
   - Login sebagai `team_verifikator_lvl1`
   - Navigate: `/waiting-verifikator-lvl1-recommendation`
   - Klik "Show" pada loan yang sudah diputuskan Verif2
   - Expected: Loan terbuka

2. **Verify Form Visibility**
   - Scroll ke "Upload File Bukti Pemeriksaan - Team Verifikator Level 1"
   - Card header background: **BLUE** (bg-info)
   - Expected: Form tampil dengan:
     * Alert showing Verif2 decision (green=Approve, red=Not Approve)
     * File input field (accept: .pdf, .jpg, .jpeg, .png)
     * Upload button
     * Warning message tentang kewajiban upload

3. **Test Browser Console (F12)**
   - Buka Developer Console
   - Expected console logs:
     ```
     Upload File Bukti script loaded
     jQuery version: 3.x.x
     Swal available: true
     Form element exists: 1
     ```

4. **Upload File: Valid PDF**
   - Pilih file: `test_bukti.pdf` (size < 2MB)
   - Klik button "Upload File Bukti"
   - Expected:
     * Button berubah: "Uploading..." dengan spinner icon
     * Progress bar muncul dan bergerak 0% → 100%
     * ✅ SweetAlert popup: "Berhasil! File bukti berhasil diupload..."
     * ✅ Page auto-reload setelah klik OK
     * ❌ **TIDAK ADA** GET parameters di URL (no `?_token=...&file_bukti=...`)

   Verify di console:
   ```
   Form submitted - preventing default
   Upload success: {success: true, message: "...", file_path: "...", final_status: 1}
   ```

5. **Verify Database Changes**
   ```sql
   SELECT file_bukti_verifikator, final_status, processed
   FROM data_file
   WHERE loan_app_no = 'ID025044642';
   ```
   Expected:
   - `file_bukti_verifikator` = `/360/2025/11/13/ID025044642-BUKTI_VERIFIKATOR-1731490123.pdf`
   - `final_status` = 1 (if Verif2 Approve + SPV Verify) or 3 (if SPV TBO) or 6 (if Verif2 Not Approve)
   - `processed` = 1 or 6

6. **Verify File Storage**
   - Check file exists: `public/indexed/{branch_code}/{YYYY}/{MM}/{DD}/{filename}`
   - Expected: ✅ File exists and accessible

7. **Verify detail_file table**
   ```sql
   SELECT * FROM detail_file
   WHERE loan_app_no = 'ID025044642'
   AND alias = 'BUKTI_VERIFIKATOR';
   ```
   Expected:
   - `file` path matches `file_bukti_verifikator`
   - `branch_dir` = branch code
   - `created_at` = current timestamp

8. **Upload File: Invalid Format**
   - Pilih file: `test.docx`
   - Klik upload
   - Expected:
     * ❌ SweetAlert error: "Terjadi kesalahan saat upload file"
     * Button kembali normal: "Upload File Bukti"

9. **Upload File: Too Large**
   - Pilih file: `large_file.pdf` (> 2MB)
   - Expected: ❌ Error message tentang ukuran file

10. **Try Upload Again (after success)**
    - Reload page
    - Expected: ❌ Form **TIDAK MUNCUL** (karena `file_bukti_verifikator` sudah ada)

11. **Verify Loan Disappeared from List**
    - Navigate: `/waiting-verifikator-lvl1-recommendation`
    - Expected: ❌ Loan **TIDAK ADA** di list (sudah processed)

---

### TEST CASE 7: View Uploaded Verification Document

**Objective**: User dapat melihat file bukti yang sudah diupload di kolom Note.

**Roles**: All roles (after file uploaded)

**Prerequisites**:
- File bukti sudah diupload (`file_bukti_verifikator` NOT NULL)
- Review entry exists dari user_verif1 atau team_verifikator_lvl1

**Steps**:

1. **Open Loan Detail**
   - Login dengan role apapun
   - Buka: `/show-new/ID025044642`
   - Scroll ke section review table

2. **Locate Verif1 Review Row**
   - Cari row dengan kolom "Level SPV" = "team_verifikator_lvl1" atau "user_verif1"
   - Expected: Row ditemukan

3. **Verify Document Link in Note Column**
   - Kolom "Note" harus berisi:
     * Comment text dari Verif1
     * Line break (`<br><br>`)
     * Link dengan icon: 📄 "Lihat Dokumen Verifikasi" 🔗
   - Expected: ✅ Link tampil dengan styling `text-primary`

4. **Click Document Link**
   - Klik link "Lihat Dokumen Verifikasi"
   - Expected:
     * ✅ New tab terbuka
     * ✅ PDF/Image file ditampilkan
     * URL: `http://172.28.97.167:1000/indexed/{branch}/{YYYY}/{MM}/{DD}/{filename}`

5. **Verify Link NOT Show for Other Roles**
   - Cek row dengan "Level SPV" = "spv2" atau "spv3"
   - Kolom "Note": Only comment, **NO LINK**

---

### TEST CASE 8: Form Isolation - SPV vs Verifikator

**Objective**: Form SPV tidak muncul untuk verifikator, dan sebaliknya.

**Roles**: `team_verifikator_lvl1`, `team_verifikator_lvl2`, `spv2`, `spv3`

**Steps**:

1. **Login sebagai `team_verifikator_lvl1`**
   - Buka loan: `/show-new/ID025044642`
   - Expected forms VISIBLE:
     * ✅ "Form Review - Team Verifikator Level 1" (if eligible)
     * ✅ "Upload File Bukti Pemeriksaan" (if eligible)
   - Expected forms HIDDEN:
     * ❌ "Add Review" form (SPV's form)

2. **Login sebagai `team_verifikator_lvl2`**
   - Buka loan: `/show-new/ID025044642`
   - Expected forms VISIBLE:
     * ✅ "Form Review - Team Verifikator Level 2" (if eligible)
   - Expected forms HIDDEN:
     * ❌ "Add Review" form (SPV's form)
     * ❌ Verif1 forms

3. **Login sebagai `spv2`**
   - Buka loan: `/show-new/ID025044642`
   - Expected forms VISIBLE:
     * ✅ "Add Review" form (SPV's form)
   - Expected forms HIDDEN:
     * ❌ Verifikator Level 1 forms
     * ❌ Verifikator Level 2 forms

---

### TEST CASE 9: Final Status Cascade Logic

**Objective**: Memastikan final_status diset dengan benar berdasarkan keputusan Verif2.

**Role**: `team_verifikator_lvl1` (for upload)

**Test Scenarios**:

#### Scenario A: Verif2 APPROVE + SPV VERIFY
```sql
-- Setup
UPDATE data_file SET
  final_status_spv2 = 1, -- Verify
  final_status_verif1 = 5, -- Approve
  final_status_verif2 = 5, -- Approve
  file_bukti_verifikator = NULL
WHERE loan_app_no = 'TEST001';
```

**Steps**:
1. Upload file bukti
2. Verify database:
   ```sql
   SELECT final_status, processed FROM data_file WHERE loan_app_no = 'TEST001';
   ```
   Expected:
   - `final_status` = **1** (Verify)
   - `processed` = **1**

#### Scenario B: Verif2 APPROVE + SPV TBO
```sql
UPDATE data_file SET
  final_status_spv2 = 3, -- TBO
  final_status_verif1 = 5,
  final_status_verif2 = 5,
  file_bukti_verifikator = NULL
WHERE loan_app_no = 'TEST002';
```

**Steps**:
1. Upload file bukti
2. Expected:
   - `final_status` = **3** (TBO)
   - `processed` = **1**

#### Scenario C: Verif2 NOT APPROVE
```sql
UPDATE data_file SET
  final_status_spv2 = 1,
  final_status_verif1 = 6, -- Not Approve
  final_status_verif2 = 6, -- Not Approve
  file_bukti_verifikator = NULL
WHERE loan_app_no = 'TEST003';
```

**Steps**:
1. Upload file bukti
2. Expected:
   - `final_status` = **6**
   - `final_status_spv1` = **6** (cascade)
   - `final_status_spv2` = **6** (cascade)
   - `final_status_spv3` = **6** (cascade)
   - `final_status_verif1` = **6** (cascade)
   - `processed` = **6**

---

### TEST CASE 10: Access Control Validation

**Objective**: Memastikan hanya role yang tepat dapat mengakses fitur.

**Test Matrix**:

| Action | team_verifikator_lvl1 | team_verifikator_lvl2 | spv2 | staff |
|--------|:---------------------:|:---------------------:|:----:|:-----:|
| View waiting-verifikator-lvl1-recommendation | ✅ | ❌ | ❌ | ❌ |
| View waiting-verifikator-lvl2 | ❌ | ✅ | ❌ | ❌ |
| Submit Verif1 recommendation | ✅ | ❌ | ❌ | ❌ |
| Submit Verif2 decision | ❌ | ✅ | ❌ | ❌ |
| Upload file bukti verifikator | ✅ | ❌ | ❌ | ❌ |
| View documents ID 30,31,32 | ✅ | ✅ | ❌ | ✅ |

**Steps**:
1. Login dengan setiap role
2. Coba akses setiap URL/action
3. Verify hasilnya match matrix di atas

---

## Integration Testing

### END-TO-END Test: Complete Workflow

**Objective**: Test full flow dari awal sampai akhir.

**Duration**: ~15 minutes

**Steps**:

1. **STAFF**: Upload loan application
   - Produk: KUPEG
   - Date: 2025-10-28 (after cutoff)
   - Mark `need_verifikator = 1`

2. **SPV2**: Approve loan
   - Set `final_status_spv2 = 1` (Verify)
   - Add comment

3. **VERIF1-A**: Lock attempt
   - User A klik Show
   - Verify lock created

4. **VERIF1-B**: Lock conflict
   - User B klik Show
   - Verify error message

5. **VERIF1-A**: Submit recommendation
   - Pilih APPROVE
   - Verify `final_status_verif1 = 5`

6. **VERIF2**: Submit decision
   - Pilih APPROVE
   - Verify `final_status_verif2 = 5`

7. **VERIF1-A**: Upload file bukti
   - Upload PDF file
   - Verify file saved
   - Verify `final_status = 1`, `processed = 1`

8. **ANY USER**: View document link
   - Buka loan
   - Klik link dokumen verifikasi
   - Verify PDF terbuka

**Expected Total Time**:
- ✅ All steps complete successfully
- ✅ No errors in Laravel log
- ✅ No JavaScript errors in console

---

## Performance Testing

### Load Test: Concurrent Users

**Objective**: Test sistem dengan multiple verifikator concurrent.

**Setup**:
- 5 users: team_verifikator_lvl1
- 10 loans ready for review

**Test**:
1. Semua user login simultaneously
2. Setiap user klik Show pada loan berbeda
3. Verify: All locks created successfully

**Expected**:
- ✅ No deadlocks
- ✅ No duplicate locks
- ✅ Response time < 2 seconds

---

## Error Handling Testing

### Test Invalid States

1. **Upload file bukti sebelum Verif2 keputusan**
   - Expected: ❌ Error 400 "Verifikator Level 2 belum memberikan keputusan"

2. **Upload file bukti double**
   - Upload once (success)
   - Try upload again
   - Expected: ❌ Error 400 "File bukti sudah pernah diupload"

3. **Non-verifikator trying to upload**
   - Login as `spv2`
   - POST to `/upload-bukti-verifikator`
   - Expected: ❌ Error 403 "Access denied"

4. **Lock expired access**
   - User A creates lock
   - Wait 16 minutes
   - User A try to submit review
   - Expected: Lock auto-cleared, no error

---

## Browser Compatibility

Test pada browser:
- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Edge (latest)
- ✅ Safari (latest)

Focus:
- SweetAlert2 dialogs
- File upload progress bar
- AJAX form submission
- Console logs

---

## Regression Testing Checklist

After setiap deployment, run checklist ini:

- [ ] Loan locking works untuk verif1 dan verif2
- [ ] Flash messages muncul dengan benar
- [ ] Document filtering by role (ID 30, 31, 32) berfungsi
- [ ] Document filtering by date (ID 29) berfungsi
- [ ] Verif1 form hanya muncul untuk verif1
- [ ] Verif2 form hanya muncul untuk verif2
- [ ] Upload file bukti menggunakan AJAX (bukan GET)
- [ ] File tersimpan dengan path format yang benar
- [ ] Document link muncul di kolom Note
- [ ] Final status cascade logic benar
- [ ] No JavaScript errors di console
- [ ] No PHP errors di Laravel log

---

## Known Issues & Limitations

1. **Lock Duration**: Fixed 15 minutes, tidak bisa dikonfigurasi
2. **File Size**: Max 2MB untuk file bukti
3. **File Format**: Hanya PDF, JPG, JPEG, PNG
4. **Browser Compatibility**: SweetAlert2 requires modern browser
5. **CDN Dependency**: SweetAlert2 loaded dari CDN (requires internet)

---

## Troubleshooting Guide

### Issue: Form submit refresh page dengan parameter di URL

**Symptoms**: URL shows `?_token=...&file_bukti=...&loan_app_no=...`

**Cause**: JavaScript handler tidak attach

**Debug Steps**:
1. Open browser console (F12)
2. Check logs:
   - "Upload File Bukti script loaded" ✅
   - "Form element exists: 1" ✅
   - "Swal available: true" ✅
3. If not present: SweetAlert2 not loaded
4. Check network tab for `sweetalert2` CDN load

**Fix**: Verify SweetAlert2 script loaded BEFORE form handler

---

### Issue: Upload gagal dengan error 419

**Symptoms**: AJAX returns 419 Page Expired

**Cause**: CSRF token invalid

**Fix**: Refresh halaman, CSRF token auto-regenerated

---

### Issue: File tidak tersimpan

**Debug**:
```sql
-- Check file_bukti_verifikator
SELECT file_bukti_verifikator FROM data_file WHERE loan_app_no = 'XXX';

-- Check detail_file
SELECT * FROM detail_file WHERE loan_app_no = 'XXX' AND alias = 'BUKTI_VERIFIKATOR';

-- Check file existence
ls -la /home/user/dms/public/indexed/{branch_code}/{YYYY}/{MM}/{DD}/
```

---

### Issue: Lock tidak release

**Debug**:
```sql
-- Check active locks
SELECT * FROM list_pickup WHERE status = 1;

-- Manual release
UPDATE list_pickup SET status = 0 WHERE loan_app_no = 'XXX';
```

---

## Test Data Generator

### SQL Script untuk Generate Test Data

```sql
-- Create test loan
INSERT INTO data_file (
    loan_app_no,
    date_input,
    need_verifikator,
    final_status_spv2,
    final_status_verif1,
    final_status_verif2,
    processed
) VALUES (
    'TEST_VERIF_001',
    '2025-10-28 10:00:00',
    1,
    1,
    0,
    0,
    0
);

-- Create another for Verif2 stage
INSERT INTO data_file (
    loan_app_no,
    date_input,
    need_verifikator,
    final_status_spv2,
    final_status_verif1,
    final_status_verif2,
    user_verif1,
    date_flag_verif1
) VALUES (
    'TEST_VERIF_002',
    '2025-10-28 11:00:00',
    1,
    1,
    5,
    0,
    '1234567890',
    NOW()
);

-- Create for upload stage
INSERT INTO data_file (
    loan_app_no,
    date_input,
    need_verifikator,
    final_status_spv2,
    final_status_verif1,
    final_status_verif2,
    user_verif1,
    user_verif2,
    date_flag_verif1,
    date_flag_verif2,
    file_bukti_verifikator
) VALUES (
    'TEST_VERIF_003',
    '2025-10-28 12:00:00',
    1,
    1,
    5,
    5,
    '1234567890',
    '0987654321',
    NOW(),
    NOW(),
    NULL
);
```

---

## Reporting Template

### Bug Report Format

```
TITLE: [Component] Brief description

PRIORITY: Critical / High / Medium / Low

ENVIRONMENT:
- Browser: Chrome 120.0
- OS: Windows 11
- Server: Laravel 8.x
- Database: MySQL 8.0

STEPS TO REPRODUCE:
1. Login as team_verifikator_lvl1
2. Navigate to /waiting-verifikator-lvl1-recommendation
3. Click Show button
4. ...

EXPECTED RESULT:
Lock should be created and form displayed

ACTUAL RESULT:
Error 500 Internal Server Error

LOGS:
[2025-11-13 10:30:45] production.ERROR: ...

SCREENSHOTS:
[Attach screenshots]

RELATED TEST CASE: TEST CASE 1
```

---

## Appendix

### A. Database Schema Reference

**data_file table (relevant columns)**:
```
loan_app_no (PK)
date_input
need_verifikator (tinyint)
final_status_spv1
final_status_spv2
final_status_spv3
final_status_verif1
final_status_verif2
user_verif1
user_verif2
date_flag_verif1
date_flag_verif2
file_bukti_verifikator
final_status
processed
```

**list_pickup table**:
```
id (PK)
loan_app_no
nik
status (0=inactive, 1=active)
expired_at
created_at
updated_at
```

**detail_file table**:
```
id (PK)
loan_app_no
file (path)
alias
branch_dir
created_at
```

### B. Route Reference

```php
// List pages
GET  /waiting-verifikator-lvl1-recommendation
GET  /waiting-verifikator-lvl2

// Detail page
GET  /show-new/{loan_app_no}?lock=1

// Upload
POST /upload-bukti-verifikator

// View file
GET  /indexed/{branch}/{YYYY}/{MM}/{DD}/{filename}
```

### C. Status Codes Reference

| Code | Meaning | Used In |
|------|---------|---------|
| 0 | Pending/Not Yet | All status fields |
| 1 | Verify | final_status_spv*, final_status |
| 3 | TBO | final_status_spv*, final_status |
| 5 | Approve | final_status_verif* |
| 6 | Not Approve | final_status_verif*, final_status |

---

## Contact & Support

For issues or questions:
- Developer: Claude Code
- Documentation: CLAUDE.md, TESTING_VERIFIKATOR.md
- GitHub Issues: [Repository URL]

---

**Document Version**: 1.0
**Last Updated**: 2025-11-13
**Author**: Claude Code Assistant
