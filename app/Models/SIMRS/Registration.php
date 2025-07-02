<?php

namespace App\Models\SIMRS;

use App\Models\DietGizi;
use App\Models\OrderGizi;
use App\Models\SIMRS\BatalRegister;
use App\Models\Employee;
use App\Models\keuangan\JasaDokter;
use App\Models\Keuangan\KonfirmasiAsuransi;
use App\Models\OrderRadiologi;
use App\Models\SIMRS\CPPT\CPPT;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\Keuangan\Kasir;
use App\Models\SIMRS\Pengkajian\PengkajianNurseRajal;
use App\Models\SIMRS\Pengkajian\PengkajianDokterRajal;
use App\Models\SIMRS\Pengkajian\PengkajianLanjutan;
use App\Models\SIMRS\Pengkajian\TransferPasienAntarRuangan;
use App\Models\SIMRS\ResumeMedisRajal\ResumeMedisRajal;
use App\Models\getTotalTarifMedis;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Registration extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function kelas_rawat()
    {
        return $this->belongsTo(KelasRawat::class);
    }

    public function bilingan()
    {
        return $this->hasOne(Bilingan::class, 'registration_id');
    }

    public function pengkajian_nurse_rajal()
    {
        return $this->hasOne(PengkajianNurseRajal::class, 'registration_id');
    }

    public function pengkajian_lanjutan()
    {
        return $this->hasOne(PengkajianLanjutan::class, 'registration_id');
    }

    // Define the relationship to TransferPasienAntarRuangan
    public function transfer_pasien_antar_ruangan()
    {
        return $this->hasOne(TransferPasienAntarRuangan::class, 'registration_id');
    }

    public function pengkajian_dokter_rajal()
    {
        return $this->hasOne(PengkajianDokterRajal::class, 'registration_id');
    }

    public function resume_medis_rajal()
    {
        return $this->hasOne(ResumeMedisRajal::class, 'registration_id');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    // public function penjamin()
    // {
    //     return $this->belongsTo(Penjamin::class);
    // }

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class, 'penjamin_id');
    }




    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function batal_registration()
    {
        return $this->hasOne(BatalRegister::class);
    }

    public function tutup_kunjungan()
    {
        return $this->hasOne(TutupKunjungan::class);
    }

    public function batal_keluar()
    {
        return $this->hasOne(BatalKeluar::class);
    }

    public function ganti_dokter()
    {
        return $this->hasOne(GantiDokter::class);
    }

    protected static function boot()
    {
        parent::boot();
    }


    public function order_tindakan_medis()
    {
        return $this->hasMany(OrderTindakanMedis::class);
    }

    public function order_radiologi()
    {
        return $this->hasMany(OrderRadiologi::class, 'registration_id');
    }

    public function order_laboratorium()
    {
        return $this->hasMany(OrderLaboratorium::class, 'registration_id');
    }


    public function konfirmasi_asuransi()
    {
        return $this->hasMany(KonfirmasiAsuransi::class, 'registration_id');
    }


    public function getDoctorFullnameAttribute()
    {
        return $this->doctor->employee->fullname ?? null;
    }

    public function tagihan_pasien()
    {
        return $this->hasMany(TagihanPasien::class, 'registration_id');
    }



    public function order_gizi()
    {
        return $this->hasMany(OrderGizi::class, 'registration_id');
    }

    public function diet_gizi()
    {
        return $this->hasOne(DietGizi::class, 'registration_id');
    }

    public function cppt()
    {
        return $this->hasMany(CPPT::class);
    }

    // public function generateNomorRegistrasi()
    // {
    //     $date = Carbon::now();
    //     $year = $date->format('y');
    //     $month = $date->format('m');
    //     $day = $date->format('d');

    //     $count = Registration::whereDate('created_at', $date->toDateString())->count() + 1;
    //     $count = str_pad($count, 4, '0', STR_PAD_LEFT);

    //     return $year . $month . $day . $count;
    // }

    public function jasaDokter()
    {
        return $this->hasMany(JasaDokter::class, 'registration_id');
    }
}
