<?php

namespace App\Models\RS;

use App\Models\User;
use App\Models\WarehouseMasterGudang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLedger extends Model
{
    use HasFactory;

    protected $table = 'stock_ledgers';
    protected $guarded = ['id'];

    public function projectBuildItem(): BelongsTo
    {
        return $this->belongsTo(ProjectBuildItem::class, 'project_build_item_id');
    }

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(WarehouseMasterGudang::class, 'gudang_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Untuk referensi polimorfik
    public function reference()
    {
        return $this->morphTo();
    }
}
