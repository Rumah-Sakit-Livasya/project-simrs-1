<?php

namespace App\Exports;

use App\Models\SIMRS\Departement;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\TindakanMedis;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TindakanMedisExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected int $grupPenjaminId;
    protected int $departementId;
    protected $kelasRawat;

    public function __construct(int $grupPenjaminId, int $departementId)
    {
        $this->grupPenjaminId = $grupPenjaminId;
        $this->departementId = $departementId;
        // Urutkan berdasarkan kolom 'urutan' (pastikan kolom ini ada di tabel kelas_rawat)
        $this->kelasRawat = KelasRawat::orderBy('urutan')->get();
    }

    /**
     * Mengambil data dari database yang akan diekspor.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return TindakanMedis::whereHas('grup_tindakan_medis', function ($query) {
            $query->where('departement_id', $this->departementId);
        })
            ->with([
                'grup_tindakan_medis.departement',
                'tarifTindakanMedis' => function ($query) {
                    $query->where('group_penjamin_id', $this->grupPenjaminId);
                }
            ])
            ->get();
    }

    /**
     * Mendefinisikan header file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        $grupPenjamin = GroupPenjamin::find($this->grupPenjaminId);
        $departement = Departement::find($this->departementId);

        $headerRow1 = ['FGID', 'FIRM GROUP', 'DID', 'POLYNAME', 'Status Hapus'];
        $headerRow2 = [
            $grupPenjamin->id ?? '',
            $grupPenjamin->name ?? '',
            $departement->id ?? '',
            $departement->name ?? '',
            't = Hapus'
        ];

        $headerRow4 = ['ID#', 'CODE', 'NAME', 'GROUP', 'HAPUS'];
        $headerRow5 = ['', '', '', '', ''];

        foreach ($this->kelasRawat as $kelas) {
            $headerRow4[] = strtoupper($kelas->kelas) . ':' . $kelas->id;
            for ($i = 0; $i < 4; $i++) {
                $headerRow4[] = '';
            }
            $headerRow5 = array_merge($headerRow5, ['Share Dokter', 'Share RS', 'Prasarana', 'BHP', 'TARIF']);
        }

        return [
            $headerRow1,
            $headerRow2,
            [],
            $headerRow4,
            $headerRow5
        ];
    }

    /**
     * Memetakan setiap baris data dari collection ke format array.
     *
     * @param TindakanMedis $tindakan
     * @return array
     */
    public function map($tindakan): array
    {
        $row = [
            $tindakan->id,
            $tindakan->kode,
            $tindakan->nama_tindakan,
            $tindakan->grup_tindakan_medis->departement->name ?? '',
            $tindakan->deleted_at ? 't' : 'f',
        ];

        foreach ($this->kelasRawat as $kelas) {
            $tarif = $tindakan->tarifTindakanMedis->firstWhere('kelas_rawat_id', $kelas->id);

            $row = array_merge($row, [
                $tarif->share_dr ?? '0.00',
                $tarif->share_rs ?? '0.00',
                $tarif->prasarana ?? '0.00',
                $tarif->bhp ?? '0.00',
                $tarif->total ?? '0.00',
            ]);
        }

        return $row;
    }
}
