<?php

namespace App\Models\SIMRS\Pengkajian;

use Illuminate\Database\Eloquent\Model;

class FormKategori extends Model
{
    protected $table = 'form_kategori', $guarded = ['id'];

    public function form_templates()
    {
        return $this->hasMany(FormTemplate::class);
    }
}
