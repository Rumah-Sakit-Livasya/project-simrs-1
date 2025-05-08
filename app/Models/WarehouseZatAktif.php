<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseZatAktif extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_zat_aktif";
    protected $guarded = ["id"];
}
