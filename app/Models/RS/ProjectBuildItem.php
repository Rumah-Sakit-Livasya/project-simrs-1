<?php

namespace App\Models\RS;

use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseSatuanBarang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectBuildItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'project_build_items';
    protected $guarded = ['id'];

    /**
     * Get the category for the item.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(WarehouseKategoriBarang::class, 'kategori_id');
    }

    /**
     * Get the base unit for the item.
     */
    public function satuan(): BelongsTo
    {
        return $this->belongsTo(WarehouseSatuanBarang::class, 'satuan_id');
    }

    /**
     * Get all material approval requests for this item.
     */
    public function materialApprovals(): HasMany
    {
        return $this->hasMany(MaterialApproval::class, 'project_build_item_id');
    }

    /**
     * Get all the current stock records for this item across all warehouses.
     */
    public function currentStocks(): HasMany
    {
        return $this->hasMany(CurrentStock::class, 'project_build_item_id');
    }
}
