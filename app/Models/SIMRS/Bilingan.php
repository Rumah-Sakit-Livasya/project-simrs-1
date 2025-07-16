<?php

namespace App\Models\SIMRS;

use Carbon\Carbon;

use App\Events\BillingFinalized;
use App\Models\Keuangan\JasaDokter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Bilingan extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable;

    protected $guarded = ['id'];
    protected $table = 'bilingan';





    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function pembayaran_tagihan()
    {
        return $this->hasOne(PembayaranTagihan::class);
    }

    public function jasa_dokter()
    {
        return $this->hasMany(JasaDokter::class, 'bilingan_id');
    }

    public function down_payment()
    {
        return $this->hasMany(DownPayment::class);
    }

    public function tagihan_pasien()
    {
        return $this->belongsToMany(TagihanPasien::class, 'bilingan_tagihan_pasien');
    }

    public function order_tindakan_medis()
    {
        return $this->belongsToMany(OrderTindakanMedis::class, 'order_tindakan_medis_bilingan');
    }

    public function pembayaranTagihan()
    {
        return $this->hasOne(PembayaranTagihan::class, 'bilingan_id');
    }
    public function konfirmasiAsuransi()
    {
        return $this->hasOne(\App\Models\Keuangan\KonfirmasiAsuransi::class, 'registration_id', 'registration_id');
    }

    // Accessor untuk cek status lunas berdasarkan pembayaran tagihan
    // public function getStatusLunasAttribute()
    // {
    //     if ($this->pembayaranTagihan && $this->pembayaranTagihan->bill_notes) {
    //         return str_contains(strtolower($this->pembayaranTagihan->bill_notes), 'lunas');
    //     }

    //     return false;
    // }

    public function getStatusPembayaranAttribute()
    {
        return $this->status_lunas ? 'Lunas' : 'Belum Lunas';
    }

    public function getStatusLunasAttribute()
    {
        if ($this->pembayaranTagihan && $this->pembayaranTagihan->bill_notes) {
            return str_contains(strtolower($this->pembayaranTagihan->bill_notes), 'lunas');
        }
        return false;
    }

    public function tagihanPasien()
    {
        return $this->belongsToMany(TagihanPasien::class, 'bilingan_tagihan_pasien', 'bilingan_id', 'tagihan_pasien_id');
    }


    protected static function booted()
    {
        static::updated(function (Bilingan $bilingan) {
            \Log::info('Bilingan updated', ['id' => $bilingan->id, 'status' => $bilingan->status]);

            if ($bilingan->isDirty('status') && strtolower($bilingan->status) === 'final') {
                \Log::info('Processing final bilingan', ['id' => $bilingan->id]);

                // Menggunakan nama variabel yang berbeda untuk koleksi dan item individu
                $tagihanPasienItems = $bilingan->tagihanPasien()
                    ->where('tagihan', 'LIKE', '[Tindakan Medis]%')
                    ->get();

                \Log::info('Found tagihan', ['count' => $tagihanPasienItems->count()]);

                foreach ($tagihanPasienItems as $tagihan) {
                    \Log::info('Processing tagihan', ['id' => $tagihan->id]);

                    if (!$tagihan->jasaDokter()->exists()) {
                        \Log::info('Creating jasa dokter for tagihan', ['id' => $tagihan->id]);
                        // Panggil method pada instance $bilingan
                        // dan karena $bilingan sudah $this di dalam createJasaDokter, kita hanya perlu $tagihan
                        $bilingan->createJasaDokter($tagihan);
                    } else {
                        \Log::info('Jasa dokter already exists for tagihan', ['id' => $tagihan->id]);
                    }
                }
            }
        });
    }

    // Method createJasaDokter, parameter $bilingan dihilangkan karena kita bisa pakai $this
    protected function createJasaDokter(TagihanPasien $tagihan)
    {
        try {
            $registration = $this->registration; // Mengambil relasi registrasi dari bilingan ini

            if (!$registration) {
                \Log::error('Missing registration relation for bilingan', [
                    'bilingan_id' => $this->id,
                    'tagihan_id' => $tagihan->id
                ]);
                return;
            }

            // PERUBAHAN UTAMA: Menghitung jasa dokter sebagai 60% dari wajib_bayar
            $persentaseJasaDokter = 0.60; // 60%
            $wajibBayar = $tagihan->wajib_bayar;
            $nilaiJasaDokter = $wajibBayar * $persentaseJasaDokter;

            // Contoh: jika wajib_bayar = 135.000, maka nilaiJasaDokter = 135.000 * 0.60 = 81.000

            \Log::info('Calculating Jasa Dokter', [
                'wajib_bayar' => $wajibBayar,
                'persentase' => $persentaseJasaDokter,
                'hasil_perhitungan' => $nilaiJasaDokter
            ]);

            JasaDokter::create([
                'tagihan_pasien_id' => $tagihan->id,
                'registration_id'   => $registration->id,
                'bilingan_id'       => $this->id,
                'dokter_id'         => $registration->doctor_id,
                'ap_number'         => null,                     // Akan diisi nanti saat proses "Save AP Dokter"
                'ap_date'           => null,                     // Akan diisi nanti
                'bill_date'         => $this->updated_at,        // Menggunakan tanggal bilingan difinalisasi
                'nama_tindakan'     => $tagihan->tagihan,        // Mengambil dari kolom 'tagihan' di TagihanPasien
                'nominal'           => $tagihan->nominal,
                'diskon'            => 0,                        // Default diskon
                'ppn_persen'        => 0,                        // Default PPN
                'jkp'               => $wajibBayar,              // JKP adalah nilai 'wajib_bayar' dari tagihan
                'jasa_dokter'       => $nilaiJasaDokter,         // Diisi dengan hasil perhitungan 60%
                'share_dokter'      => $nilaiJasaDokter,         // Diisi dengan hasil perhitungan 60%
                'status'            => 'draft',                  // Status awal adalah draft
            ]);

            \Log::info('JasaDokter (draft) created successfully', ['tagihan_id' => $tagihan->id, 'bilingan_id' => $this->id, 'calculated_share' => $nilaiJasaDokter]);
        } catch (\Exception $e) {
            \Log::error('Error creating JasaDokter (draft): ' . $e->getMessage(), [
                'tagihan_id' => $tagihan->id,
                'bilingan_id' => $this->id,
                'error_trace' => $e->getTraceAsString()
            ]);
        }
    }
}
