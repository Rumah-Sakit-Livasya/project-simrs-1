<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanInternal extends Model
{
    use SoftDeletes;
    protected $table = 'laporan_internal';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Relasi untuk unit_terkait_name (organisasi yang terkait dengan laporan).
     * Mengembalikan nama organisasi terkait dengan laporan.
     */
    public function unit_terkait_name()
    {
        // Asumsi bahwa `unit_terkait_name` adalah ID organisasi yang terkait
        return $this->belongsTo(Organization::class, 'unit_terkait', 'id');
    }

    /**
     * Method untuk mengambil nama organisasi yang terkait dengan unit_terkait_name.
     * Menggunakan relasi unit_terkait_name untuk mendapatkan nama organisasi.
     */
    public function getUnitTerkaitName()
    {
        // Mengembalikan nama organisasi yang terkait dengan unit_terkait_name
        return $this->unit_terkait_name ? $this->unit_terkait_name->name : '-';
    }
}
