<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianPegawai extends Model
{
    use HasFactory;
    protected $fillable = ['employee_id', 'group_penilaian_id', 'indikator_penilaian_id','pejabat_penilai', 'penilai', 'nilai', 'tahun', 'file'];

    public function group_penilaian()
    {
        return $this->belongsTo(GroupPenilaian::class, 'group_penilaian_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function indikator_penilaian()
    {
        return $this->belongsTo(IndikatorPenilaian::class, 'employee_id');
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
