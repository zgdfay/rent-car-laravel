<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Car;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@dsr.com'],
            [
                'name' => 'Admin DSR',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'customer',
            ]
        );

        // Seed cars from JSON
        $carsJson = file_get_contents(resource_path('js/data/cars-data.json'));
        $cars = json_decode($carsJson, true);
        foreach ($cars as $car) {
            $price = (int) preg_replace('/[^0-9]/', '', $car['price']);
            Car::firstOrCreate(
                ['name' => $car['name']],
                [
                    'category' => $car['category'],
                    'price_per_day' => $price,
                    'image' => $car['image'],
                    'seats' => (int) $car['features']['seats'],
                    'transmission' => $car['features']['transmission'],
                    'fuel' => $car['features']['fuel'],
                    'is_available' => $car['isAvailable'],
                ]
            );
        }
    }
}
