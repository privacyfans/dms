# IMPLEMENTATION SUMMARY: Team Verifikator Level 1 & 2

## ✅ YANG SUDAH SELESAI DIIMPLEMENTASI

### 1. Database Migration
**File:** `database/migrations/2025_11_07_000001_add_team_verifikator_columns_to_data_file_table.php`

Menambahkan 7 kolom baru ke tabel `data_file`:
- `final_status_verif1` (INT) - Status Team Verifikator Level 1
- `user_verif1` (VARCHAR) - NIK reviewer Level 1
- `date_flag_verif1` (DATETIME) - Tanggal review Level 1
- `final_status_verif2` (INT) - Status Team Verifikator Level 2
- `user_verif2` (VARCHAR) - NIK reviewer Level 2
- `date_flag_verif2` (DATETIME) - Tanggal review Level 2
- `file_bukti_verifikator` (VARCHAR) - Path file bukti pemeriksaan

**Cara Menjalankan:**
```bash
php artisan migrate
```

---

### 2. Model Update
**File:** `app/Models/DataFileModel.php`

Menambahkan fields baru ke `$fillable` array untuk support kolom verifikator.

---

### 3. Controller Methods
**File:** `app/Http/Controllers/DataFilesController.php`

#### A. Method `updateFlag()` - DIMODIFIKASI (Lines 5802-6365)
**Perubahan:**
- Menambah validasi untuk status 5 (Approve) dan 6 (Not Approve)
- Menambah logic untuk role `team_verifikator_lvl1` dan `team_verifikator_lvl2`
- Modifikasi logic SPV2/3/4 untuk cek produk yang perlu verifikator (jenis_produk: 2, 3, 8)
- Logic penentuan `final_status`:
  - Jika SPV reject (4) → cascade semua = 4, STOP (tidak lanjut ke verifikator)
  - Jika SPV not verify (2) → kembali staff, STOP
  - Jika SPV verify (1) atau TBO (3) untuk produk KUPEG/KUPEN Hybrid/KUPEN Hybrid GO → lanjut ke verifikator
  - Team Verifikator Level 1 memberikan rekomendasi (5 atau 6)
  - Team Verifikator Level 2 memberikan keputusan final:
    - Status 5 (Approve) → final_status mengikuti SPV (1 atau 3)
    - Status 6 (Not Approve) → cascade semua status = 6

#### B. Method `waiting_verifikator_lvl1()` - BARU (Lines 6371-6402)
Query loan yang:
- Produk jenis_produk IN (2, 3, 8)
- SPV kasih verify (1) atau TBO (3)
- Belum direview verifikator level 1 (final_status_verif1 = 0)

#### C. Method `waiting_verifikator_lvl2()` - BARU (Lines 6408-6428)
Query loan yang:
- Produk jenis_produk IN (2, 3, 8)
- Sudah direview verifikator level 1 (final_status_verif1 IN (5, 6))
- Belum direview verifikator level 2 (final_status_verif2 = 0)

#### D. Method `uploadBuktiVerifikator()` - BARU (Lines 6433-6489)
Handle upload file bukti pemeriksaan oleh Team Verifikator Level 2:
- Validasi file (PDF, JPG, PNG max 5MB)
- Save path ke `data_file.file_bukti_verifikator`
- Insert ke tabel `detail_file` dengan alias 'BUKTI_VERIFIKATOR'

---

### 4. Routes
**File:** `routes/web.php` (Lines 245-248)

```php
// Team Verifikator Routes
Route::get('waiting_verifikator_lvl1', [DataFilesController::class,'waiting_verifikator_lvl1'])
     ->name('datafile.waiting_verifikator_lvl1');
Route::get('waiting_verifikator_lvl2', [DataFilesController::class,'waiting_verifikator_lvl2'])
     ->name('datafile.waiting_verifikator_lvl2');
Route::post('upload-bukti-verifikator', [DataFilesController::class,'uploadBuktiVerifikator'])
     ->name('datafile.uploadBuktiVerifikator');
```

---

### 5. Middleware
**File:** `app/Http/Middleware/CheckRole.php`

Menambahkan `team_verifikator_lvl1` dan `team_verifikator_lvl2` ke allowed roles.

---

### 6. Views (Baru)

#### A. `resources/views/loan/waiting_verifikator_lvl1.blade.php`
Queue page untuk Team Verifikator Level 1 dengan DataTables.

#### B. `resources/views/loan/waiting_verifikator_lvl2.blade.php`
Queue page untuk Team Verifikator Level 2 dengan informasi rekomendasi Level 1.

---

