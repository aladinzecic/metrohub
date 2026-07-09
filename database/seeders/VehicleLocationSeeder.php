<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\Line;
use App\Models\VehicleLocation;

class VehicleLocationSeeder extends Seeder
{
    public function run()
    {
        $lines = Line::with(['stations' => function($q) {
            $q->orderBy('line_stations.order');
        }])->where('status', 'active')->get();

        if ($lines->isEmpty()) {
            $this->command->error('Nema aktivnih linija!');
            return;
        }

        $vehicles = [
            'BG-123-AB', // Bus 42
            'BG-456-CD', // Bus 17
            'BG-789-EF', // Bus 33
            'BG-012-GH', // Tram 08
            'BG-345-IJ', // Bus 55
            'BG-901-MN', // Bus 61
            'BG-234-OP', // Bus 74
            'BG-567-QR', // Bus 88
            'BG-890-ST', // Bus 95
            'BG-111-UV', // Tram 03
            'BG-222-WX', // Tram 11
            'BG-444-AA', // Bus 47
            'BG-555-BB', // Bus 66
        ];

        foreach ($vehicles as $index => $plate) {
            $vehicle = Vehicle::where('plate', $plate)->first();

            if (!$vehicle) {
                $this->command->warn("Vozilo sa tablicom {$plate} nije pronađeno.");
                continue;
            }

            $line     = $lines[$index % $lines->count()];
            $stations = $line->stations;

            if ($stations->isEmpty()) {
                $this->command->warn("Linija {$line->code} nema stanica.");
                continue;
            }

            $stationIndex = $index % $stations->count();
            $station      = $stations[$stationIndex];

            VehicleLocation::updateOrCreate(
                ['vehicle_id' => $vehicle->id],
                [
                    'latitude'              => $station->latitude + (rand(-10, 10) / 10000),
                    'longitude'             => $station->longitude + (rand(-10, 10) / 10000),
                    'speed'                 => rand(20, 50),
                    'line_id'               => $line->id,
                    'current_station_index' => $stationIndex,
                    'recorded_at'           => now(),
                ]
            );

            $this->command->info("✓ {$vehicle->name} → {$line->code} → {$station->name}");
        }
    }
}