<?php

namespace App\Models;

use App\Models\Inventaris\RoomMaintenance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class SurveiKebersihanKamar extends Model implements ContractsAuditable
{
    use HasFactory, Auditable;

    protected $table = 'survei_kebersihan_kamar', $guarded = ['id'];

    public function kamar()
    {
        return $this->belongsTo(RoomMaintenance::class, 'room_maintenance_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
