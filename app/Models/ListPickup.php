<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ListPickup extends Model
{
    use HasFactory;

    protected $table = 'list_pickup';
    public $timestamps = false;

    protected $fillable = [
        'loan_app_no',
        'status',
        'user_spv',
        'spv_lvl',
        'nik',
        'locked_by_level',
        'locked_at',
        'expired_at',
    ];

    protected $casts = [
        'locked_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Relationship ke DataFileModel
     */
    public function dataFile()
    {
        return $this->belongsTo(DataFileModel::class, 'loan_app_no', 'loan_app_no');
    }

    /**
     * Relationship ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'nik', 'nik');
    }

    /**
     * Scope untuk filter lock yang masih aktif (belum expired)
     */
    public function scopeActiveLocks($query)
    {
        return $query->where('status', 0)
                     ->where(function($q) {
                         $q->whereNull('expired_at')
                           ->orWhere('expired_at', '>', Carbon::now());
                     });
    }

    /**
     * Scope untuk filter lock yang sudah expired
     */
    public function scopeExpiredLocks($query)
    {
        return $query->where('status', 0)
                     ->whereNotNull('expired_at')
                     ->where('expired_at', '<=', Carbon::now());
    }

    /**
     * Check apakah lock masih aktif
     */
    public function isActive()
    {
        if ($this->status != 0) {
            return false;
        }

        if (is_null($this->expired_at)) {
            return true;
        }

        return Carbon::now()->lessThan($this->expired_at);
    }

    /**
     * Check apakah lock sudah expired
     */
    public function isExpired()
    {
        if ($this->status != 0) {
            return false;
        }

        if (is_null($this->expired_at)) {
            return false;
        }

        return Carbon::now()->greaterThanOrEqualTo($this->expired_at);
    }
}
