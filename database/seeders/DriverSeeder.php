<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DriverSeeder extends Seeder
{
    public function run()
    {
        $drivers = [
            [
                'name'     => 'Marko Jovanović',
                'email'    => 'm.jovanovic@gsp.rs',
                'password' => 'driver123',
                'role'     => 'driver',
                'status'   => 'active',
                'phone'    => '+381 63 111 222',
                'depot'    => 'Garage B',
            ],
            [
                'name'     => 'Stefan Petrović',
                'email'    => 's.petrovic@gsp.rs',
                'password' => 'driver123',
                'role'     => 'driver',
                'status'   => 'active',
                'phone'    => '+381 63 222 333',
                'depot'    => 'Garage B',
            ],
            [
                'name'     => 'Milan Nikolić',
                'email'    => 'm.nikolic@gsp.rs',
                'password' => 'driver123',
                'role'     => 'driver',
                'status'   => 'active',
                'phone'    => '+381 63 333 444',
                'depot'    => 'Garage A',
            ],
            [
                'name'     => 'Nikola Đorđević',
                'email'    => 'n.djordjevic@gsp.rs',
                'password' => 'driver123',
                'role'     => 'driver',
                'status'   => 'active',
                'phone'    => '+381 63 444 555',
                'depot'    => 'Garage A',
            ],
            [
                'name'     => 'Aleksandar Stanković',
                'email'    => 'a.stankovic@gsp.rs',
                'password' => 'driver123',
                'role'     => 'driver',
                'status'   => 'active',
                'phone'    => '+381 63 555 666',
                'depot'    => 'Garage B',
            ],
            [
                'name'     => 'Vladimir Marinović',
                'email'    => 'v.marinovic@gsp.rs',
                'password' => 'driver123',
                'role'     => 'driver',
                'status'   => 'active',
                'phone'    => '+381 63 666 777',
                'depot'    => 'Garage A',
            ],
            [
                'name'     => 'Dragan Vasić',
                'email'    => 'd.vasic@gsp.rs',
                'password' => 'driver123',
                'role'     => 'driver',
                'status'   => 'active',
                'phone'    => '+381 63 777 888',
                'depot'    => 'Garage B',
            ],
            [
                'name'     => 'Bojan Lazić',
                'email'    => 'b.lazic@gsp.rs',
                'password' => 'driver123',
                'role'     => 'driver',
                'status'   => 'active',
                'phone'    => '+381 63 888 999',
                'depot'    => 'Garage A',
            ],
        ];

        foreach ($drivers as $driver) {
            User::firstOrCreate(
                ['email' => $driver['email']],
                $driver
            );
        }
    }
}