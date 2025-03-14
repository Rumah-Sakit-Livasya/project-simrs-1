<?php

namespace App\Models\SIMRS\Pengkajian;

use Illuminate\Database\Eloquent\Model;

class FormTemplate extends Model
{
    protected $table = 'form_templates', $guarded = ['id'];

    public function kategori()
    {
        return $this->belongsTo(FormKategori::class, 'form_kategori_id');
    }
}
