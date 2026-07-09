<?php

namespace Database\Seeders;

use App\Models\LineStation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LineStationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=json_decode(file_get_contents(database_path('data/line_stations.json')),true);
        foreach($data['line_stations'] as $ls){
            LineStation::create([
                'line_id'=>$ls['line_id'],
                'station_id'=>$ls['station_id'],
                'order'=>$ls['order'],
                'distance_from_pre'=>$ls['distance_from_prev'],
            ]);
        }
    }
}
