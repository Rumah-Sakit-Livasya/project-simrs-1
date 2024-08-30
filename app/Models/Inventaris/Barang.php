<?php

namespace App\Models\Inventaris;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'barang';

    public function room()
    {
        return $this->belongsTo(RoomMaintenance::class);
    }

    public function template_barang()
    {
        return $this->belongsTo(TemplateBarang::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function maintenance()
    {
        return $this->hasMany(MaintenanceBarang::class);
    }

    public function reportBarang()
    {
        return $this->hasMany(ReportBarang::class);
    }
}
