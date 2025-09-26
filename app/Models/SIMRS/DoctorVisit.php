<?php

namespace App\Models\SIMRS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorVisit extends Model
{
    use HasFactory;

    protected $table = 'doctor_visits';

    protected $fillable = [
        'registration_id',
        'doctor_id',
        'kelas_rawat_id', // Telah diubah
        'user_id',
        'visit_date',
        'is_billed',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'is_billed' => 'boolean',
    ];

    /**
     * Relasi ke model Registration.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Relasi ke model Doctor.
     * Asumsi: Model Doctor memiliki relasi 'employee' untuk mendapatkan nama lengkap.
     */
    public function doctor(): BelongsTo
    {
        // Ganti namespace jika model Doctor Anda berada di lokasi lain
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Relasi ke model KelasRawat.
     */
    public function kelas_rawat(): BelongsTo
    {
        return $this->belongsTo(KelasRawat::class, 'kelas_rawat_id');
    }

    /**
     * Relasi ke model User (untuk pencatat).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tagihan_pasien()
    {
        return $this->hasOne(TagihanPasien::class); // Ganti namespace jika perlu
    }
}