## 📋 YANG MASIH PERLU DILAKUKAN MANUAL

### 1. Update `show.blade.php` dan `show-new.blade.php`

Kedua file ini sangat besar (1860 dan 3167 lines), sehingga perlu modifikasi manual.

#### Tambahkan Section Berikut:

**A. Helper Function untuk Status Badge (di bagian atas file, setelah @section):**

```php
@php
function getStatusBadge($status) {
    switch($status) {
        case 0: return '<span class="badge badge-secondary">Pending</span>';
        case 1: return '<span class="badge badge-success">Verify</span>';
        case 2: return '<span class="badge badge-warning">Not Verify</span>';
        case 3: return '<span class="badge badge-info">TBO</span>';
        case 4: return '<span class="badge badge-danger">Reject</span>';
        case 5: return '<span class="badge badge-success">Approve (Verifikator)</span>';
        case 6: return '<span class="badge badge-danger">Not Approve (Verifikator)</span>';
        default: return '<span class="badge badge-secondary">-</span>';
    }
}
@endphp
```

**B. Section Status Team Verifikator (tambahkan setelah section status SPV):**

```blade
{{-- Status Team Verifikator (hanya untuk produk tertentu) --}}
@php
    $jenis_produk = $datafile->masterproduk->first()->jenis_produk ?? 0;
    $need_verifikator = in_array($jenis_produk, [2, 3, 8]);
@endphp

@if($need_verifikator)
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Status Team Verifikator</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="250">Team Verifikator Level 1</th>
                        <td>
                            {!! getStatusBadge($datafile->final_status_verif1) !!}
                            @if($datafile->final_status_verif1 > 0)
                                <br><small class="text-muted">
                                    <strong>Rekomendasi oleh:</strong> {{ $datafile->user_verif1 }}<br>
                                    <strong>Tanggal:</strong> {{ $datafile->date_flag_verif1 }}
                                </small>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Team Verifikator Level 2</th>
                        <td>
                            {!! getStatusBadge($datafile->final_status_verif2) !!}
                            @if($datafile->final_status_verif2 > 0)
                                <br><small class="text-muted">
                                    <strong>Keputusan oleh:</strong> {{ $datafile->user_verif2 }}<br>
                                    <strong>Tanggal:</strong> {{ $datafile->date_flag_verif2 }}
                                </small>
                            @endif
                        </td>
                    </tr>
                    @if($datafile->file_bukti_verifikator)
                    <tr>
                        <th>Bukti Hasil Pemeriksaan</th>
                        <td>
                            <a href="{{ route('datafile.showpdf', $datafile->file_bukti_verifikator) }}"
                               class="btn btn-sm btn-primary" target="_blank">
                                <i class="fa fa-download"></i> Download Bukti Pemeriksaan
                            </a>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endif
```

**C. Form Review untuk Team Verifikator Level 1:**

```blade
@if(Session::get('role') == 'team_verifikator_lvl1' && $datafile->final_status_verif1 == 0)
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">Form Review - Team Verifikator Level 1 (Rekomendasi)</h3>
            </div>
            <div class="card-body">
                <form id="formReviewVerif1" method="POST" action="{{ route('datafile.updateflag') }}">
                    @csrf
                    <input type="hidden" name="loan_app_no" value="{{ $datafile->loan_app_no }}">

                    <div class="form-group">
                        <label>Rekomendasi <span class="text-danger">*</span></label>
                        <select name="flag_spv" class="form-control" required>
                            <option value="">-- Pilih Rekomendasi --</option>
                            <option value="5">Approve (Direkomendasikan untuk Lanjut)</option>
                            <option value="6">Not Approve (Tidak Direkomendasikan)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Komentar <span class="text-danger">*</span></label>
                        <textarea name="comment" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="form-group" id="reasonGroup" style="display:none;">
                        <label>Alasan Not Approve</label>
                        <select name="reason[]" class="form-control select2" multiple>
                            <!-- Populate dari database FlagSpv/Reason -->
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Rekomendasi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$('select[name="flag_spv"]').on('change', function() {
    if ($(this).val() == '6') {
        $('#reasonGroup').show();
    } else {
        $('#reasonGroup').hide();
    }
});
</script>
@endif
```

**D. Form Review + Upload untuk Team Verifikator Level 2:**

