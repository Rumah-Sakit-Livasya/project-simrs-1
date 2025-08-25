<?php

namespace App\Http\Controllers;

use App\Models\FarmasiResep;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Room;
use App\Models\WarehouseGolonganBarang;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseKelompokBarang;
use App\Models\WarehouseMasterGudang;
use App\Models\WarehousePabrik;
use Illuminate\Support\Facades\Validator;

class FarmasiReportPenjualan extends Controller
{
    public function index()
    {
        return view("pages.simrs.farmasi.laporan.penjualan.index", [
            "gudangs" => WarehouseMasterGudang::all(),
            'doctors' => Doctor::all(),
            'departements' => Departement::all(),
            'kelas_rawats' => KelasRawat::all(),
            'kategoris' => WarehouseKategoriBarang::all(),
            'rooms' => Room::all(),
            'golongans' => WarehouseGolonganBarang::all(),
            'kelompoks' => WarehouseKelompokBarang::all(),
            'pabriks' => WarehousePabrik::all(),
            'penjamins' => Penjamin::all(),

        ]);
    }

    public function show($type, $btoa)
    {
        $request = json_decode(base64_decode($btoa), true);
        // dd($request);

        $data = Validator::make($request, [
            'order_date'          => ['required', 'regex:/^\d{4}-\d{2}-\d{2}\s-\s\d{4}-\d{2}-\d{2}$/'],
            'gudang_id'           => ['nullable', 'integer'],
            'doctor_id'           => ['nullable', 'integer'],
            'registration_type'   => ['nullable', 'string'],
            'departement_id'      => ['nullable', 'integer'],
            'kelas_rawat_id'      => ['nullable', 'integer'],
            'kategori_id'         => ['nullable', 'integer'],
            'room_id'             => ['nullable', 'integer'],
            'golongan_id'         => ['nullable', 'integer'],
            'kelompok_id'         => ['nullable', 'integer'],
            'pabrik_id'           => ['nullable', 'integer'],
            'nama_pasien'         => ['nullable', 'string'],
            'nama_obat'           => ['nullable', 'string'],
            'registration_number' => ['nullable', 'string'],
            'penjamin_id'         => ['nullable', 'integer'],
            'tipe_barang'         => ['nullable', 'string'],
            'formularium_barang'  => ['nullable', 'string'],
        ])->validated();

        $query = FarmasiResep::query();

        // exclude racikan
        // $query->with('items', function ($q) {
        //     $q->where('tipe', 'obat');
        // });

        $dateRange = explode(' - ', $data['order_date']);
        $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
        $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
        $query->whereBetween('order_date', [$startDate, $endDate]);

        if (!empty($data['gudang_id'])) {
            $query->where("gudang_id", $data['gudang_id']);
        }

        if (!empty($data['doctor_id'])) {
            $query->where('dokter_id', $data['doctor_id'])
                ->orWhereHas('registration', function ($q) use ($data) {
                    $q->where('doctor_id', $data['doctor_id']);
                });
        }

        if (!empty($data['registration_type'])) {
            if ($data['registration_type'] == 'rajal') {
                $query->whereNull('otc_id');
                $query->whereHas('registration', function ($q) {
                    $q->where("registration_type", 'rawat-jalan');
                });
            } else if ($data['registration_type'] == 'ranap') {
                $query->whereNull('otc_id');
                $query->whereHas('registration', function ($q) {
                    $q->where("registration_type", 'rawat-inap');
                });
            } else if ($data['registration_type'] == 'otc') {
                $query->whereNull('registration_id');
            }
        }

        if (!empty($data['departement_id'])) {
            $query->whereHas('otc', function ($q) use ($data) {
                $q->where('departement_id', $data['departement_id']);
            })->orWhereHas('registration', function ($q) use ($data) {
                $q->where('departement_id', $data['departement_id']);
            });
        }

        if (!empty($data['kelas_rawat_id'])) {
            $query->whereHas('registration', function ($q) use ($data) {
                $q->where('kelas_rawat_id', $data['kelas_rawat_id']);
            });
        }

        if (!empty($data['kategori_id'])) {
            $query->whereHas('items', function ($q) use ($data) {
                $q->whereHas('stored.pbi.item', function ($qr) use ($data) {
                    $qr->where('kategori_id', $data['kategori_id']);
                });
            });
        }

        if (!empty($data['room_id'])) {
            $query->whereHas('registration', function ($q) use ($data) {
                $q->where('room_id', $data['room_id']);
            });
        }

        if (!empty($data['golongan_id'])) {
            $query->whereHas('items', function ($q) use ($data) {
                $q->whereHas('stored.pbi.item', function ($qr) use ($data) {
                    $qr->where('golongan_id', $data['golongan_id']);
                });
            });
        }

        if (!empty($data['kelompok_id'])) {
            $query->whereHas('items', function ($q) use ($data) {
                $q->whereHas('stored.pbi.item', function ($qr) use ($data) {
                    $qr->where('kelompok_id', $data['kelompok_id']);
                });
            });
        }

        if (!empty($data['pabrik_id'])) {
            $query->whereHas('items', function ($q) use ($data) {
                $q->whereHas('stored.pbi.item', function ($qr) use ($data) {
                    $qr->where('principal', $data['pabrik_id']);
                });
            });
        }

        if (!empty($data['nama_pasien'])) {
            $query->where(function ($q) use ($data) {
                $q->whereHas('registration', function ($qr) use ($data) {
                    $qr->whereHas('patient', function ($q2) use ($data) {
                        $q2->where('name', 'like', '%' . $data['nama_pasien'] . '%');
                    });
                })
                    ->orWhereHas('otc', function ($qr) use ($data) {
                        $qr->where('nama_pasien', 'like', '%' . $data['nama_pasien'] . '%');
                    });
            });
        }

        if (!empty($data['nama_obat'])) {
            $query->whereHas('items', function ($q) use ($data) {
                $q->whereHas('stored.pbi.item', function ($qr) use ($data) {
                    $qr->where('nama', 'like', '%' . $data['nama_obat'] . '%');
                });
            });
        }

        if (!empty($data['registration_number'])) {
            $query->whereHas('registration', function ($q) use ($data) {
                $q->where('registration_number', 'like', '%' . $data['registration_number'] . '%');
            });
        }

        if (!empty($data['penjamin_id'])) {
            $query->whereHas('registration', function ($q) use ($data) {
                $q->where('penjamin_id', $data['penjamin_id']);
            });
        }

        if (!empty($data['tipe_barang'])) {
            $query->whereHas('items', function ($q) use ($data) {
                $q->whereHas('stored.pbi.item', function ($qr) use ($data) {
                    $qr->where('tipe', $data['tipe_barang']);
                });
            });
        }

        if (!empty($data['formularium_barang'])) {
            $query->whereHas('items', function ($q) use ($data) {
                $q->whereHas('stored.pbi.item', function ($qr) use ($data) {
                    $qr->where('formularium', $data['formularium_barang']);
                });
            });
        }


        if ($type == 'order') {
            $reseps = $query->get();
            return view(
                "pages.simrs.farmasi.laporan.penjualan.show-order",
                [
                    "reseps" => $reseps,
                    "startDate" => $dateRange[0],
                    "endDate" => $dateRange[1]
                ]
            );
        } else if ($type == 'doctor') {
            // can't be OTC
            $query->whereNull('otc_id');

            $reseps = $query->get();
            $dictionary = $this->aggregateDoctorSales($reseps);
            return view(
                "pages.simrs.farmasi.laporan.penjualan.show-doctor",
                [
                    "dictionary" => $dictionary,
                    "startDate" => $dateRange[0],
                    "endDate" => $dateRange[1]
                ]
            );
        } else {
            return abort(404);
        }
    }

    private function aggregateDoctorSales($reseps)
    {
        $dictionary = [];

        foreach ($reseps as $resep) {
            $doctor = isset($resep->dokter_id) ? $resep->doctor : $resep->registration->doctor;

            if (!isset($dictionary[$doctor->id])) {
                $dictionary[$doctor->id] = (object)[
                    "doctor" => $doctor,
                    "reseps" => []
                ];
            }

            // insert resep to reseps
            $dictionary[$doctor->id]->reseps[] = $resep;
        }

        return $dictionary;
    }
}
