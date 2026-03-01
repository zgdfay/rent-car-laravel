<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price_per_day' => 'required|integer|min:0',
            'seats' => 'required|integer|min:1',
            'transmission' => 'required|string|max:100',
            'fuel' => 'required|string|max:100',
            'is_available' => 'boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $validated;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('car-images', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $data['is_available'] = $request->boolean('is_available', true);

        Car::create($data);

        return back()->with('success', 'Mobil berhasil ditambahkan.');
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price_per_day' => 'required|integer|min:0',
            'seats' => 'required|integer|min:1',
            'transmission' => 'required|string|max:100',
            'fuel' => 'required|string|max:100',
            'is_available' => 'boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $validated;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('car-images', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $data['is_available'] = $request->boolean('is_available', true);

        $car->update($data);

        return back()->with('success', 'Mobil berhasil diperbarui.');
    }

    public function destroy(Car $car)
    {
        $car->delete();

        return back()->with('success', 'Mobil berhasil dihapus.');
    }
}
