<?php

namespace App\Http\Controllers;

use App\Models\FarmasiResep;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\KelasRawat;
use Illuminate\Http\Request;

class FarmasiReportKronis extends Controller
{
    public function index()
    {
        return view("pages.simrs.farmasi.laporan.kronis.index", [
            "departements" => Departement::all(),
            "kelas_rawats" => KelasRawat::all(),
            "doctors" => Doctor::all(),
        ]);
    }

    public function show($startDate, $endDate, $tipe, $doctor_id, $departement_id, $kelas_rawat_id, $nama_obat)
    {
        $query = FarmasiResep::query()->with([
            "gudang",
            "doctor",
            "doctor.employee",
            "registration",
            "registration.departement",
            "registration.kelas_rawat",
            "registration.patient",
            "registration.doctor",
            "registration.doctor.employee",
            "items",
            "items.stored",
            "items.stored.pbi",
            "items.stored.pbi.item"
        ]);

        // only where group_penjamin is LIKE bpjs
        $query->whereHas('registration.penjamin.group_penjamin', function ($q) {
            $q->where('name', 'like', '%bpjs%');
        });

        // can't be OTC
        $query->whereNull('otc_id');
        $query->whereNotNull('registration_id');

        $query->whereBetween("created_at", [$startDate, $endDate]);

        $query->where('kronis', 1);

        if ($doctor_id != '-') {
            // if the row has doctor_id not null, then select that column
            // else, select whereHas from registration, $registration->doctor_id == $doctor_id
            $query->where(function ($q) use ($doctor_id) {
                $q->where('dokter_id', $doctor_id)
                    ->orWhereHas('registration', function ($q) use ($doctor_id) {
                        $q->where('doctor_id', $doctor_id);
                    });
            });
        }

        if ($departement_id != '-') {
            $query->whereHas('registration', function ($q) use ($departement_id) {
                $q->where('departement_id', $departement_id);
            });
        }

        if ($kelas_rawat_id != '-') {
            $query->whereHas('registration', function ($q) use ($kelas_rawat_id) {
                $q->where('kelas_rawat_id', $kelas_rawat_id);
            });
        }

        if ($nama_obat != '-') {
            $query->whereHas('items.stored.pbi', function ($q) use ($nama_obat) {
                $q->where('nama_barang', $nama_obat);
            });
        }

        if ($tipe != '-') {
            if ($tipe == 'rajal') {
                $query->whereHas('registration', function ($q) {
                    return $q->where('registration_type', 'rawat-jalan');
                });
            } else if ($tipe == 'ranap') {
                $query->whereHas('registration', function ($q) {
                    return $q->where('registration_type', 'rawat-inap');
                });
            }
        }

        $reseps = $query->get();

        return view(
            "pages.simrs.farmasi.laporan.kronis.show",
            compact("reseps", "startDate", "endDate")
        );
    }
}
