<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Echocardiography extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'echocardiographies';
    protected $guarded = ['id'];

    protected $casts = [
        'aorta' => 'array',
        'left_atrium' => 'array',
        'right_ventricle' => 'array',
        'left_ventricle' => 'array',
        'mitral_valve' => 'array',
        'other_valves' => 'array',
        'pericardial_effusion' => 'array',
        'comments' => 'array',
    ];

    /**
     * Relasi polimorfik untuk tanda tangan.
     */
    public function signatures()
    {
        return $this->morphMany(Signature::class, 'signable');
    }
}
