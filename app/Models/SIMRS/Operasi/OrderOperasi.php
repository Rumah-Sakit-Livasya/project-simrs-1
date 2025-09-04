<?php

namespace App\Models\SIMRS\Operasi;

use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderOperasi extends Model
{
    use SoftDeletes;

    protected $table = 'order_operasi';
    protected $guarded = ['id'];

    protected $casts = [
        'tgl_operasi' => 'datetime'
    ];

    // Relasi ke registrasi
    public function registration()
    {
        return $this->belongsTo(\App\Models\SIMRS\Registration::class, 'registration_id');
    }

    // Relasi ke dokter DPJP (biarkan jika memang ada kolomnya)
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    // ==========================================================
    // INI ADALAH BAGIAN YANG DIPERBAIKI
    // ==========================================================
    public function doctorOperator()
    {
        // Pastikan nama foreign key ini sama persis dengan nama kolom di migrasi
        return $this->belongsTo(Doctor::class, 'dokter_operator_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Sesuaikan dengan nama kolom di database
    }

    // Relasi ke jenis operasi
    public function tipeOperasi()
    {
        return $this->belongsTo(TipeOperasi::class, 'tipe_operasi_id');
    }

    public function jenisOperasi()
    {
        return $this->belongsTo(JenisOperasi::class, 'jenis_operasi_id');
    }

    // Relasi ke kategori operasi
    public function kategoriOperasi()
    {
        return $this->belongsTo(KategoriOperasi::class, 'kategori_operasi_id');
    }

    // Relasi ke prosedur operasi
    public function prosedurOperasi()
    {
        return $this->hasMany(ProsedurOperasi::class, 'order_operasi_id');
    }

    // Relasi ke kelas rawat
    public function kelasRawat()
    {
        return $this->belongsTo(\App\Models\SIMRS\KelasRawat::class, 'kelas_rawat_id');
    }

    // Relasi ke ruangan
    public function ruangan()
    {
        return $this->belongsTo(Room::class, 'ruangan_id');
    }
}
