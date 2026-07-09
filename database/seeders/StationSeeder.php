<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $data = json_decode(file_get_contents(database_path('data/stations.json')), true);

        $centerLat = 44.8161; // Trg Republike
        $centerLon = 20.4601;

        foreach ($data['stanice'] as $s) {

            $distance = $this->distance(
                $s['latitude'],
                $s['longitude'],
                $centerLat,
                $centerLon
            );

            if ($distance <= 5) {
                $zone = 'A';
            } elseif ($distance <= 15) {
                $zone = 'B';
            } else {
                $zone = 'A+B';
            }

            Station::create([
                'name' => $s['name'],
                'latitude' => $s['latitude'],
                'longitude' => $s['longitude'],
                'zone' => $zone,
            ]);
        }
    }

    private function distance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
