<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPenilaian extends Model
{
    use HasFactory;
    protected $fillable = ['nama_group', 'penilai', 'pejabat_penilai'];

    public function aspek_penilaians()
    {
        return $this->hasMany(AspekPenilaian::class);
    }
    public function penilaian_pegawais()
    {
        return $this->hasMany(PenilaianPegawai::class);
    }
    public function rekap_penilaians()
    {
        return $this->hasMany(RekapPenilaianBulanan::class);
    }

    public function employee_penilai()
    {
        return $this->belongsTo(Employee::class, 'penilai');
    }
    public function employee_pejabat_penilai()
    {
        return $this->belongsTo(Employee::class, 'pejabat_penilai');
    }
}
