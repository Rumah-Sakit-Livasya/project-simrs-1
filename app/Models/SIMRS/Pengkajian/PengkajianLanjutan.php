<?php

namespace App\Models\SIMRS\Pengkajian;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;

class PengkajianLanjutan extends Model
{
    protected $table = 'pengkajian_lanjutan', $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
    
    public function form_template()
    {
        return $this->belongsTo(FormTemplate::class);
    }
}
