<?php

namespace App\Models\SIMRS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GantiPenjamin extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penjaminLama()
    {
        return $this->belongsTo(Penjamin::class, 'penjamin_id_lama');
    }

    public function penjaminBaru()
    {
        return $this->belongsTo(Penjamin::class, 'penjamin_id_baru');
    }
}