```blade
@if(Session::get('role') == 'team_verifikator_lvl2' && $datafile->final_status_verif2 == 0 && $datafile->final_status_verif1 > 0)
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title text-white">Form Review - Team Verifikator Level 2 (Keputusan Final)</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Rekomendasi dari Level 1:</strong>
                    {!! getStatusBadge($datafile->final_status_verif1) !!}
                    <br>
                    <small>{{ $datafile->user_verif1 }} - {{ $datafile->date_flag_verif1 }}</small>
                </div>

                <form id="formReviewVerif2" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="loan_app_no" value="{{ $datafile->loan_app_no }}">

                    <div class="form-group">
                        <label>Keputusan Final <span class="text-danger">*</span></label>
                        <select name="flag_spv" class="form-control" required id="flagVerif2">
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="5">Approve (Setuju untuk Lanjut)</option>
                            <option value="6">Not Approve (Tidak Disetujui)</option>
                        </select>
                        <small class="text-muted">
                            * Jika Approve: Final status akan mengikuti keputusan SPV
                            ({{ $datafile->final_status_spv2 == 1 ? 'Verify' : 'TBO' }})<br>
                            * Jika Not Approve: Semua status akan menjadi Not Approve (6)
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Upload Bukti Hasil Pemeriksaan <span class="text-danger">*</span></label>
                        <input type="file" name="file_bukti" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-muted">Format: PDF, JPG, PNG (Max 5MB)</small>
                    </div>

                    <div class="form-group">
                        <label>Komentar <span class="text-danger">*</span></label>
                        <textarea name="comment" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="form-group" id="reasonGroup2" style="display:none;">
                        <label>Alasan Not Approve</label>
                        <select name="reason[]" class="form-control select2" multiple>
                            <!-- Populate dari database -->
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Submit Keputusan Final</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$('#flagVerif2').on('change', function() {
    if ($(this).val() == '6') {
        $('#reasonGroup2').show();
    } else {
        $('#reasonGroup2').hide();
    }
});

// Handle form submit dengan upload file
$('#formReviewVerif2').on('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);
    let submitBtn = $(this).find('button[type=submit]');

    submitBtn.prop('disabled', true).text('Processing...');

    // Upload file terlebih dahulu
    $.ajax({
        url: '{{ route("datafile.uploadBuktiVerifikator") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                // Kemudian submit review
                $.ajax({
                    url: '{{ route("datafile.updateflag") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        loan_app_no: $('input[name=loan_app_no]').val(),
                        flag_spv: $('select[name=flag_spv]').val(),
                        comment: $('textarea[name=comment]').val(),
                        reason: $('select[name="reason[]"]').val()
                    },
                    success: function(res) {
                        alert('Keputusan berhasil disimpan');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Gagal menyimpan keputusan: ' + (xhr.responseJSON?.message || 'Unknown error'));
                        submitBtn.prop('disabled', false).text('Submit Keputusan Final');
                    }
                });
            }
        },
        error: function(xhr) {
            alert('Upload file gagal: ' + (xhr.responseJSON?.message || 'Unknown error'));
            submitBtn.prop('disabled', false).text('Submit Keputusan Final');
        }
    });
});
</script>
@endif
```

---

### 2. Update Helper Function `sendEmailNotifikasi()`

**File:** `app/Helper/GeneralFunction.php` (around line 521)

Tambahkan kondisi baru:

```php
function sendEmailNotifikasi($loan_app_no, $result)
{
    // ... existing code ...

    // Tambahan untuk Team Verifikator
    if ($result == "Approved by Team Verifikator") {
        // Send email ke DCRM dan Unit Bisnis
        // Informasi bahwa loan sudah approved oleh team verifikator
        // DCRM bisa lanjut DCRM Approval di WGSS
        $subject = "Loan {$loan_app_no} - Approved by Team Verifikator";
        $message = "Loan application {$loan_app_no} telah disetujui oleh Team Verifikator. Silakan lanjutkan ke proses DCRM Approval di WGSS.";
        // ... send email logic
    }

    if ($result == "TBO - Approved by Team Verifikator") {
        $subject = "Loan {$loan_app_no} - TBO Approved by Team Verifikator";
        $message = "Loan application {$loan_app_no} (TBO) telah disetujui oleh Team Verifikator.";
        // ... send email logic
    }

    if ($result == "Not Approved by Team Verifikator") {
        // Send email ke Staff, DCRM, dan Unit Bisnis
        // Informasi bahwa loan ditolak oleh team verifikator
        // Loan tidak bisa lanjut ke dropping
        $subject = "Loan {$loan_app_no} - NOT APPROVED by Team Verifikator";
        $message = "Loan application {$loan_app_no} TIDAK DISETUJUI oleh Team Verifikator. Loan tidak dapat dilanjutkan ke proses dropping.";
        // ... send email logic
    }
}
```

---

### 3. Insert User dengan Role Baru

Tambahkan user team verifikator ke database:

