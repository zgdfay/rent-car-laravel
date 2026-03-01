<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ProfileController;
use App\Models\Booking;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $cars = \App\Models\Car::latest()->get();
    return Inertia::render('welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'featuredCars' => $cars->take(3)->values(),
    ]);
});
Route::get('/list-car', function () {
    $cars = \App\Models\Car::latest()->get();
    return Inertia::render('list-car', [
        'cars' => $cars,
    ]);
});
Route::get('/tentang', function () {
    return Inertia::render('tentang');
});
Route::get('/kontak', function () {
    return Inertia::render('kontak');
});

// Dashboard - admin only
Route::get('/dashboard', function () {
    $bookings = Booking::latest()->get();
    $stats = [
        'total' => $bookings->count(),
        'pending' => $bookings->where('status', 'pending')->count(),
        'approved' => $bookings->where('status', 'approved')->count(),
        'rejected' => $bookings->where('status', 'rejected')->count(),
        'revenue' => $bookings->where('status', 'approved')->sum('total_price'),
    ];
    return Inertia::render('dashboard', [
        'bookings' => $bookings->load('user'),
        'stats' => $stats,
    ]);
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Booking
    Route::get('/booking/{id}', function ($id) {
        $car = \App\Models\Car::findOrFail($id);
        return Inertia::render('booking', [
            'car' => $car
        ]);
    })->name('booking');

    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

    Route::get('/booking/success/{booking}', [BookingController::class, 'success'])->name('booking.success');

    // Order history
    Route::get('/riwayat-order', function () {
        $bookings = auth()->user()->bookings()->latest()->get();
        return Inertia::render('riwayat-order', [
            'bookings' => $bookings,
        ]);
    })->name('riwayat-order');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/reservasi', function (\Illuminate\Http\Request $request) {
        $query = \App\Models\Booking::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('car_name', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        return Inertia::render('admin/reservasi', [
            'bookings' => $query->get(),
            'filters' => $request->only(['status', 'search']),
        ]);
    })->name('admin.reservasi');

    // Manajemen Mobil
    Route::get('/admin/mobil', function () {
        $cars = \App\Models\Car::latest()->get();
        return Inertia::render('admin/mobil', [
            'cars' => $cars,
        ]);
    })->name('admin.mobil');
    Route::post('/admin/mobil', [CarController::class, 'store'])->name('admin.mobil.store');
    Route::post('/admin/mobil/{car}', [CarController::class, 'update'])->name('admin.mobil.update');
    Route::delete('/admin/mobil/{car}', [CarController::class, 'destroy'])->name('admin.mobil.destroy');

    // Manajemen User
    Route::get('/admin/users', function () {
        $users = \App\Models\User::withCount('bookings')->latest()->get();
        return Inertia::render('admin/users', [
            'users' => $users,
        ]);
    })->name('admin.users');

    Route::post('/admin/users', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,customer',
        ]);
        $validated['password'] = bcrypt($validated['password']);
        \App\Models\User::create($validated);
        return back()->with('success', 'User berhasil ditambahkan.');
    })->name('admin.users.store');

    Route::patch('/admin/users/{user}/role', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
        $validated = $request->validate([
            'role' => 'required|in:admin,customer',
        ]);
        $user->update(['role' => $validated['role']]);
        return back()->with('success', 'Role user berhasil diperbarui.');
    })->name('admin.users.updateRole');

    Route::put('/admin/users/{user}', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,customer',
        ]);
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        $user->update($validated);
        return back()->with('success', 'Data user berhasil diperbarui.');
    })->name('admin.users.update');

    Route::delete('/admin/users/{user}', function (\App\Models\User $user) {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    })->name('admin.users.destroy');

    // Laporan
    Route::get('/admin/laporan', function () {
        $bookings = Booking::latest()->get();
        $approvedBookings = $bookings->where('status', 'approved');

        // Monthly revenue (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('Y-m');
            $label = $date->translatedFormat('M Y');
            $revenue = Booking::where('status', 'approved')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_price');
            $count = Booking::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthlyRevenue[] = [
                'month' => $label,
                'revenue' => $revenue,
                'count' => $count,
            ];
        }

        // Top cars by bookings
        $topCars = Booking::select('car_name', 'car_image')
            ->selectRaw('COUNT(*) as total_bookings')
            ->selectRaw('SUM(CASE WHEN status = "approved" THEN total_price ELSE 0 END) as total_revenue')
            ->groupBy('car_name', 'car_image')
            ->orderByDesc('total_bookings')
            ->limit(5)
            ->get();

        // Stats summary
        $stats = [
            'total_bookings' => $bookings->count(),
            'total_revenue' => $approvedBookings->sum('total_price'),
            'total_users' => \App\Models\User::count(),
            'total_cars' => \App\Models\Car::count(),
            'pending' => $bookings->where('status', 'pending')->count(),
            'approved' => $approvedBookings->count(),
            'rejected' => $bookings->where('status', 'rejected')->count(),
            'avg_revenue' => $approvedBookings->count() > 0 ? round($approvedBookings->avg('total_price')) : 0,
        ];

        // Recent bookings
        $recentBookings = Booking::with('user')->latest()->limit(5)->get();

        return Inertia::render('admin/laporan', [
            'stats' => $stats,
            'monthlyRevenue' => $monthlyRevenue,
            'topCars' => $topCars,
            'recentBookings' => $recentBookings,
        ]);
    })->name('admin.laporan');

    Route::patch('/admin/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('admin.bookings.updateStatus');
    Route::post('/admin/bookings/{booking}/return', [BookingController::class, 'processReturn'])->name('admin.bookings.return');
    Route::delete('/admin/bookings/{booking}', [BookingController::class, 'destroy'])->name('admin.bookings.destroy');
});

require __DIR__ . '/auth.php';
