<?php

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('booking creation with save_biodata updates user profile biodata automatically', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'whatsapp' => null,
        'address' => null,
    ]);

    $car = Car::create([
        'name' => 'Toyota Avanza',
        'category' => 'MPV',
        'transmission' => 'Automatic',
        'price_per_day' => 350000,
        'image' => '/assets/images/avanza.png',
        'is_available' => true,
    ]);

    $file = UploadedFile::fake()->image('bukti.jpg');

    $response = $this
        ->actingAs($user)
        ->post('/booking', [
            'car_id' => $car->id,
            'car_name' => $car->name,
            'car_image' => $car->image,
            'car_price_per_day' => $car->price_per_day,
            'name' => 'Budi Santoso',
            'whatsapp' => '081333332215',
            'address' => 'Jl. Kolonel Sugiono 3, Pasuruan',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(2)->format('Y-m-d'),
            'duration' => 2,
            'total_price' => 700000,
            'payment_proof' => $file,
            'save_biodata' => 1,
        ]);

    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();

    $user->refresh();

    expect($user->whatsapp)->toBe('081333332215');
    expect($user->address)->toBe('Jl. Kolonel Sugiono 3, Pasuruan');
});
