<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkriningGiziDewasa extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'skrining_gizi_dewasa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_id',
        'diagnosa_medis',
        'bb',
        'tb',
        'imt',
        'tinggi_lutut',
        'lla',
        'skor1',
        'skor2',
        'skor3',
        'hasil_skor',
        'analisis_skor',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'bb' => 'decimal:2',
        'tb' => 'decimal:2',
        'imt' => 'decimal:2',
        'tinggi_lutut' => 'decimal:2',
        'lla' => 'decimal:2',
    ];
}
