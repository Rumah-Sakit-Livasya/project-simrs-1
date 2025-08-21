<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Signature extends Model
{
    protected $table = 'signatures';

    protected $guarded = ['idp'];
    protected $appends = ['signature_url'];

    /**
     * Relasi polymorphic ke model induk (Sbar, dll).
     */
    public function signable()
    {
        return $this->morphTo();
    }

    /**
     * Accessor untuk mendapatkan URL publik dari file tanda tangan.
     * Asumsi: nama kolom di database adalah 'signature'. Sesuaikan jika berbeda.
     */
    public function getSignatureUrlAttribute()
    {
        if ($this->signature && Storage::disk('public')->exists($this->signature)) {
            return asset('storage/' . $this->signature);
        }
        return null; // Atau URL gambar placeholder jika tidak ada gambar
    }
}
