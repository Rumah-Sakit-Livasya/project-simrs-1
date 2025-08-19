<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class ApNonGRNDetail extends Model
{
    protected $table = 'ap_non_grn_details';
    protected $guarded = ['id'];

    public function header()
    {
        return $this->belongsTo(ApSupplierHeader::class, 'ap_supplier_header_id');
    }

    public function coa()
    {
        // Terhubung ke model ChartOfAccount melalui kolom 'coa_id'
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }

    /**
     * Relasi ke Chart of Account (sebagai Cost Center).
     * Sebuah detail AP memiliki satu Cost Center.
     */
    public function costCenter()
    {
        return $this->belongsTo(RncCenter::class, 'cost_center_id');
    }
}
