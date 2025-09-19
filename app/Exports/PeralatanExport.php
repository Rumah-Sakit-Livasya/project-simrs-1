<?php

namespace App\Exports;

use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Peralatan\Peralatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class PeralatanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $grupPenjaminId;
    protected $kelasRawat;

    public function __construct(int $grupPenjaminId)
    {
        $this->grupPenjaminId = $grupPenjaminId;
        // Mengambil semua kelas rawat dan mengurutkannya agar konsisten
        $this->kelasRawat = KelasRawat::orderBy('id')->get();
    }

    /**
     * Mengambil data dari database untuk diekspor.
     * Menggunakan eager loading untuk efisiensi.
     */
    public function collection()
    {
        return Peralatan::with([
            'tarif_peralatan' => function ($query) {
                // Filter tarif hanya untuk grup penjamin yang dipilih
                $query->where('group_penjamin_id', $this->grupPenjaminId);
            }
        ])->get();
    }

    /**
     * Mendefinisikan header file yang kompleks (multi-row).
     */
    public function headings(): array
    {
        $grupPenjamin = GroupPenjamin::find($this->grupPenjaminId);

        // Baris 1 & 2: Informasi Grup Penjamin
        $headerRow1 = ['FGID', 'FIRM GROUP'];
        $headerRow2 = [$grupPenjamin->id ?? '', $grupPenjamin->name ?? 'GRUP TIDAK DITEMUKAN'];

        // Baris 4 & 5: Header Tabel
        $headerRow4 = ['ID#', 'nama', 'kode'];
        $headerRow5 = ['', '', '']; // Header kosong untuk 3 kolom pertama

        // Loop untuk setiap kelas rawat untuk membangun header dinamis
        foreach ($this->kelasRawat as $kelas) {
            // Baris 4: Nama Kelas Rawat (akan di-merge)
            $headerRow4[] = strtoupper($kelas->kelas) . ':' . $kelas->id;
            $headerRow4[] = ''; // Sel kosong untuk merge
            $headerRow4[] = ''; // Sel kosong untuk merge

            // Baris 5: Sub-header untuk tarif
            array_push($headerRow5, 'Share Dokter', 'Share RS', 'TARIF');
        }

        return [
            $headerRow1,
            $headerRow2,
            [], // Baris kosong sebagai pemisah (Baris 3)
            $headerRow4,
            $headerRow5
        ];
    }

    /**
     * Memetakan data dari satu model Peralatan ke format baris Excel.
     * @param Peralatan $peralatan
     */
    public function map($peralatan): array
    {
        // Data dasar dari model Peralatan
        $row = [
            $peralatan->id,
            $peralatan->nama . '/' . $peralatan->satuan_pakai, // Sesuai format 'NAMA/SATUAN'
            $peralatan->kode,
        ];

        // Loop melalui setiap kelas rawat untuk menemukan tarif yang sesuai
        foreach ($this->kelasRawat as $kelas) {
            // Cari tarif yang cocok dari koleksi yang sudah di-eager load
            $tarif = $peralatan->tarif_peralatan->firstWhere('kelas_rawat_id', $kelas->id);

            // Tambahkan data tarif ke baris, atau 0 jika tidak ada
            $row[] = $tarif->share_dr ?? 0;
            $row[] = $tarif->share_rs ?? 0;
            $row[] = $tarif->total ?? 0;
        }

        return $row;
    }

    /**
     * Mendaftarkan event listener untuk memanipulasi sheet setelah dibuat.
     * Digunakan untuk merge cell pada header.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge cell untuk header Kelas Rawat di baris ke-4
                $columnIndex = 4; // Mulai dari kolom 'D'
                foreach ($this->kelasRawat as $kelas) {
                    $startCell = Coordinate::stringFromColumnIndex($columnIndex) . '4';
                    $endCell = Coordinate::stringFromColumnIndex($columnIndex + 2) . '4';
                    $event->sheet->getDelegate()->mergeCells("{$startCell}:{$endCell}");
                    $columnIndex += 3; // Pindah ke blok 3 kolom berikutnya
                }
            },
        ];
    }
}
