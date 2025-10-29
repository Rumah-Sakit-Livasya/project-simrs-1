<?php

namespace App\Models\RS;

use App\Models\WarehouseMasterGudang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentStock extends Model
{
    use HasFactory;

    protected $table = 'current_stocks';

    protected $guarded = ['id'];

    /**
     * Relasi ke ProjectBuildItem.
     */
    public function projectBuildItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProjectBuildItem::class, 'project_build_item_id');
    }

    /**
     * Relasi ke WarehouseMasterGudang.
     */
    public function warehouseMasterGudang(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WarehouseMasterGudang::class, 'gudang_id');
    }
}
