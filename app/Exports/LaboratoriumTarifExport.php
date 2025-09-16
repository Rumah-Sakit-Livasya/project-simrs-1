<?php

namespace App\Exports;

use App\Models\SIMRS\Departement;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use App\Models\SIMRS\ParameterLaboratoritum;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaboratoriumTarifExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $grupPenjaminId;
    protected $kelasRawat;

    public function __construct(int $grupPenjaminId)
    {
        $this->grupPenjaminId = $grupPenjaminId;
        $this->kelasRawat = KelasRawat::orderBy('id')->get();
    }

    /**
     * Mengambil data dari database untuk diekspor.
     */
    public function collection()
    {
        // Mengambil semua ParameterLaboratoritum yang terkait dengan departemen laboratorium.
        // Asumsi: Departemen Laboratorium memiliki ID tertentu atau bisa diidentifikasi dengan cara lain.
        // Jika tidak ada cara spesifik untuk mengidentifikasi departemen lab dari model ParameterLaboratoritum,
        // maka filter departemen dihilangkan agar sesuai template Laboratorium.
        return ParameterLaboratorium::with([
            'grup_parameter_laboratorium',
            'kategori_laboratorium',
            'tipe_laboratorium',
            'tarif_parameter_laboratorium' => function ($query) {
                // Filter tarif hanya untuk grup penjamin yang dipilih
                $query->where('group_penjamin_id', $this->grupPenjaminId);
            }
        ])->get();
    }

    /**
     * Mendefinisikan header file.
     */
    public function headings(): array
    {
        $grupPenjamin = GroupPenjamin::find($this->grupPenjaminId);

        // Baris 1-2: Info Global
        $headerRow1 = ['FGID', 'FIRM GROUP'];
        $headerRow2 = [$grupPenjamin->id ?? '', $grupPenjamin->name ?? ''];

        // Baris 4-5: Header Tabel
        $headerRow4 = ['ID#', 'Group', 'Tipe', 'Parameter', 'Kategori'];
        $headerRow5 = ['', '', '', '', ''];

        // Loop untuk setiap kelas rawat, masing-masing 3 kolom
        foreach ($this->kelasRawat->sortBy('urutan') as $kelas) {
            $headerRow4[] = strtoupper($kelas->kelas) . ':' . $kelas->id;
            $headerRow4[] = ''; // Sel kosong untuk merge
            $headerRow4[] = ''; // Sel kosong untuk merge
            $headerRow4[] = ''; // Sel kosong untuk merge
            $headerRow4[] = ''; // Sel kosong untuk merge

            array_push($headerRow5, 'Share DR', 'Share RS', 'prasarana', 'bhp', 'TARIF');
        }

        return [
            $headerRow1,
            $headerRow2,
            [], // Baris kosong
            $headerRow4,
            $headerRow5
        ];
    }

    /**
     * Memetakan data dari collection ke format baris Excel.
     */
    public function map($parameter): array
    {
        $row = [
            $parameter->id,
            $parameter->grup_parameter_laboratorium?->nama_grup,
            $parameter->tipe_laboratorium?->nama_tipe,
            $parameter->parameter,
            $parameter->kategori_laboratorium?->nama_kategori,
        ];

        foreach ($this->kelasRawat as $kelas) {
            $tarif = $parameter->tarif_parameter_laboratorium->firstWhere('kelas_rawat_id', $kelas->id);

            $row[] = $tarif?->share_dr ?? '0.00';
            $row[] = $tarif?->share_rs ?? '0.00';
            $row[] = $tarif?->prasarana ?? '0.00';
            $row[] = $tarif?->bhp ?? '0.00';
            $row[] = $tarif?->total ?? '0.00';
        }

        return $row;
    }
}
