<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiagnosisCategory; // Pastikan path ini sesuai dengan model Anda
use App\Models\NursingDiagnosis;   // Pastikan path ini sesuai dengan model Anda
use Illuminate\Support\Facades\DB;

class NursingDiagnosisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Nonaktifkan pengecekan foreign key untuk proses truncate yang mulus
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Kosongkan tabel untuk menghindari duplikasi jika seeder dijalankan lagi
        NursingDiagnosis::truncate();
        DiagnosisCategory::truncate();

        $diagnosesData = [
            // AKTIFITAS DAN ISTIRAHAT
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00109', 'diagnosa' => 'Defisit Perawatan diri : Berpakaian'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00110', 'diagnosa' => 'Defisit Perawatan Diri : Eliminasi'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00102', 'diagnosa' => 'Defisit Perawatan diri : Makan'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00108', 'diagnosa' => 'Defisit Perawatan diri : Mandi'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00034', 'diagnosa' => 'Disfungsi Respon Penyapihan Ventilator'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00198', 'diagnosa' => 'Gangguan pola tidur'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00033', 'diagnosa' => 'Gangguan Ventilasi Spontan'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00085', 'diagnosa' => 'Hambatan mobilitas fisik'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00095', 'diagnosa' => 'Insomnia'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00092', 'diagnosa' => 'Intoleransi Aktifitas'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00093', 'diagnosa' => 'Keletihan'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00204', 'diagnosa' => 'Ketidakefektifan perfusi jaringan perifer'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00032', 'diagnosa' => 'Ketidakefektifan pola nafas'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00029', 'diagnosa' => 'Penurunan Curah Jantung'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00094', 'diagnosa' => 'Risiko Intoleransi aktifitas'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00202', 'diagnosa' => 'Risiko Ketidakefektifan perfusi gastrointestinal'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00203', 'diagnosa' => 'Risiko Ketidakefektifan perfusi ginjal'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00201', 'diagnosa' => 'Risiko Ketidakefektifan Perfusi Jaringan Otak'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00228', 'diagnosa' => 'Risiko Ketidakefektifan Perfusi Jaringan Perifer'],
            ['category' => 'AKTIFITAS DAN ISTIRAHAT', 'code' => '00200', 'diagnosa' => 'Risiko Penurunan Perfusi Jaringan Jantung'],

            // ELIMINASI DAN PERTUKARAN
            ['category' => 'ELIMINASI DAN PERTUKARAN', 'code' => '00013', 'diagnosa' => 'Diare'],
            ['category' => 'ELIMINASI DAN PERTUKARAN', 'code' => '00196', 'diagnosa' => 'Disfungsi Motilitas gastrointestinal'],
            ['category' => 'ELIMINASI DAN PERTUKARAN', 'code' => '00016', 'diagnosa' => 'Gangguan eliminasi urine'],
            ['category' => 'ELIMINASI DAN PERTUKARAN', 'code' => '00030', 'diagnosa' => 'Gangguan Pertukaran Gas'],
            ['category' => 'ELIMINASI DAN PERTUKARAN', 'code' => '00018', 'diagnosa' => 'Inkontinensia urine'],
            ['category' => 'ELIMINASI DAN PERTUKARAN', 'code' => '00011', 'diagnosa' => 'Konstipasi'],
            ['category' => 'ELIMINASI DAN PERTUKARAN', 'code' => '00023', 'diagnosa' => 'Retensi Urine'],
            ['category' => 'ELIMINASI DAN PERTUKARAN', 'code' => '00197', 'diagnosa' => 'Risiko Disfungsi Motilitas Gastrointestinal'],
            ['category' => 'ELIMINASI DAN PERTUKARAN', 'code' => '00015', 'diagnosa' => 'Risiko Konstipasi'],

            // HUBUNGAN PERAN
            ['category' => 'HUBUNGAN PERAN', 'code' => '00105', 'diagnosa' => 'Diskontinuitas Pemberian ASI'],
            ['category' => 'HUBUNGAN PERAN', 'code' => '00106', 'diagnosa' => 'Kesiapan Meningkatkan Pemberian ASI'],
            ['category' => 'HUBUNGAN PERAN', 'code' => '00104', 'diagnosa' => 'Ketidakefektifan Pemberian ASI'],

            // KEAMANAN / PERLINDUNGAN
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00007', 'diagnosa' => 'Hipertermi'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00006', 'diagnosa' => 'Hipotermi'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00044', 'diagnosa' => 'Kerusakan Integritas Jaringan'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00046', 'diagnosa' => 'Kerusakan Integritas Kulit'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00031', 'diagnosa' => 'Ketidakefektifan Bersihan Jalan Nafas'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00008', 'diagnosa' => 'Ketidakefektifan Termoregulasi'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00217', 'diagnosa' => 'Risiko Alergi'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00036', 'diagnosa' => 'Risiko Asfiksia'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00039', 'diagnosa' => 'Risiko Aspirasi'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00035', 'diagnosa' => 'Risiko Cedera'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00220', 'diagnosa' => 'Risiko Cedera Termal'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00004', 'diagnosa' => 'Risiko Infeksi'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00155', 'diagnosa' => 'Risiko Jatuh'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00047', 'diagnosa' => 'Risiko Kerusakan Integritas Kulit'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00005', 'diagnosa' => 'Risiko Ketidakseimbangan Suhu Tubuh'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00219', 'diagnosa' => 'Risiko Mata Kering'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00206', 'diagnosa' => 'Risiko Perdarahan'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00205', 'diagnosa' => 'Risiko Syok'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00038', 'diagnosa' => 'Risiko Trauma'],
            ['category' => 'KEAMANAN / PERLINDUNGAN', 'code' => '00213', 'diagnosa' => 'Risiko Trauma Vaskuler'],

            // KENYAMANAN
            ['category' => 'KENYAMANAN', 'code' => '00214', 'diagnosa' => 'Gangguan Rasa Nyaman'],
            ['category' => 'KENYAMANAN', 'code' => '00134', 'diagnosa' => 'Mual'],
            ['category' => 'KENYAMANAN', 'code' => '00132', 'diagnosa' => 'Nyeri Akut'],
            ['category' => 'KENYAMANAN', 'code' => '00133', 'diagnosa' => 'Nyeri Kronis'],

            // KOPING / TOLERANSI STRESS
            ['category' => 'KOPING / TOLERANSI STRESS', 'code' => '00146', 'diagnosa' => 'Ansietas'],
            ['category' => 'KOPING / TOLERANSI STRESS', 'code' => '00148', 'diagnosa' => 'Ketakutan'],
            ['category' => 'KOPING / TOLERANSI STRESS', 'code' => '00125', 'diagnosa' => 'Ketidakberdayaan'],
            ['category' => 'KOPING / TOLERANSI STRESS', 'code' => '00069', 'diagnosa' => 'Ketidakefektifan Koping'],
            ['category' => 'KOPING / TOLERANSI STRESS', 'code' => '00152', 'diagnosa' => 'Risiko Ketidakberdayaan'],

            // NUTRISI
            ['category' => 'NUTRISI', 'code' => '00103', 'diagnosa' => 'Gangguan menelan'],
            ['category' => 'NUTRISI', 'code' => '00194', 'diagnosa' => 'Ikterik neonatus'],
            ['category' => 'NUTRISI', 'code' => '00027', 'diagnosa' => 'Kekurangan volume cairan'],
            ['category' => 'NUTRISI', 'code' => '00026', 'diagnosa' => 'Kelebihan volume cairan'],
            ['category' => 'NUTRISI', 'code' => '00163', 'diagnosa' => 'Kesiapan meningkatkan nutrisi'],
            ['category' => 'NUTRISI', 'code' => '00216', 'diagnosa' => 'Ketidakcukupan Air Susu Ibu'],
            ['category' => 'NUTRISI', 'code' => '00107', 'diagnosa' => 'Ketidakefektifan Pola Makan Bayi'],
            ['category' => 'NUTRISI', 'code' => '00002', 'diagnosa' => 'Ketidakseimbangan nutrisi : Kurang dari kebutuhan tubuh'],
            ['category' => 'NUTRISI', 'code' => '00001', 'diagnosa' => 'Ketidakseimbangan nutrisi : Lebih dari kebutuhan tubuh'],
            ['category' => 'NUTRISI', 'code' => '00179', 'diagnosa' => 'Resiko ketidakstabilan kadar gula darah'],
            ['category' => 'NUTRISI', 'code' => '00230', 'diagnosa' => 'Risiko Ikterik neonatus'],
            ['category' => 'NUTRISI', 'code' => '00028', 'diagnosa' => 'Risiko kekurangan volume cairan'],
            ['category' => 'NUTRISI', 'code' => '00195', 'diagnosa' => 'Risiko ketidakseimbangan elektrolit'],
            ['category' => 'NUTRISI', 'code' => '00003', 'diagnosa' => 'Risiko ketidakseimbangan nutrisi : Lebih dari kebutuhan tubuh'],
            ['category' => 'NUTRISI', 'code' => '00025', 'diagnosa' => 'Risiko Ketidakseimbangan volume cairan'],

            // PERSEPSI DIRI
            ['category' => 'PERSEPSI DIRI', 'code' => '00124', 'diagnosa' => 'Keputusasaan'],

            // PERSEPSI / KOGNISI
            ['category' => 'PERSEPSI / KOGNISI', 'code' => '00126', 'diagnosa' => 'Defisiensi Pengetahuan'],
            ['category' => 'PERSEPSI / KOGNISI', 'code' => '00051', 'diagnosa' => 'Hambatan Komunikasi Verbal'],
            ['category' => 'PERSEPSI / KOGNISI', 'code' => '00131', 'diagnosa' => 'Kerusakan Memori'],
            ['category' => 'PERSEPSI / KOGNISI', 'code' => '00128', 'diagnosa' => 'Konfusi Akut'],
            ['category' => 'PERSEPSI / KOGNISI', 'code' => '00129', 'diagnosa' => 'Konfusi Kronik'],
            ['category' => 'PERSEPSI / KOGNISI', 'code' => '00173', 'diagnosa' => 'Risiko Konfusi Akut'],

            // PROMOSI KESEHATAN
            ['category' => 'PROMOSI KESEHATAN', 'code' => '00078', 'diagnosa' => 'Ketidakefektifan manajemen kesehatan diri'],
            ['category' => 'PROMOSI KESEHATAN', 'code' => '00099', 'diagnosa' => 'Ketidakefektifan Pemeliharaan Kesehatan'],
            ['category' => 'PROMOSI KESEHATAN', 'code' => '00043', 'diagnosa' => 'Ketidakefektifan perlindungan'],
            ['category' => 'PROMOSI KESEHATAN', 'code' => '00118', 'diagnosa' => 'Perilaku Kesehatan Cenderung Beresiko'],

            // SEKSUALITAS
            ['category' => 'SEKSUALITAS', 'code' => '00208', 'diagnosa' => 'Kesiapan Meningkatkan Proses Kehamilan - Melahirkan'],
            ['category' => 'SEKSUALITAS', 'code' => '00221', 'diagnosa' => 'Ketidakefektifan Proses Kehamilan - Melahirkan'],
            ['category' => 'SEKSUALITAS', 'code' => '00209', 'diagnosa' => 'Risiko Gangguan Hubungan Ibu - Janin'],
            ['category' => 'SEKSUALITAS', 'code' => '00227', 'diagnosa' => 'Risiko Ketidakefektifan Proses Kehamilan - Persalinan'],

            // TAMBAHAN
            ['category' => 'TAMBAHAN', 'code' => '00136', 'diagnosa' => 'Gangguan pertukaran gas'],
            ['category' => 'TAMBAHAN', 'code' => '00142', 'diagnosa' => 'Gangguan termoregulasi : Hipertermi / Hipotermi'],
            ['category' => 'TAMBAHAN', 'code' => '00140', 'diagnosa' => 'Kekurangan Volume Cairan Tubuh'],
            ['category' => 'TAMBAHAN', 'code' => '00141', 'diagnosa' => 'Kelebihan Volume Cairan tubuh'],
            ['category' => 'TAMBAHAN', 'code' => '00134', 'diagnosa' => 'Ketidak efektifan bersihnya jalan napas'],
            ['category' => 'TAMBAHAN', 'code' => '00138', 'diagnosa' => 'Ketidak efektifan perfusi jaringan perifer / serebrasi'],
            ['category' => 'TAMBAHAN', 'code' => '00135', 'diagnosa' => 'Ketidak efektifan Pola napas'],
            ['category' => 'TAMBAHAN', 'code' => '00143', 'diagnosa' => 'Ketidak seimbangan Nutrisi Kurang dari Kebutuhan Tubuh'],
            ['category' => 'TAMBAHAN', 'code' => '00139', 'diagnosa' => 'Nyeri Akut'],
            ['category' => 'TAMBAHAN', 'code' => '00137', 'diagnosa' => 'Penurunan curah jantung'],
            ['category' => 'TAMBAHAN', 'code' => '00146', 'diagnosa' => 'Resiko Aspirasi'],
            ['category' => 'TAMBAHAN', 'code' => '00149', 'diagnosa' => 'Resiko Pendarahan'],
            ['category' => 'TAMBAHAN', 'code' => '00147', 'diagnosa' => 'Resiko Syok'],
            ['category' => 'TAMBAHAN', 'code' => '00144', 'diagnosa' => 'Retensi urin'],
            ['category' => 'TAMBAHAN', 'code' => '00145', 'diagnosa' => 'Risiko / actual infeksi'],
        ];

        foreach ($diagnosesData as $data) {
            // Cari kategori, jika tidak ada, buat baru. Ini mencegah duplikasi kategori.
            $category = DiagnosisCategory::firstOrCreate(['name' => $data['category']]);

            // Buat diagnosa baru dan hubungkan dengan ID kategori yang sudah ada/dibuat.
            // 'updateOrCreate' digunakan untuk menghindari duplikasi berdasarkan 'code'
            NursingDiagnosis::updateOrCreate(
                ['code' => $data['code']],
                [
                    'category_id' => $category->id,
                    'diagnosa' => $data['diagnosa']
                ]
            );
        }

        // Aktifkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
