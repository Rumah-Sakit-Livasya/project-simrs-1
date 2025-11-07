<?php

namespace App\Models\SIMRS\Pengkajian;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormTemplate extends Model
{
    use SoftDeletes;
    protected $table = 'form_templates', $guarded = ['id'];

    public function kategori()
    {
        return $this->belongsTo(FormKategori::class, 'form_kategori_id');
    }

    public function pengkajian_lanjutan()
    {
        return $this->hasMany(PengkajianLanjutan::class);
    }
}
