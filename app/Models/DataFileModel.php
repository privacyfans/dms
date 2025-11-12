<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataFileModel extends Model
{
    use HasFactory;

    protected $table = 'data_file';
    protected $primaryKey = 'loan_app_no';
    public $timestamps = false;
    const CREATED_AT = 'date_input';
    protected $keyType = 'string';
    protected $fillable = [
        'modul',
        'kode_cabang',
        'loan_app_no',
        'no_cif',
        'no_ktp',
        'nama_debitur',
        'ttl',
        'alamat_rumah',
        'no_tlp_rumah',
        'instansi',
        'alamat_kantor',
        'no_tlp_kantor',
        'plafond',
        'jangka_waktu',
        'rate',
        'angsuran',
        'tanggal_jatuh_tempo',
        'produk',
        'user_input',
        'branch_input',
        'date_input',
        'user_spv1',
        'final_status_spv1',
        'date_flag_spv1',
        'user_spv2',
        'final_status_spv2',
        'date_flag_spv2',
        'user_spv3',
        'final_status_spv3',
        'date_flag_spv3',
        // Team Verifikator fields
        'final_status_verif1',
        'user_verif1',
        'date_flag_verif1',
        'final_status_verif2',
        'user_verif2',
        'date_flag_verif2',
        'file_bukti_verifikator',
        // End Team Verifikator fields
        'final_status',
        'processed',
        'status_pernikahan',
        'pekerjaan',
        'fasilitas',
        'early',
        'take_over',
        'take_over_hari_ini',
        'status_usia_debitur',
        'status_deviasi',
        'status_kantor',
        'status_pegawai',
        'status_fronting_agent'
    ];

    public function masterproduk()
    {
        return $this->hasMany(MasterProduct::class,'produk','produk');
    }

    public function mastercabang()
    {
        return $this->hasMany(Branchlist::class,'branch_code','kode_cabang');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class,'loan_app_no','loan_app_no');
    }
}
