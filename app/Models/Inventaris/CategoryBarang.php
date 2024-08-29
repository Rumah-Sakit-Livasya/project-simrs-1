<?php

namespace App\Models\Inventaris;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'category_barang';

    public function template_barang()
    {
        return $this->hasMany(TemplateBarang::class);
    }
}
