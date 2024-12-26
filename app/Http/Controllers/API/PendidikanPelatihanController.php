<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PendidikanPelatihan;
use Illuminate\Http\Request;

class PendidikanPelatihanController extends Controller
{
    public function getPeserta($rapatId)
    {
        $rapat = PendidikanPelatihan::find($rapatId);

        if (!$rapat) {
            return response()->json(['error' => 'Rapat tidak ditemukan'], 404);
        }

        $peserta = [];
        $peserta['peserta_rapat'] = $rapat->employees()
            ->select('employees.id as employee_id', 'employees.fullname', 'organizations.name as organization_name')
            ->join('organizations', 'employees.organization_id', '=', 'organizations.id')
            ->get();

        $peserta['pembicara'] = $rapat->pembicara;
        $peserta['organisasi_pembicara'] = $rapat->pembicara;

        return response()->json($peserta, 200);
    }

    public function store(Request $request)
    {
        try {
            // Membuat objek TimeSchedule dan menyimpannya ke database
            $pendidikanPelatihan = new PendidikanPelatihan();
            $pendidikanPelatihan->judul = $request->judul;
            $pendidikanPelatihan->pembicara = $request->pembicara;
            $pendidikanPelatihan->tempat = $request->tempat;
            $pendidikanPelatihan->datetime = $request->datetime;
            $pendidikanPelatihan->catatan = $request->catatan;
            $pendidikanPelatihan->type = $request->type;

            $pendidikanPelatihan->save();

            // Menyimpan data karyawan ke dalam tabel many-to-many time_schedule_employees
            $peserta = [];

            // Mengumpulkan peserta berdasarkan input
            if ($request->has('peserta')) {
                $pesetaId = $request->peserta;
                if (is_array($pesetaId)) {
                    $peserta = array_merge($peserta, array_map('intval', $pesetaId));
                } else {
                    $peserta[] = intval($pesetaId);
                }
            }

            $uniquePeserta = array_unique($peserta); // Menghapus duplikat ID yang sama

            $pendidikanPelatihan->employees()->attach($uniquePeserta);

            // Send broadcast message to participants
            // $roles = Employee::whereIn('id', $uniquePeserta)->pluck('fullname')->toArray(); // Ambil nama peserta untuk broadcast
            $this->broadcastMessageToParticipants($uniquePeserta, $pendidikanPelatihan->judul, $pendidikanPelatihan->datetime, $pendidikanPelatihan->tempat, $pendidikanPelatihan->catatan);

            return response()->json([
                'message' => 'Agenda Rapat berhasil ditambahkan!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Gagal menyimpan data.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function broadcastMessageToParticipants($participantIds, $judul, $datetime, $tempat, $catatan)
    {
        $employees = Employee::whereIn('id', $participantIds)->get();
        $headers = [
            'Key:KeyAbcKey',
            'Nama:arul',
            'Sandi:123###!!',
        ];

        // Data untuk pesan broadcast
        $broadcastMessage = "Assalamualaikum\n";
        $broadcastMessage .= "Mohon izin menyampaikan agenda diklat \"$judul\", yang akan dilaksanakan pada:\n\n";
        $broadcastMessage .= "Hari/Tanggal: " . \Carbon\Carbon::parse($datetime)->translatedFormat('l, d F Y') . "\n";
        $broadcastMessage .= "Waktu: " . \Carbon\Carbon::parse($datetime)->format('H:i') . " WIB s/d selesai\n";
        $broadcastMessage .= "Tempat: " . $tempat . "\n";
        $broadcastMessage .= "Catatan: " . $catatan . "\n\n";

        $broadcastMessage .= "Mohon hadir tepat pada waktunya, terima kasih☺🙏🏻\n\n";
        $broadcastMessage .= "Ttd,\nHRD RS Livasya";

        if ($employees->count() > 0) {
            foreach ($employees as $pesertaRapat) {
                if ($pesertaRapat->mobile_phone) {
                    $httpData = [
                        'number' => formatNomorIndo($pesertaRapat->mobile_phone),
                        'message' => $broadcastMessage,
                    ];

                    // Mengirim request HTTP menggunakan cURL
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, 'http://192.168.3.111:3001/send-message');
                    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $httpData);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                    $response = curl_exec($curl);
                    curl_close($curl);
                }
            }
        }
    }
}
