<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapPenilaianBulanan extends Model
{
    use HasFactory;
    protected $table = 'rekap_penilaian';
    protected $fillable = ['employee_id', 'group_penilaian_id', 'tahun', 'total_nilai', 'keterangan_ya', 'keterangan_tidak', 'is_ya', 'is_tidak', 'keterangan', 'is_verified_penilai', 'is_verified_pejabat_penilai', 'is_verified_pegawai', 'is_verified_hrd', 'is_verified_direktur'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function group_penilaian()
    {
        return $this->belongsTo(GroupPenilaian::class, 'group_penilaian_id');
    }
}
