<?php

namespace App\Models\SIMRS;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $guarded = ['id'];

    use HasFactory;

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->registrasi = $model->generateNomorRegistrasi();
        });
    }

    public function generateNomorRegistrasi()
    {
        $tanggal = now()->format('ymd');

        $latestRegistrasi = static::where('registrasi', 'like', $tanggal . '%')->latest('registrasi')->first();

        if ($latestRegistrasi) {
            $lastNumber = intval(substr($latestRegistrasi->registrasi, 6));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            return $tanggal . $newNumber;
        }

        return $tanggal . '0001';
    }
}
