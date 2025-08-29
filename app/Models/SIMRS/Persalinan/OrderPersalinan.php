<?php

namespace App\Models\SIMRS\Persalinan;

use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderPersalinan extends Model
{
    protected $table = 'order_persalinan';
    protected $guarded = [];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_entry_id');
    }
    public function dokterBidan()
    {
        return $this->belongsTo(Doctor::class, 'dokter_bidan_operator_id');
    }
    public function dokterResusitator()
    {
        return $this->belongsTo(Doctor::class, 'dokter_resusitator_id');
    }
    public function persalinan()
    {
        return $this->belongsTo(Persalinan::class, 'persalinan_id', 'id');
    }

    public function dokterAnestesi()
    {
        return $this->belongsTo(Doctor::class, 'dokter_anestesi_id');
    }
    public function dokterUmum()
    {
        return $this->belongsTo(Doctor::class, 'dokter_umum_id');
    }
    public function asistenOperator()
    {
        return $this->belongsTo(Doctor::class, 'asisten_operator_id');
    }
    public function asistenAnestesi()
    {
        return $this->belongsTo(Doctor::class, 'asisten_anestesi_id');
    }

    public function kelasRawat()
    {
        return $this->belongsTo(KelasRawat::class);
    }
    public function kategori()
    {
        return $this->belongsTo(KategoriPersalinan::class, 'kategori_id');
    }
    public function tipePersalinan()
    {
        return $this->belongsTo(TipePersalinan::class, 'tipe_penggunaan_id');
    }

    public function details()
    {
        return $this->hasMany(OrderPersalinanDetail::class);
    }
}
