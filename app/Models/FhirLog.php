<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SIMRS\Registration; // Pastikan path ini benar

class FhirLog extends Model
{
    use HasFactory;

    protected $table = 'fhir_logs';
    protected $guarded = ['id'];

    /**
     * Mendefinisikan relasi ke model Registration.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
