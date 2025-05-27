<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class ProcurementPurchaseRequestNonPharmacy extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "procurement_purchase_request_non_pharmacy";
    protected $guarded = ["id"];

    public function gudang(){
        return $this->belongsTo(WarehouseMasterGudang::class, 'gudang_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(){
        return $this->hasMany(ProcurementPurchaseRequestNonPharmacyItems::class, 'pr_id');
    }
}
