<?php

namespace App\Models\SIMRS\Obat;

use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Registration;
use App\Models\User;
use App\Models\WarehouseMasterGudang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderObat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_obats';
    protected $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, 'warehouse_id'); // Asumsi
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderObatDetail::class, 'order_obat_id');
    }
}
