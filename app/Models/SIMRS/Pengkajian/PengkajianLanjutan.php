<?php

namespace App\Models\SIMRS\Pengkajian;

use App\Models\SIMRS\Registration;
use App\Models\User; // <-- PENTING: Import model User
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- TAMBAHKAN: Untuk praktik terbaik
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengkajianLanjutan extends Model
{
    // ------------------- TAMBAHKAN TRAITS INI -------------------
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel database.
     *
     * @var string
     */
    protected $table = 'pengkajian_lanjutan';

    /**
     * Atribut yang dikecualikan dari mass assignment.
     * Menggunakan $guarded adalah alternatif dari $fillable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     * Ini akan secara otomatis mengubah tipe data dari/ke database.
     * PENTING untuk mencegah error "Array to string conversion".
     *
     * @var array<string, string>
     */
    protected $casts = [
        'form_values' => 'array', // <-- TAMBAHKAN BARIS INI
        'is_final'    => 'boolean', // <-- Ini juga praktik yang baik
    ];
    // ------------------- DEFINISIKAN RELASI ANDA -------------------

    /**
     * Relasi ke model Registration.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    /**
     * Relasi ke model FormTemplate.
     */
    public function form_template()
    {
        return $this->belongsTo(FormTemplate::class);
    }

    /**
     * Relasi ke model User (untuk user_id).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'modified_by', 'id');
    }
}