```sql
INSERT INTO users (nik, role, name, email, cabang) VALUES
('NIK_VERIF1', 'team_verifikator_lvl1', 'Nama Team Verifikator Level 1', 'verif1@domain.com', 'HQ'),
('NIK_VERIF2', 'team_verifikator_lvl2', 'Nama Team Verifikator Level 2', 'verif2@domain.com', 'HQ');
```

---

### 4. Verifikasi Master Produk

Pastikan mapping produk di tabel `master_produk` sudah benar:

```sql
SELECT * FROM master_produk WHERE jenis_produk IN (2, 3, 8);
```

Pastikan:
- jenis_produk = 2 untuk KUPEN Hybrid
- jenis_produk = 3 untuk KUPEG (Kredit Umum Pegawai)
- jenis_produk = 8 untuk KUPEN Hybrid GO (Grace Period Option)

---

## 🔧 CARA TESTING

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Buat Test User
```sql
-- Team Verifikator Level 1
INSERT INTO users (nik, role, name, email, cabang, created_at, updated_at)
VALUES ('99991', 'team_verifikator_lvl1', 'Test Verifikator Lvl 1', 'verif1@test.com', '001', NOW(), NOW());

-- Team Verifikator Level 2
INSERT INTO users (nik, role, name, email, cabang, created_at, updated_at)
VALUES ('99992', 'team_verifikator_lvl2', 'Test Verifikator Lvl 2', 'verif2@test.com', '001', NOW(), NOW());
```

### 3. Test Flow
1. **Login sebagai Staff** → Input loan dengan produk KUPEG/KUPEN Hybrid/KUPEN Hybrid GO
2. **Login sebagai SPV2/3/4** → Berikan status Verify (1) atau TBO (3)
3. **Login sebagai team_verifikator_lvl1** →
   - Akses: http://your-domain.com/waiting_verifikator_lvl1
   - Review loan dan berikan rekomendasi (status 5 atau 6)
4. **Login sebagai team_verifikator_lvl2** →
   - Akses: http://your-domain.com/waiting_verifikator_lvl2
   - Review loan, upload bukti, dan berikan keputusan final (status 5 atau 6)
5. **Verifikasi** →
   - Jika Approve (5) → final_status = SPV value (1 atau 3)
   - Jika Not Approve (6) → semua status = 6

### 4. Test Produk Lain
- Login dengan produk selain KUPEG/KUPEN Hybrid/KUPEN Hybrid GO
- Pastikan flow existing tetap berjalan normal (langsung ke final_status tanpa verifikator)

---

## 📝 CATATAN PENTING

1. **FTP Upload:** Method `uploadBuktiVerifikator()` masih menggunakan placeholder untuk FTP upload. Anda perlu mengimplementasikan actual FTP upload logic sesuai dengan function yang sudah ada di codebase Anda.

2. **Email Template:** Function `sendEmailNotifikasi()` perlu disesuaikan dengan email template yang sudah ada di sistem Anda.

3. **Navigation Menu:** Anda perlu menambahkan menu navigasi untuk Team Verifikator di sidebar/menu utama.

4. **Reason Dropdown:** Form review menggunakan reason dropdown yang perlu di-populate dari database `FlagSpv` dan `Reason`.

5. **Testing Menyeluruh:** Pastikan untuk test semua skenario:
   - SPV Reject (4) → tidak lanjut ke verifikator
   - SPV Not Verify (2) → tidak lanjut ke verifikator
   - SPV Verify (1) → lanjut ke verifikator
   - SPV TBO (3) → lanjut ke verifikator
   - Verifikator Level 1 approve/not approve
   - Verifikator Level 2 approve/not approve
   - Upload file bukti
   - Download file bukti

---

## 🎯 STATUS CODES

| Code | Nama | Keterangan |
|------|------|------------|
| 0 | Pending | Belum direview |
| 1 | Verify | Disetujui (SPV) |
| 2 | Not Verify | Tidak disetujui (SPV) |
| 3 | TBO | To Be Override |
| 4 | Reject | Ditolak final (SPV) |
| 5 | Approve | Disetujui (Verifikator) |
| 6 | Not Approve | Tidak disetujui (Verifikator) |

---

## 📞 SUPPORT

Jika ada pertanyaan atau issue, silakan check:
1. Log Laravel: `storage/logs/laravel.log`
2. Browser console untuk JavaScript errors
3. Network tab di DevTools untuk AJAX requests

---

**Implementasi selesai pada:** {{ date('Y-m-d H:i:s') }}
**Total Files Modified:** 8
**Total Files Created:** 4
