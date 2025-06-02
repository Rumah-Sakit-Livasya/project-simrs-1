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

    // protected static function booted()
    // // {
    // //     static::updated(function ($billing) {
    // //         if ($billing->isDirty('status') && strtolower($billing->status) === 'final') {
    // //             event(new BillingFinalized($billing));
    // //         }
    // //     });
    // // }



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

    // App\Models\SIMRS\Bilingan.php
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
            $tindakanMedis = $tagihan->tindakan_medis; // Pastikan relasi ini ada di model TagihanPasien
            $registration = $tagihan->registration; // Pastikan relasi ini ada di model TagihanPasien

            if (!$registration) {
                \Log::error('Missing registration relation for tagihan', ['tagihan_id' => $tagihan->id]);
                return;
            }
            // tindakanMedis mungkin tidak selalu ada jika tagihan bukan dari tindakan medis spesifik,
            // namun untuk jasa dokter, kita asumsikan ia berasal dari tindakan medis.
            if (!$tindakanMedis) {
                \Log::error('Missing tindakan_medis relation for tagihan, cannot determine tarif.', ['tagihan_id' => $tagihan->id]);
                // Anda mungkin ingin default atau skip jika tidak ada tindakan_medis
                return;
            }


            $tarif = $tindakanMedis->getTarif(
                $registration->penjamin_id,
                $registration->kelas_rawat_id
            );

            if (!$tarif) {
                \Log::error('Tarif not found for tindakan in createJasaDokter', [
                    'tindakan_id' => $tindakanMedis->id,
                    'penjamin_id' => $registration->penjamin_id,
                    'kelas_id' => $registration->kelas_rawat_id,
                    'tagihan_id' => $tagihan->id,
                    'bilingan_id' => $this->id
                ]);
                return;
            }

            // `ap_number` dan `ap_date` akan diisi nanti saat proses "Save AP Dokter"
            // Jadi, kita tidak perlu generate $apNumber atau $apDate di sini.

            JasaDokter::create([
                'tagihan_pasien_id' => $tagihan->id,
                'registration_id'   => $registration->id,
                'bilingan_id'       => $this->id,
                'dokter_id'         => $registration->doctor_id, // Dokter dari registrasi
                'ap_number'         => null,                     // Akan diisi nanti
                'ap_date'           => null,                     // Akan diisi nanti
                'bill_date'         => $this->created_at,        // Atau $this->updated_at / tanggal finalisasi bilingan
                'nama_tindakan'     => $tagihan->tagihan,        // Mengambil dari kolom 'tagihan' di TagihanPasien
                'nominal'           => $tarif->total,
                'diskon'            => 0,                        // Default diskon
                'ppn_persen'        => 0,                        // Default PPN
                'jkp'               => $tarif->jkp ?? 0,         // Ambil JKP dari tarif jika ada, atau default 0
                'jasa_dokter'       => $tarif->share_dr,         // Ini bisa jadi sama dengan share_dokter
                'share_dokter'      => $tarif->share_dr,
                'status'            => 'draft',                  // Status awal adalah 'draft'
            ]);

            \Log::info('JasaDokter (draft) created successfully', ['tagihan_id' => $tagihan->id, 'bilingan_id' => $this->id]);

        } catch (\Exception $e) {
            \Log::error('Error creating JasaDokter (draft): ' . $e->getMessage(), [
                'tagihan_id' => $tagihan->id,
                'bilingan_id' => $this->id,
                'error_trace' => $e->getTraceAsString()
            ]);
        }
    }
}

