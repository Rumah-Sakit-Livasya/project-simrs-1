<?php

namespace App\Models\Inventaris;

use App\Models\Organization;
use App\Models\SurveiKebersihanKamar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomMaintenance extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'room_maintenance';

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }

    public function reportBarang()
    {
        return $this->hasMany(ReportBarang::class);
    }

    public function survei_kebersihan_kamar()
    {
        return $this->hasMany(SurveiKebersihanKamar::class);
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'room_maintenance_organization');
    }
}
