<?php

namespace App\Models\SIMRS;

use Carbon\Carbon;

use App\Events\BillingFinalized;
use App\Models\Keuangan\JasaDokter;
use App\Models\OrderRadiologi;
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

    // Pada model Bilingan.php
    public function orderRadiologi()
    {
        return $this->hasOne(OrderRadiologi::class, 'bilingan_id', 'id');
    }
    protected static function booted()
    {
        static::updated(function (Bilingan $bilingan) {
            \Log::info('Bilingan updated', ['id' => $bilingan->id, 'status' => $bilingan->status]);

            if ($bilingan->isDirty('status') && strtolower($bilingan->status) === 'final') {
                \Log::info('Processing final bilingan', ['id' => $bilingan->id]);

                // UBAH BAGIAN INI
                // Tidak lagi memfilter 'Tindakan Medis', tapi mengecualikan 'Biaya Administrasi'
                $tagihanPasienItems = $bilingan->tagihanPasien()
                    ->where('tagihan', 'NOT LIKE', 'Biaya Administrasi%')
                    ->get();
                // --- AKHIR PERUBAHAN ---

                \Log::info('Found tagihan to process for Jasa Dokter', ['count' => $tagihanPasienItems->count()]);

                foreach ($tagihanPasienItems as $tagihan) {
                    \Log::info('Processing tagihan', ['id' => $tagihan->id, 'description' => $tagihan->tagihan]);

                    if (!$tagihan->jasaDokter()->exists()) {
                        \Log::info('Creating jasa dokter for tagihan', ['id' => $tagihan->id]);
                        $bilingan->createJasaDokter($tagihan);
                    } else {
                        \Log::info('Jasa dokter already exists for tagihan', ['id' => $tagihan->id]);
                    }
                }
            }
        });
    }

    protected function createJasaDokter(TagihanPasien $tagihan)
    {
        try {
            $registration = $this->registration;

            if (!$registration) {
                \Log::error('Missing registration relation for bilingan', [
                    'bilingan_id' => $this->id,
                    'tagihan_id' => $tagihan->id
                ]);
                return;
            }

            $persentaseJasaDokter = 0.60;
            $wajibBayar = $tagihan->wajib_bayar;
            $nilaiJasaDokter = $wajibBayar * $persentaseJasaDokter;

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
                'ap_number'         => null,
                'ap_date'           => null,
                'bill_date'         => $this->updated_at,
                'nama_tindakan'     => $tagihan->tagihan,
                'nominal'           => $tagihan->nominal,
                'diskon'            => 0,
                'ppn_persen'        => 0,
                'jkp'               => $wajibBayar,
                'jasa_dokter'       => $nilaiJasaDokter,
                'share_dokter'      => $nilaiJasaDokter,
                'status'            => 'draft',
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
