<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|integer',
            'car_name' => 'required|string|max:255',
            'car_image' => 'nullable|string|max:500',
            'car_price_per_day' => 'required|integer',
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'address' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'duration' => 'required|integer|min:1',
            'total_price' => 'required|integer|min:0',
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Store the payment proof image
        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'car_id' => $validated['car_id'],
            'car_name' => $validated['car_name'],
            'car_image' => $validated['car_image'],
            'car_price_per_day' => $validated['car_price_per_day'],
            'name' => $validated['name'],
            'whatsapp' => $validated['whatsapp'],
            'address' => $validated['address'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'duration' => $validated['duration'],
            'total_price' => $validated['total_price'],
            'payment_proof' => $path,
            'status' => Booking::STATUS_PENDING,
        ]);

        // Car is still available until admin approves, or we can reserve it now. 
        // For now, let's keep it available until approved by admin to prevent spam booking blocking cars.

        return redirect()->route('booking.success', $booking->id);
    }

    public function success(Booking $booking)
    {
        // Ensure the user can only see their own booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('booking-success', [
            'booking' => $booking,
        ]);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $booking->update(['status' => $validated['status']]);

        // Update car availability based on status
        $car = Car::find($booking->car_id);
        if ($car) {
            if ($validated['status'] === Booking::STATUS_APPROVED) {
                // If approved, car is no longer available
                $car->update(['is_available' => false]);
            } elseif ($validated['status'] === Booking::STATUS_REJECTED) {
                // If rejected, car becomes available again (if it was the one holding it)
                $car->update(['is_available' => true]);
            }
        }

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function processReturn(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'return_date' => 'required|date',
            'late_fee' => 'required|numeric|min:0',
            'damage_fee' => 'required|numeric|min:0',
        ]);

        $booking->update([
            'status' => Booking::STATUS_COMPLETED,
            'return_date' => $validated['return_date'],
            'late_fee' => $validated['late_fee'],
            'damage_fee' => $validated['damage_fee'],
            'total_price' => $booking->total_price + $validated['late_fee'] + $validated['damage_fee'],
        ]);

        // Free up the car
        $car = Car::find($booking->car_id);
        if ($car) {
            $car->update(['is_available' => true]);
        }

        return back()->with('success', 'Pengembalian mobil berhasil diproses.');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->payment_proof) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($booking->payment_proof);
        }

        // If we delete an approved booking, free up the car
        if ($booking->status === Booking::STATUS_APPROVED) {
            $car = Car::find($booking->car_id);
            if ($car) {
                $car->update(['is_available' => true]);
            }
        }

        $booking->delete();
        return back()->with('success', 'Booking berhasil dihapus.');
    }
}
