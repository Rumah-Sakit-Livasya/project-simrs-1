<?php

namespace App\Models\SIMRS\Peralatan;

use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TindakanMedis;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class OrderAlatMedis extends Model
{
    protected $table = 'order_alat_medis';

    protected $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function alat()
    {
        return $this->belongsTo(Peralatan::class, 'peralatan_id');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
