<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\ApSupplierHeader;
use App\Models\WarehouseSupplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportAPSupplierController extends Controller
{
    public function belumTukarFaktur(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $request->input('tanggal_akhir') ?? date('Y-m-d');

        return view('app-type.keuangan.report-ap-supplier.belum-tukar-faktur', compact('tanggal_awal', 'tanggal_akhir'));
    }

    public function agingApSupplier(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $request->input('tanggal_akhir') ?? date('Y-m-d');

        return view('app-type.keuangan.report-ap-supplier.aging-ap-supplier', compact('tanggal_awal', 'tanggal_akhir'));
    }

    public function laporanJatuhTempo(Request $request)
    {
        // --- 1. Ambil Input Filter dari Request ---
        // Jika tidak ada input, defaultnya adalah bulan ini
        $awal_due = $request->input('awal_due') ?? Carbon::now()->startOfMonth()->toDateString();
        $akhir_due = $request->input('akhir_due') ?? Carbon::now()->endOfMonth()->toDateString();
        $selected_supplier_id = $request->input('supplier_id');

        // --- 2. Bangun Query Dasar ---
        $query = ApSupplierHeader::query()
            ->with('supplier') // Eager load relasi supplier untuk efisiensi
            // KONDISI KUNCI: Hanya ambil yang statusnya masih ada hutang
            ->whereIn('status_pembayaran', ['Belum Lunas', 'Lunas Sebagian']);

        // --- 3. Terapkan Filter ---
        if ($awal_due && $akhir_due) {
            $query->whereBetween('due_date', [$awal_due, $akhir_due]);
        }

        if ($selected_supplier_id) {
            $query->where('supplier_id', $selected_supplier_id);
        }

        // --- 4. Ambil dan Proses Data ---
        // Ambil semua data yang cocok, urutkan berdasarkan supplier lalu tanggal jatuh tempo
        $results = $query->orderBy('supplier_id')->orderBy('due_date', 'asc')->get();

        // KEAJAIBAN DIMULAI DI SINI: Kelompokkan hasil berdasarkan nama supplier
        // Hasilnya akan menjadi collection di mana key-nya adalah nama supplier,
        // dan value-nya adalah collection lain berisi semua invoice dari supplier tsb.
        $groupedAps = $results->groupBy('supplier.nama');

        // --- 5. Ambil Data Master untuk Filter Dropdown ---
        $suppliers = WarehouseSupplier::where('aktif', 1)->orderBy('nama')->get();

        // --- 6. Cek jika ada request export ---
        if ($request->has('export') && $request->export == 'excel') {
            return $this->exportJatuhTempoExcel($groupedAps, $awal_due, $akhir_due);
        }

        // --- 7. Kirim Semua Data ke View ---
        return view('app-type.keuangan.report-ap-supplier.laporan-jatuh-tempo', compact(
            'groupedAps',
            'suppliers',
            'awal_due',
            'akhir_due',
            'selected_supplier_id'
        ));
    }

    /**
     * Export laporan jatuh tempo ke Excel
     */
    private function exportJatuhTempoExcel($groupedAps, $awal_due, $akhir_due)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul dokumen
        $sheet->setTitle('Laporan Jatuh Tempo');

        // Header utama
        $sheet->setCellValue('A1', 'LAPORAN JATUH TEMPO AP SUPPLIER');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Periode
        $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($awal_due)->format('d-m-Y') . ' s/d ' . Carbon::parse($akhir_due)->format('d-m-Y'));
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->getFont()->setBold(true);

        // Header tabel
        $headers = [
            'A4' => 'No',
            'B4' => 'Supplier',
            'C4' => 'Inv Number',
            'D4' => 'Kode AP',
            'E4' => 'Tgl AP',
            'F4' => 'Duedate',
            'G4' => 'DPP',
            'H4' => 'PPN',
            'I4' => 'Total Hutang',
            'J4' => 'Sisa Hutang'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style header tabel
        $sheet->getStyle('A4:J4')->getFont()->setBold(true);
        $sheet->getStyle('A4:J4')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A4:J4')->getFont()->getColor()->setARGB('FFFFFFFF');

        $row = 5;
        $no = 1;

        // Total keseluruhan
        $grandTotalDpp = 0;
        $grandTotalPpn = 0;
        $grandTotalHutang = 0;
        $grandTotalSisaHutang = 0;

        foreach ($groupedAps as $supplierName => $aps) {
            $supplierTotalDpp = 0;
            $supplierTotalPpn = 0;
            $supplierTotalHutang = 0;
            $supplierTotalSisaHutang = 0;

            // Data per supplier
            foreach ($aps as $ap) {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $ap->supplier->nama);
                $sheet->setCellValue('C' . $row, $ap->no_invoice_supplier);
                $sheet->setCellValue('D' . $row, $ap->kode_ap);
                $sheet->setCellValue('E' . $row, Carbon::parse($ap->tanggal_ap)->format('d-m-Y'));
                $sheet->setCellValue('F' . $row, Carbon::parse($ap->due_date)->format('d-m-Y'));
                $sheet->setCellValue('G' . $row, $ap->subtotal);
                $sheet->setCellValue('H' . $row, $ap->ppn_nominal);
                $sheet->setCellValue('I' . $row, $ap->grand_total);
                $sheet->setCellValue('J' . $row, $ap->sisa_hutang);

                // Format angka
                $sheet->getStyle('G' . $row . ':J' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

                // Akumulasi total per supplier
                $supplierTotalDpp += $ap->subtotal;
                $supplierTotalPpn += $ap->ppn_nominal;
                $supplierTotalHutang += $ap->grand_total;
                $supplierTotalSisaHutang += $ap->sisa_hutang;

                $row++;
            }

            // Subtotal per supplier
            $sheet->setCellValue('A' . $row, '');
            $sheet->setCellValue('B' . $row, 'Total ' . $supplierName);
            $sheet->mergeCells('B' . $row . ':F' . $row);
            $sheet->setCellValue('G' . $row, $supplierTotalDpp);
            $sheet->setCellValue('H' . $row, $supplierTotalPpn);
            $sheet->setCellValue('I' . $row, $supplierTotalHutang);
            $sheet->setCellValue('J' . $row, $supplierTotalSisaHutang);

            // Style subtotal
            $sheet->getStyle('A' . $row . ':J' . $row)->getFont()->setBold(true);
            $sheet->getStyle('A' . $row . ':J' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFD9E1F2');
            $sheet->getStyle('G' . $row . ':J' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

            // Akumulasi grand total
            $grandTotalDpp += $supplierTotalDpp;
            $grandTotalPpn += $supplierTotalPpn;
            $grandTotalHutang += $supplierTotalHutang;
            $grandTotalSisaHutang += $supplierTotalSisaHutang;

            $row += 2; // Spasi setelah subtotal
        }

        // Grand Total
        if ($groupedAps->isNotEmpty()) {
            $sheet->setCellValue('A' . $row, '');
            $sheet->setCellValue('B' . $row, 'GRAND TOTAL');
            $sheet->mergeCells('B' . $row . ':F' . $row);
            $sheet->setCellValue('G' . $row, $grandTotalDpp);
            $sheet->setCellValue('H' . $row, $grandTotalPpn);
            $sheet->setCellValue('I' . $row, $grandTotalHutang);
            $sheet->setCellValue('J' . $row, $grandTotalSisaHutang);

            // Style grand total
            $sheet->getStyle('A' . $row . ':J' . $row)->getFont()->setBold(true);
            $sheet->getStyle('A' . $row . ':J' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF4472C4');
            $sheet->getStyle('A' . $row . ':J' . $row)->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getStyle('G' . $row . ':J' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        }

        // Auto size columns
        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set border untuk seluruh tabel
        $lastRow = $row;
        $sheet->getStyle('A4:J' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Generate filename
        $filename = 'laporan-jatuh-tempo-' . Carbon::parse($awal_due)->format('Ymd') . '-' . Carbon::parse($akhir_due)->format('Ymd') . '.xlsx';

        // Output file
        $writer = new Xlsx($spreadsheet);

        // Set headers untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    /**
     * Method untuk export Excel yang bisa digunakan kembali di halaman lain
     * Contoh penggunaan untuk laporan lain
     */
    protected function createExcelTemplate($title, $headers, $data, $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul dokumen
        $sheet->setTitle($title);

        // Header utama
        $sheet->setCellValue('A1', strtoupper($title));
        $lastColumn = chr(64 + count($headers)); // Convert number to letter (A, B, C, etc.)
        $sheet->mergeCells('A1:' . $lastColumn . '1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header tabel
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $col++;
        }

        // Style header tabel
        $sheet->getStyle('A3:' . $lastColumn . '3')->getFont()->setBold(true);
        $sheet->getStyle('A3:' . $lastColumn . '3')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A3:' . $lastColumn . '3')->getFont()->getColor()->setARGB('FFFFFFFF');

        // Data
        $row = 4;
        foreach ($data as $rowData) {
            $col = 'A';
            foreach ($rowData as $cellData) {
                $sheet->setCellValue($col . $row, $cellData);
                $col++;
            }
            $row++;
        }

        // Auto size columns
        foreach (range('A', $lastColumn) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set border untuk seluruh tabel
        $sheet->getStyle('A3:' . $lastColumn . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Output file
        $writer = new Xlsx($spreadsheet);

        // Set headers untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
