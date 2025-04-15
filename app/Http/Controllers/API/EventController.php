<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        return response()->json(Event::all());
    }

    public function store(Request $request)
    {
        $event = Event::create($request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
        ]));

        return response()->json($event, 201);
    }

    public function update(Request $request, Event $event)
    {
        $event->update($request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
        ]));

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(null, 204);
    }

    public function getEmployeeBirthdays()
    {
        // Ambil data karyawan yang ulang tahun pada bulan ini
        $birthdays = Employee::whereMonth('birthdate', now()->month)
            ->orderByRaw('DAY(birthdate)')
            ->get(['id', 'fullname', 'birthdate', 'foto', 'gender', 'organization_id']);

        $birthdays = $birthdays->map(function ($employee) {
            $fullName = $employee->fullname;
            $title = ucfirst($fullName);
            $start = Carbon::parse($employee->birthdate)->setYear(now()->year)->format('Y-m-d');
            $color = $employee->gender === 'Perempuan' ? '#3498db' : '#3498db';
            return [
                'id'    => $employee->id,
                'title' => $title,
                'start' => $start,
                'color' => $color,
            ];
        });

        return response()->json($birthdays);
    }
}
