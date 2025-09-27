<?php

namespace App\Models\SIMRS;

use Carbon\Carbon;
use App\Models\Keuangan\JasaDokter;
use App\Models\OrderRadiologi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Casts\Attribute; // Penting untuk accessor baru

class Bilingan extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable;

    /**
     * Persentase default untuk perhitungan jasa dokter.
     * @info Sebaiknya nilai ini disimpan di database (misal: tabel settings) atau di file config agar mudah diubah.
     */
    const PERSENTASE_JASA_DOKTER = 0.60;

    protected $guarded = ['id'];
    protected $table = 'bilingan';

    //======================================================================
    // RELATIONS (Hubungan antar model, distandarisasi ke camelCase)
    //======================================================================

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function pembayaran_tagihan()
    {
        return $this->hasOne(PembayaranTagihan::class, 'bilingan_id');
    }

    public function jasaDokter()
    {
        return $this->hasMany(JasaDokter::class, 'bilingan_id');
    }

    public function downPayment()
    {
        return $this->hasMany(DownPayment::class);
    }

    public function tagihanPasien()
    {
        return $this->belongsToMany(TagihanPasien::class, 'bilingan_tagihan_pasien', 'bilingan_id', 'tagihan_pasien_id');
    }

    public function orderTindakanMedis()
    {
        return $this->belongsToMany(OrderTindakanMedis::class, 'order_tindakan_medis_bilingan');
    }

    public function konfirmasiAsuransi()
    {
        return $this->hasOne(\App\Models\Keuangan\KonfirmasiAsuransi::class, 'registration_id', 'registration_id');
    }

    public function orderRadiologi()
    {
        return $this->hasOne(OrderRadiologi::class, 'bilingan_id', 'id');
    }

    //======================================================================
    // ACCESSORS & MUTATORS (Atribut Dinamis)
    //======================================================================

    /**
     * Accessor untuk mendapatkan total DP sebagai properti.
     * Penggunaan: $bilingan->total_dp
     */
    protected function totalDp(): Attribute
    {
        return Attribute::make(
            get: fn() => (float) $this->downPayment()->sum('nominal')
        );
    }

    /**
     * Accessor untuk mengecek status lunas berdasarkan catatan pembayaran.
     * @warning Logika ini rapuh karena bergantung pada string. Sebaiknya gunakan kolom status boolean/enum.
     */
    protected function statusLunas(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->pembayaran_tagihan && $this->pembayaran_tagihan->bill_notes) {
                    return str_contains(strtolower($this->pembayaran_tagihan->bill_notes), 'lunas');
                }
                return false;
            }
        );
    }

    /**
     * Accessor untuk menampilkan teks status pembayaran.
     * Penggunaan: $bilingan->status_pembayaran
     */
    protected function statusPembayaran(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status_lunas ? 'Lunas' : 'Belum Lunas'
        );
    }

    //======================================================================
    // MODEL EVENTS (Logika yang berjalan otomatis)
    //======================================================================

    protected static function booted()
    {
        static::updated(function (Bilingan $bilingan) {
            // Jalankan hanya jika kolom 'status' diubah menjadi 'final'
            if ($bilingan->isDirty('status') && strtolower($bilingan->status) === 'final') {
                \Log::info('Processing final bilingan.', ['id' => $bilingan->id]);

                $tagihanPasienItems = $bilingan->tagihanPasien()
                    ->where('tagihan', 'NOT LIKE', 'Biaya Administrasi%')
                    ->get();

                \Log::info('Found tagihan items to process for Jasa Dokter.', ['count' => $tagihanPasienItems->count()]);

                foreach ($tagihanPasienItems as $tagihan) {
                    // Cek untuk menghindari duplikasi
                    if (!$tagihan->jasaDokter()->exists()) {
                        $bilingan->createJasaDokter($tagihan);
                    } else {
                        \Log::warning('Jasa dokter already exists for this tagihan, skipping.', ['tagihan_id' => $tagihan->id]);
                    }
                }
            }
        });
    }

    //======================================================================
    // PUBLIC & PROTECTED METHODS (Fungsi Bantuan)
    //======================================================================

    /**
     * Fungsi bantuan untuk membuat entri JasaDokter dari sebuah TagihanPasien.
     */
    protected function createJasaDokter(TagihanPasien $tagihan)
    {
        try {
            $registration = $this->registration;

            if (!$registration || !$registration->doctor_id) {
                \Log::error('Missing registration or doctor relation for bilingan, cannot create JasaDokter.', [
                    'bilingan_id' => $this->id,
                    'tagihan_id' => $tagihan->id
                ]);
                return;
            }

            $wajibBayar = (float) $tagihan->wajib_bayar;
            $nilaiJasaDokter = $wajibBayar * self::PERSENTASE_JASA_DOKTER;

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

            \Log::info('JasaDokter (draft) created successfully.', [
                'tagihan_id' => $tagihan->id,
                'bilingan_id' => $this->id,
                'calculated_share' => $nilaiJasaDokter
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating JasaDokter: ' . $e->getMessage(), [
                'tagihan_id' => $tagihan->id,
                'bilingan_id' => $this->id,
                'error_trace' => $e->getTraceAsString()
            ]);
        }
    }
}
