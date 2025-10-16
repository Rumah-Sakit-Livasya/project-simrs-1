<?php

namespace App\Models\SIMRS\Persalinan;

use App\Models\SIMRS\Bed;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Operasi\OrderOperasi;
use App\Models\SIMRS\Patient;
use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class Bayi extends Model
{
    protected $table = 'bayi';
    protected $guarded = ['id'];

    public function orderPersalinan()
    {
        return $this->belongsTo(OrderPersalinan::class, 'order_persalinan_id');
    }
    public function orderOperasi()
    {
        return $this->belongsTo(OrderOperasi::class, 'order_operasi_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class, 'bed_id');
    }
    public function getDoctors(Request $request)
    {
        try {
            $search = $request->input('q', ''); // Select2 sends the search term as 'q'
            $doctors = Doctor::with('employee')
                ->whereHas('employee', function ($query) use ($search) {
                    $query->where('fullname', 'like', "%{$search}%");
                })
                ->take(10)
                ->get()
                ->map(function ($doctor) {
                    return [
                        'id' => $doctor->id,
                        'text' => $doctor->employee->fullname ?? 'Unknown',
                    ];
                });

            return response()->json($doctors);
        } catch (\Exception $e) {
            Log::error('Error fetching doctors', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Gagal mengambil data dokter.', 'error' => $e->getMessage()], 500);
        }
    }
    public function kelasRawat()
    {
        return $this->belongsTo(KelasRawat::class, 'kelas_rawat_id');
    }

    // Accessor untuk format tanggal
    public function getTglLahirFormattedAttribute()
    {
        return $this->tgl_lahir ? $this->tgl_lahir->format('d/m/Y H:i') : null;
    }

    public function getTglRegFormattedAttribute()
    {
        return $this->tgl_reg ? $this->tgl_reg->format('d/m/Y H:i') : null;
    }

    // Accessor untuk nomor rekam medis
    public function getNomorRmAttribute()
    {
        return $this->no_rm ?? ($this->patient ? $this->patient->medical_record_number : null);
    }

    // Accessor untuk informasi bed
    public function getInfoBedAttribute()
    {
        if ($this->bed && $this->bed->room && $this->bed->room->kelas_rawat) {
            return $this->bed->room->kelas_rawat->kelas . ' / ' .
                $this->bed->room->ruangan . ' - ' .
                $this->bed->nama_tt;
        }
        return $this->kelas_kamar ?? '-';
    }

    // Accessor untuk nama dokter
    public function getNamaDokterAttribute()
    {
        return $this->doctor && $this->doctor->employee ?
            $this->doctor->employee->fullname : '-';
    }

    // Scope untuk filter berdasarkan order persalinan
    public function scopeByOrderPersalinan($query, $orderId)
    {
        return $query->where('order_persalinan_id', $orderId);
    }

    // Scope untuk filter berdasarkan status lahir
    public function scopeByStatusLahir($query, $status)
    {
        return $query->where('status_lahir', $status);
    }

    // Scope untuk filter berdasarkan jenis kelahiran
    public function scopeByJenisKelahiran($query, $jenis)
    {
        return $query->where('jenis_kelahiran', $jenis);
    }
}
