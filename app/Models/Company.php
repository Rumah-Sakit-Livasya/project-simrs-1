<?php

namespace App\Models;

use App\Models\Inventaris\Barang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $guarded = ['id'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function Barang()
    {
        return $this->hasMany(Barang::class);
    }
}
