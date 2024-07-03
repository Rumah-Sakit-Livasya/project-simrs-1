<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [
            [
                'name' => 'dayoff',
                'time_in' => '00:00',
                'time_out' => '00:00',
                'status' => 'on'
            ],
            [
                'name' => 'O',
                'time_in' => '08:00',
                'time_out' => '16:00',
                'status' => 'on'
            ],
            [
                'name' => 'O Sabtu',
                'time_in' => '08:00',
                'time_out' => '14:00',
                'status' => 'on'
            ],
            [
                'name' => 'TMP',
                'time_in' => '07:00',
                'time_out' => '14:00',
                'status' => 'on'
            ],
            [
                'name' => 'TMS',
                'time_in' => '14:00',
                'time_out' => '21:00',
                'status' => 'on'
            ],
            [
                'name' => 'TMM',
                'time_in' => '21:00',
                'time_out' => '07:00',
                'status' => 'on'
            ],
            [
                'name' => 'PMP',
                'time_in' => '07:00',
                'time_out' => '14:30',
                'status' => 'on'
            ],
            [
                'name' => 'PMS',
                'time_in' => '13:30',
                'time_out' => '21:00',
                'status' => 'on'
            ],
            [
                'name' => 'PMM',
                'time_in' => '21:00',
                'time_out' => '07:00',
                'status' => 'on'
            ],
            [
                'name' => 'FOP',
                'time_in' => '07:00',
                'time_out' => '14:00',
                'status' => 'on'
            ],
            [
                'name' => 'FOS',
                'time_in' => '14:00',
                'time_out' => '21:00',
                'status' => 'on'
            ],
            [
                'name' => 'FOM',
                'time_in' => '21:00',
                'time_out' => '07:00',
                'status' => 'on'
            ],
            [
                'name' => 'FOP Longshift',
                'time_in' => '07:00',
                'time_out' => '19:00',
                'status' => 'on'
            ],
            [
                'name' => 'FOM Longshift',
                'time_in' => '19:00',
                'time_out' => '07:00',
                'status' => 'on'
            ],
            [
                'name' => 'UP',
                'time_in' => '07:00',
                'time_out' => '19:00',
                'status' => 'on'
            ],
            [
                'name' => 'UM',
                'time_in' => '19:00',
                'time_out' => '07:00',
                'status' => 'on'
            ],
            [
                'name' => 'PSRSP',
                'time_in' => '07:00',
                'time_out' => '15:00',
                'status' => 'on'
            ],
            [
                'name' => 'PSRSMid',
                'time_in' => '09:00',
                'time_out' => '17:00',
                'status' => 'on'
            ],
            [
                'name' => 'L',
                'time_in' => '06:00',
                'time_out' => '18:00',
                'status' => 'on'
            ],
            [
                'name' => 'G',
                'time_in' => '05:30',
                'time_out' => '17:00',
                'status' => 'on'
            ],
            [
                'name' => 'AS',
                'time_in' => '12:00',
                'time_out' => '20:00',
                'status' => 'on'
            ],
            [
                'name' => 'GP',
                'time_in' => '05:30',
                'time_out' => '13:30',
                'status' => 'on'
            ],
            [
                'name' => 'GS',
                'time_in' => '10:00',
                'time_out' => '18:00',
                'status' => 'on'
            ],
            [
                'name' => 'G Longshift',
                'time_in' => '05:30',
                'time_out' => '17:30',
                'status' => 'on'
            ],
            [
                'name' => 'CSSD Pagi',
                'time_in' => '07:00',
                'time_out' => '14:30',
                'status' => 'on'
            ],
            [
                'name' => 'CSSD Siang',
                'time_in' => '10:00',
                'time_out' => '17:30',
                'status' => 'on'
            ],
            [
                'name' => 'CSSD Longshift',
                'time_in' => '07:00',
                'time_out' => '17:30',
                'status' => 'on'
            ],
            [
                'name' => 'CSSD FULL',
                'time_in' => '09:00',
                'time_out' => '16:30',
                'status' => 'on'
            ],
            [
                'name' => 'UPJ',
                'time_in' => '07:00',
                'time_out' => '16:00',
                'status' => 'on'
            ],
            [
                'name' => 'OPPJ',
                'time_in' => '07:00',
                'time_out' => '15:00',
                'status' => 'on'
            ],
            [
                'name' => 'OPPJ SABTU',
                'time_in' => '07:00',
                'time_out' => '14:00',
                'status' => 'on'
            ],
            [
                'name' => 'TMMID',
                'time_in' => '11:00',
                'time_out' => '19:00',
                'status' => 'on'
            ],
            [
                'name' => 'MOD',
                'time_in' => '08:00',
                'time_out' => '07:00',
                'status' => 'on'
            ],
            [
                'name' => 'UGP',
                'time_in' => '05:30',
                'time_out' => '13:30',
                'status' => 'on'
            ],
            [
                'name' => 'UGS',
                'time_in' => '11:00',
                'time_out' => '19:00',
                'status' => 'on'
            ],
            [
                'name' => 'PSRSS',
                'time_in' => '14:00',
                'time_out' => '22:00',
                'status' => 'on'
            ],
            [
                'name' => 'National Holiday',
                'time_in' => '00:00',
                'time_out' => '00:00',
                'status' => 'on'
            ],
            [
                'name' => 'PTP',
                'time_in' => '07:00',
                'time_out' => '19:00',
                'status' => 'on'
            ],
            [
                'name' => 'PTM',
                'time_in' => '19:00',
                'time_out' => '07:00',
                'status' => 'on'
            ],
        ];

        foreach ($shifts as $data) {
            Shift::create($data);
        }
    }
}
