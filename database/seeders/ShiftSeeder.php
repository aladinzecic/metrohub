<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    public function run()
    {
        $dispatcher = User::where('role', 'dispatcher')->first();

        if (!$dispatcher) {
            $this->command->error('Nema dispečera u bazi!');
            return;
        }

        $drivers  = User::where('role', 'driver')->where('status', 'active')->get();
        $vehicles = Vehicle::where('status', 'in_service')->get();

        if ($drivers->isEmpty()) {
            $this->command->error('Nema vozača u bazi!');
            return;
        }

        if ($vehicles->isEmpty()) {
            $this->command->error('Nema vozila u bazi!');
            return;
        }

        $shifts = [
            ['start' => '06:00', 'end' => '14:00'],
            ['start' => '14:00', 'end' => '22:00'],
            ['start' => '22:00', 'end' => '06:00'],
        ];

        foreach ($drivers as $index => $driver) {
            $vehicle = $vehicles[$index % $vehicles->count()];
            $shift   = $shifts[$index % 3];

            Shift::firstOrCreate(
                [
                    'driver_id'  => $driver->id,
                    'vehicle_id' => $vehicle->id,
                    'date'       => today()->toDateString(),
                ],
                [
                    'dispatcher_id' => $dispatcher->id,
                    'status'        => 'scheduled',
                    'start_time'    => $shift['start'],
                    'end_time'      => $shift['end'],
                    'notes'         => null,
                ]
            );

            $this->command->info("✓ {$driver->name} → {$vehicle->name} · {$shift['start']} – {$shift['end']}");
        }
    }
}