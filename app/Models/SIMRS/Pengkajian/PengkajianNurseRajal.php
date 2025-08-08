<?php

namespace App\Models\SIMRS\Pengkajian;

use App\Models\Signature;
use App\Models\SIMRS\Registration; // Pastikan path ke model Registration benar
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class PengkajianNurseRajal extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, SoftDeletes, Auditable;

    /**
     * Atribut yang dikecualikan dari mass assignment.
     * Menggunakan $guarded adalah alternatif dari $fillable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Nama tabel database.
     *
     * @var string
     */
    protected $table = 'pengkajian_nurse_rajal';

    /**
     * The attributes that should be cast.
     * Ini adalah bagian penting untuk secara otomatis mengubah data ke tipe yang benar.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // --- Tanggal & Waktu ---
        'tgl_masuk' => 'date',
        'tgl_dilayani' => 'date',

        // --- Casting untuk Kolom JSON ---
        'sensorik' => 'array',               // <--- INI SOLUSINYA
        'motorik' => 'array',                // <--- INI SOLUSINYA
        'kondisi_khusus' => 'array',         // <--- INI SOLUSINYA
        'imunisasi_dasar' => 'array',        // <--- INI SOLUSINYA
        'resiko_jatuh' => 'array',           // <--- INI SOLUSINYA
        'hambatan_belajar' => 'array',       // <--- INI SOLUSINYA
        'kebutuhan_pembelajaran' => 'array', // <--- INI SOLUSINYA

        // --- Casting untuk Kolom Boolean ---
        'gelang' => 'boolean',
    ];

    /**
     * Relasi ke model Registration.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    /**
     * Relasi ke model PengkajianDokterRajal.
     * Menggunakan registration_id sebagai foreign dan local key.
     */
    public function pengkajian_dokter_rajal()
    {
        return $this->hasOne(PengkajianDokterRajal::class, 'registration_id', 'registration_id');
    }

    /**
     * Relasi polymorphic ke model Signature.
     */
    public function signature()
    {
        return $this->morphOne(Signature::class, 'signable');
    }
}
