<?php

namespace App\Models\Inventaris;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Barang extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

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
