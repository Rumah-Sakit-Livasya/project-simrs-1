<?php

namespace App\Models\RS;

use App\Models\WarehouseBarangNonFarmasi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectBuildItem extends Model
{
    use HasFactory;

    protected $table = 'project_build_items';
    protected $guarded = ['id'];

    /**
     * Get the master item data.
     */
    public function masterItem(): BelongsTo
    {
        return $this->belongsTo(WarehouseBarangNonFarmasi::class, 'barang_nf_id');
    }
}
