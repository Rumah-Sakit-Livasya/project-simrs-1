<?php

namespace App\Exports;

use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\ParameterRadiologi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RadiologiTarifExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
        return ParameterRadiologi::with([
            'grup_parameter_radiologi',
            'kategori_radiologi',
            'tarif_parameter_radiologi' => function ($query) {
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
        $headerRow4 = ['ID#', 'Group', 'Parameter', 'Kategori'];
        $headerRow5 = ['', '', '', ''];

        // Loop untuk setiap kelas rawat, masing-masing 3 kolom
        foreach ($this->kelasRawat->sortBy('urutan') as $kelas) {
            $headerRow4[] = strtoupper($kelas->kelas) . ':' . $kelas->id;
            $headerRow4[] = ''; // Sel kosong untuk merge
            $headerRow4[] = ''; // Sel kosong untuk merge

            array_push($headerRow5, 'Share Dokter', 'Share RS', 'TARIF');
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
            $parameter->grup_parameter_radiologi?->nama_grup,
            $parameter->parameter,
            $parameter->kategori_radiologi?->nama_kategori,
        ];

        foreach ($this->kelasRawat as $kelas) {
            $tarif = $parameter->tarif_parameter_radiologi->firstWhere('kelas_rawat_id', $kelas->id);

            $row[] = $tarif?->share_dr ?? '0.00';
            $row[] = $tarif?->share_rs ?? '0.00';
            $row[] = $tarif?->total ?? '0.00';
        }

        return $row;
    }
}
