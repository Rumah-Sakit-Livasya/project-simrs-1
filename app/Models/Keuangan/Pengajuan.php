<?php

namespace App\Models\Keuangan;

use App\Models\User;
use App\Models\Keuangan\pencairan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengajuan extends Model
{
    protected $guarded = ['id'];

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengaju_id');
    }

    public function userEntry(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_entry_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PengajuanDetail::class);
    }

    public function pencairan(): HasMany
    {
        return $this->hasMany(Pencairan::class);
    }
}
