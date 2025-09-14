<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoLocation extends Model
{
    use HasFactory;

    /**
     * Menentukan nama tabel yang digunakan oleh model.
     *
     * @var string
     */
    protected $table = 'geo_locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'province',
        'city',
        'address',
        'longitude',
        'latitude',
        'google_maps_api_key',
    ];
}
