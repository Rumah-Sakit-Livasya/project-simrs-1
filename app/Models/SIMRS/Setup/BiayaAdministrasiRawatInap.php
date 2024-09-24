<?php

namespace App\Models\SIMRS\Setup;

use App\Models\SIMRS\GroupPenjamin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiayaAdministrasiRawatInap extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'biaya_administrasi_rawat_inap', $fillable = ['group_penjamin_id', 'persentase', 'min_tarif', 'max_tarif'];

    public function group_penjamin()
    {
        return $this->belongsTo(GroupPenjamin::class, 'group_penjamin_id');
    }
}
