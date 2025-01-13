<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\TimeSchedule;
use App\Models\TimeScheduleEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TimeScheduleController extends Controller
{
    public function getEmployeesByOrganizationId($organizationId)
    {
        $employees = Employee::where('organization_id', $organizationId)->get();
        return response()->json($employees);
    }

    public function getPjKaruKasiKasubag($organizationId, $jobPositionId)
    {
        $organization = Organization::with('child_structures')
            ->find($organizationId);

        $employeesByOrganizationAndJobPosition = collect([]);

        foreach ($organization->child_structures as $childStructure) {
            $childOrganization = Organization::find($childStructure->child_organization);

            // Filter employees based on organization_id and job_position_id
            $employees = $childOrganization->employees()
                ->where('job_position_id', $jobPositionId)
                ->where('is_active', 1)
                ->get();

            $employees->each(function ($employee) use ($employeesByOrganizationAndJobPosition) {
                $employeesByOrganizationAndJobPosition->push([
                    'employee_id' => $employee->id,
                    'name' => $employee->fullname,
                ]);
            });
        }

        return $employeesByOrganizationAndJobPosition;
    }

    public function getDirekturWadir($jobPositionId)
    {
        // Mengambil karyawan berdasarkan job_position_id
        $employeesByJobPosition = Employee::where('job_position_id', $jobPositionId)
            ->where('is_active', 1)
            ->get();

        $employeesData = collect([]);

        // Mengisi data karyawan ke dalam collection
        $employeesByJobPosition->each(function ($employee) use ($employeesData) {
            $employeesData->push([
                'employee_id' => $employee->id,
                'name' => $employee->fullname,
            ]);
        });

        return $employeesData;
    }

    public function getKabagKabid($organizationId, $jobPositionId)
    {
        // Mengambil karyawan berdasarkan job_position_id dan organization_id
        $employeesByJobPosition = Employee::where('job_position_id', $jobPositionId)
            ->where('organization_id', $organizationId)
            ->where('is_active', 1)
            ->get();

        $employeesData = collect([]);

        // Mengisi data karyawan ke dalam collection
        $employeesByJobPosition->each(function ($employee) use ($employeesData) {
            $employeesData->push([
                'employee_id' => $employee->id,
                'name' => $employee->fullname,
            ]);
        });

        return $employeesData;
    }

    public function getPeserta($rapatId)
    {
        $rapat = TimeSchedule::find($rapatId);

        if (!$rapat) {
            return response()->json(['error' => 'Rapat tidak ditemukan'], 404);
        }

        $peserta = [];
        $peserta['peserta_rapat'] = $rapat->employees()
            ->select('employees.id as employee_id', 'employees.fullname', 'organizations.name as organization_name', 'time_schedule_employees.status')
            ->join('organizations', 'employees.organization_id', '=', 'organizations.id')
            ->join('time_schedule_employees as tse', 'employees.id', '=', 'tse.employee_id')
            ->where('tse.time_schedule_id', $rapat->id)
            ->distinct() // Ensure no duplicate records
            ->get();

        $employee = Employee::where('id', $rapat->employee_id)->first();

        $peserta['yang_mengundang'] = $employee->fullname;
        $peserta['organisasi_yang_mengundang'] = $employee->organization->name;

        return response()->json($peserta, 200);
    }

    public function store(Request $request)
    {
        try {
            // Membuat objek TimeSchedule dan menyimpannya ke database
            $timeSchedule = new TimeSchedule();
            $timeSchedule->title = $request->title; // Anda dapat mengubah judul sesuai kebutuhan
            $timeSchedule->employee_id = $request->employee_id; // Anda dapat mengubah judul sesuai kebutuhan
            $timeSchedule->perihal = $request->perihal; // Ambil perihal dari request
            $timeSchedule->type = $request->type; // Atau ambil dari request jika ada pilihan
            $timeSchedule->datetime = $request->datetime; // Ambil waktu dan tanggal dari request
            $timeSchedule->created_at = now(); // Menambahkan created_at dengan waktu sekarang

            if ($request->is_online) {
                $roomName = \Str::slug($request->room_name);
                $timeSchedule->is_online = $request->is_online; // Anda dapat mengubah judul sesuai kebutuhan
                $timeSchedule->room_name =  $roomName; // Anda dapat mengubah judul sesuai kebutuhan
                $timeSchedule->link = "vcon.livasya.com/" . $timeSchedule->room_name; // Ambil waktu dan tanggal dari request
            } else {
                $roomName = $request->room_name;
                $timeSchedule->room_name = $request->room_name; // Ambil waktu dan tanggal dari request
            }

            // Simpan file jika ada
            if (request()->hasFile('undangan')) {
                $file = request()->file('undangan');
                $fileName = "01. Undangan - " . $request->title . '.' . $file->getClientOriginalExtension();
                $path = 'time-schedule/' . \Str::slug($request->type);
                $file->storeAs($path, $fileName, 'public');
                $timeSchedule['undangan'] = $fileName;
            }
            if (request()->hasFile('materi')) {
                $file = request()->file('materi');
                $fileName = "02. Materi - " . $request->title . '.' . $file->getClientOriginalExtension();
                $path = 'time-schedule/' . \Str::slug($request->type);
                $file->storeAs($path, $fileName, 'public');
                $timeSchedule['materi'] = $fileName;
            }
            if (request()->hasFile('absensi')) {
                $file = request()->file('absensi');
                $fileName = "03. Absensi - " . $request->title . '.' . $file->getClientOriginalExtension();
                $path = 'time-schedule/' . \Str::slug($request->type);
                $file->storeAs($path, $fileName, 'public');
                $timeSchedule['absensi'] = $fileName;
            }
            if (request()->hasFile('notulen')) {
                $file = request()->file('notulen');
                $fileName = "04. Notulen - " . $request->title . '.' . $file->getClientOriginalExtension();
                $path = 'time-schedule/' . \Str::slug($request->type);
                $file->storeAs($path, $fileName, 'public');
                $timeSchedule['notulen'] = $fileName;
            }
            $timeSchedule->save();

            // Menyimpan data karyawan ke dalam tabel many-to-many time_schedule_employees
            $peserta = [];

            // Mengumpulkan peserta berdasarkan input
            if ($request->has('direktur')) {
                $employees = $this->getDirekturWadir(9);
                $peserta = array_merge($peserta, $employees->pluck('employee_id')->toArray());
            }
            if ($request->has('wakil_direktur')) {
                $employees = $this->getDirekturWadir(46);
                $peserta = array_merge($peserta, $employees->pluck('employee_id')->toArray());
            }
            if ($request->has('kabag')) {
                $employees = $this->getKabagKabid(1, 19);
                $peserta = array_merge($peserta, $employees->pluck('employee_id')->toArray());
            }
            if ($request->has('kabid')) {
                $employees = $this->getKabagKabid(2, 19);
                $peserta = array_merge($peserta, $employees->pluck('employee_id')->toArray());
            }
            if ($request->has('kasubag')) {
                $employees = $this->getPjKaruKasiKasubag(1, 21);
                $peserta = array_merge($peserta, $employees->pluck('employee_id')->toArray());
            }
            if ($request->has('kasi')) {
                $employees = $this->getPjKaruKasiKasubag(2, 20);
                $peserta = array_merge($peserta, $employees->pluck('employee_id')->toArray());
            }
            if ($request->has('pj_umum')) {
                $employees = $this->getPjKaruKasiKasubag(9, 25);
                $peserta = array_merge($peserta, $employees->pluck('employee_id')->toArray());
            }
            if ($request->has('pj_penunjang')) {
                $employees = $this->getPjKaruKasiKasubag(6, 25);
                $peserta = array_merge($peserta, $employees->pluck('employee_id')->toArray());
            }
            if ($request->has('karu_pelayanan')) {
                $employees = $this->getPjKaruKasiKasubag(5, 25);
                $peserta = array_merge($peserta, $employees->pluck('employee_id')->toArray());
            }
            if ($request->has('peserta')) {
                $pesetaId = $request->peserta;
                if (is_array($pesetaId)) {
                    $peserta = array_merge($peserta, array_map('intval', $pesetaId));
                } else {
                    $peserta[] = intval($pesetaId);
                }
            }

            $uniquePeserta = array_unique($peserta); // Menghapus duplikat ID yang sama

            $timeSchedule->employees()->attach($uniquePeserta);

            // Send broadcast message to participants
            $roles = Employee::whereIn('id', $uniquePeserta)->pluck('fullname')->toArray(); // Ambil nama peserta
            $this->broadcastMessageToParticipants($uniquePeserta, $timeSchedule->title, $timeSchedule->datetime, $request->is_online, $roomName, $roles);

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

    private function broadcastMessageToParticipants($participantIds, $title, $datetime, $isOnline, $roomName, $roles)
    {
        $employees = Employee::whereIn('id', $participantIds)->get();
        $headers = [
            'Key:KeyAbcKey',
            'Nama:arul',
            'Sandi:123###!!',
        ];

        // Data untuk pesan broadcast
        $broadcastMessage = "Assalamualaikum\n";
        $broadcastMessage .= "Kepada yth,\n";
        foreach ($roles as $role) {
            $broadcastMessage .= "- $role\n";
        }
        $broadcastMessage .= "Mohon izin menyampaikan agenda $title, yang akan dilaksanakan pada:\n\n";
        $broadcastMessage .= "Hari/Tanggal: " . \Carbon\Carbon::parse($datetime)->translatedFormat('l, d F Y') . "\n";
        $broadcastMessage .= "Waktu: " . \Carbon\Carbon::parse($datetime)->format('H:i') . " WIB s/d selesai\n";

        if ($isOnline) {
            $broadcastMessage .= "Link: vcon.livasya.com/$roomName\n";
        } else {
            $broadcastMessage .= "Tempat: " . $roomName . "\n";
        }

        $broadcastMessage .= "Mohon hadir tepat pada waktunya, terima kasih☺🙏🏻\n\n";
        $broadcastMessage .= "Ttd,\nWakil Direktur RS Livasya";

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

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,docx,xlsx,pptx,jpeg,png|max:2048',
        ]);

        $type = $request->type;
        $id = $request->id;

        // Array tipe file dan urutan
        $fileTypes = [
            'undangan' => '01. Undangan',
            'materi' => '02. Materi',
            'absensi' => '03. Absensi',
            'notulen' => '04. Notulen'
        ];
        $row = TimeSchedule::find($id);

        // Inisialisasi array untuk menyimpan nama file
        $timeSchedule = [];

        // Periksa apakah file ada di request untuk setiap tipe
        foreach ($fileTypes as $key => $label) {
            if ($request->hasFile('file') && $request->type === $key) {
                $file = $request->file('file');
                $fileName = $label . ' - ' . $row->title . '.' . $file->getClientOriginalExtension();
                $path = 'time-schedule/' . \Str::slug($row->type);
                // Simpan file ke storage
                $file->storeAs($path, $fileName, 'public');
                // Simpan nama file ke array $timeSchedule
                $timeSchedule[$key] = $fileName;
            }
        }

        // Periksa apakah tipe file ada dalam $timeSchedule dan simpan ke kolom yang sesuai
        foreach ($timeSchedule as $key => $fileName) {
            if ($key === $type) {
                $row->{"$type"} = $fileName;
            }
        }

        // Simpan perubahan di database
        $row->save();

        return redirect()->back()->with('success', 'File berhasil diupload.');
    }

    public function download($id, $type)
    {
        try {
            // Dekripsi ID jika dienkripsi
            $id = Crypt::decrypt($id);

            // Cari data berdasarkan ID
            $timeSchedule = TimeSchedule::findOrFail($id);

            // Tentukan file berdasarkan jenis
            $fileField = match ($type) {
                'undangan' => $timeSchedule->undangan,
                'materi' => $timeSchedule->materi,
                'absensi' => $timeSchedule->absensi,
                'notulen' => $timeSchedule->notulen,
                default => null,
            };

            if (!$fileField) {
                return redirect()->back()->with('error', 'Jenis file tidak valid.');
            }

            // Path file di storage
            $filePath = 'time-schedule/' . \Str::slug($timeSchedule->type) . "/" . $fileField;

            if (!Storage::exists($filePath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }

            return Storage::download($filePath);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh file.');
        }
    }
    public function verifikasiKehadiran(Request $request)
    {
        $request->validate([
            'rapat_id' => 'required|exists:time_schedules,id',
            'hadir_ids' => 'required|array',
            'hadir_ids.*' => 'exists:employees,id',
        ]);

        $rapat = TimeSchedule::findOrFail($request->rapat_id);
        $pesertaHadir = $request->hadir_ids;

        // Logika untuk memverifikasi kehadiran peserta tanpa menambahkan data baru
        foreach ($pesertaHadir as $pesertaId) {
            // Cek apakah kehadiran peserta sudah ada
            $existingAttendance = TimeScheduleEmployee::where('time_schedule_id', $rapat->id)
                ->where('employee_id', $pesertaId)
                ->first();

            if ($existingAttendance) {
                // Jika sudah ada, Anda bisa memperbarui status atau melakukan tindakan lain jika diperlukan
                $existingAttendance->status = 'hadir'; // Misalnya, memperbarui status
                $existingAttendance->save();
            }
        }

        return response()->json(['message' => 'Kehadiran berhasil diverifikasi.']);
    }
}
