<?php

namespace Database\Seeders;

use App\Models\Line;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('data/lines.json')), true);
        foreach($data['linije'] as $l){
            Line::create([
                'name'=>$l['name'],
                'code'=>$l['code'],
                'type'=>$l['type'],
                'status'=>$l['status'],
            ]);
        }
    }
}
