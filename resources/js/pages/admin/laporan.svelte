<script module>
    export { default as layout } from '@/layouts/authenticated-layout.svelte';
</script>

<script>
    import {
        TrendingUp,
        Users,
        Car,
        Calendar,
        DollarSign,
        Clock,
        CheckCircle2,
        XCircle,
        BarChart3,
        Trophy,
        Printer,
        Download,
    } from 'lucide-svelte';
    import { Button } from '@/components/ui/button/index.js';

    let { stats = {}, monthlyRevenue = [], topCars = [], recentBookings = [] } = $props();

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        });
    }

    // Calculate max revenue for bar chart scaling
    const maxRevenue = $derived(Math.max(...monthlyRevenue.map((m) => m.revenue), 1));
    const maxCount = $derived(Math.max(...monthlyRevenue.map((m) => m.count), 1));

    function getStatusColor(status) {
        switch (status) {
            case 'pending':
                return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300';
            case 'approved':
                return 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300';
            case 'rejected':
                return 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    function getStatusLabel(status) {
        switch (status) {
            case 'pending':
                return 'Menunggu';
            case 'approved':
                return 'Disetujui';
            case 'rejected':
                return 'Ditolak';
            default:
                return status;
        }
    }

    // Booking status distribution for donut
    const totalBookings = $derived(stats.pending + stats.approved + stats.rejected);
    const pendingPct = $derived(
        totalBookings > 0 ? ((stats.pending / totalBookings) * 100).toFixed(1) : 0,
    );
    const approvedPct = $derived(
        totalBookings > 0 ? ((stats.approved / totalBookings) * 100).toFixed(1) : 0,
    );
    const rejectedPct = $derived(
        totalBookings > 0 ? ((stats.rejected / totalBookings) * 100).toFixed(1) : 0,
    );

    function printReport() {
        window.print();
    }

    function downloadCSV() {
        // Build CSV from monthly revenue + stats
        let csv = 'Laporan DRS Rental\n\n';

        // Summary
        csv += 'RINGKASAN\n';
        csv += `Total Pendapatan,${stats.total_revenue || 0}\n`;
        csv += `Total Booking,${stats.total_bookings || 0}\n`;
        csv += `Booking Disetujui,${stats.approved || 0}\n`;
        csv += `Booking Menunggu,${stats.pending || 0}\n`;
        csv += `Booking Ditolak,${stats.rejected || 0}\n`;
        csv += `Total User,${stats.total_users || 0}\n`;
        csv += `Total Mobil,${stats.total_cars || 0}\n`;
        csv += `Rata-rata per Booking,${stats.avg_revenue || 0}\n\n`;

        // Monthly revenue
        csv += 'PENDAPATAN BULANAN\n';
        csv += 'Bulan,Pendapatan,Jumlah Booking\n';
        monthlyRevenue.forEach((m) => {
            csv += `${m.month},${m.revenue},${m.count}\n`;
        });
        csv += '\n';

        // Top cars
        csv += 'MOBIL TERPOPULER\n';
        csv += 'Nama Mobil,Jumlah Booking,Total Pendapatan\n';
        topCars.forEach((car) => {
            csv += `${car.car_name},${car.total_bookings},${car.total_revenue}\n`;
        });
        csv += '\n';

        // Recent bookings
        csv += 'BOOKING TERAKHIR\n';
        csv += 'Nama,Mobil,Total,Status,Tanggal\n';
        recentBookings.forEach((b) => {
            csv += `${b.name},${b.car_name},${b.total_price},${getStatusLabel(b.status)},${formatDate(b.created_at)}\n`;
        });

        // Download
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.setAttribute('href', url);
        a.setAttribute(
            'download',
            `laporan-drs-rental-${new Date().toISOString().slice(0, 10)}.csv`,
        );
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
</script>

<!-- Header -->
<div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Ringkasan data dan statistik penyewaan kendaraan.
        </p>
    </div>
    <div class="flex gap-2 print:hidden">
        <Button onclick={downloadCSV} variant="outline" class="h-9 gap-1.5 rounded-xl text-sm">
            <Download class="h-4 w-4" /> Download CSV
        </Button>
        <Button
            onclick={printReport}
            class="h-9 gap-1.5 rounded-xl bg-blue-600 text-sm text-white hover:bg-blue-700"
        >
            <Printer class="h-4 w-4" /> Cetak Laporan
        </Button>
    </div>
</div>

<!-- Overview Stats -->
<div class="mb-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
    <div
        class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
    >
        <div class="mb-3 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan</p>
            <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/30"
            >
                <TrendingUp class="h-5 w-5 text-emerald-600 dark:text-emerald-400" />
            </div>
        </div>
        <p class="text-2xl font-extrabold text-gray-900 dark:text-white">
            {formatCurrency(stats.total_revenue || 0)}
        </p>
        <p class="mt-1 text-xs text-gray-400">Dari {stats.approved || 0} booking disetujui</p>
    </div>

    <div
        class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
    >
        <div class="mb-3 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Booking</p>
            <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30"
            >
                <Calendar class="h-5 w-5 text-blue-600 dark:text-blue-400" />
            </div>
        </div>
        <p class="text-3xl font-extrabold text-gray-900 dark:text-white">
            {stats.total_bookings || 0}
        </p>
        <p class="mt-1 text-xs text-gray-400">
            Rata-rata {formatCurrency(stats.avg_revenue || 0)}/booking
        </p>
    </div>

    <div
        class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
    >
        <div class="mb-3 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total User</p>
            <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 dark:bg-purple-900/30"
            >
                <Users class="h-5 w-5 text-purple-600 dark:text-purple-400" />
            </div>
        </div>
        <p class="text-3xl font-extrabold text-gray-900 dark:text-white">
            {stats.total_users || 0}
        </p>
        <p class="mt-1 text-xs text-gray-400">Pengguna terdaftar</p>
    </div>

    <div
        class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
    >
        <div class="mb-3 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Mobil</p>
            <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 dark:bg-orange-900/30"
            >
                <Car class="h-5 w-5 text-orange-600 dark:text-orange-400" />
            </div>
        </div>
        <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{stats.total_cars || 0}</p>
        <p class="mt-1 text-xs text-gray-400">Kendaraan tersedia</p>
    </div>
</div>

<!-- Charts Row -->
<div class="mb-6 grid gap-6 lg:grid-cols-3">
    <!-- Monthly Revenue Chart -->
    <div
        class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 lg:col-span-2 dark:bg-gray-900 dark:ring-white/10"
    >
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Pendapatan Bulanan</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">6 bulan terakhir</p>
            </div>
            <BarChart3 class="h-5 w-5 text-gray-400" />
        </div>

        {#if monthlyRevenue.length > 0}
            <div class="flex h-48 items-end gap-3">
                {#each monthlyRevenue as m}
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <div
                            class="text-[10px] font-bold whitespace-nowrap text-gray-900 dark:text-white"
                        >
                            {m.revenue > 0 ? formatCurrency(m.revenue) : '-'}
                        </div>
                        <div class="flex w-full flex-col items-center gap-1">
                            <!-- Revenue bar -->
                            <div
                                class="w-full rounded-t-lg bg-blue-500 transition-all duration-500 dark:bg-blue-600"
                                style="height: {maxRevenue > 0
                                    ? Math.max((m.revenue / maxRevenue) * 140, 4)
                                    : 4}px"
                            ></div>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] font-medium text-gray-500 dark:text-gray-400">
                                {m.month}
                            </p>
                            <p class="text-[10px] text-gray-400">{m.count} booking</p>
                        </div>
                    </div>
                {/each}
            </div>
        {:else}
            <div class="flex h-48 items-center justify-center text-sm text-gray-400">
                Belum ada data
            </div>
        {/if}
    </div>

    <!-- Booking Status Distribution -->
    <div
        class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
    >
        <h2 class="mb-1 text-lg font-bold text-gray-900 dark:text-white">Status Booking</h2>
        <p class="mb-6 text-xs text-gray-500 dark:text-gray-400">Distribusi status reservasi</p>

        {#if totalBookings > 0}
            <!-- Stacked bar -->
            <div class="mb-6 flex h-6 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                {#if stats.approved > 0}
                    <div
                        class="bg-green-500 transition-all duration-500"
                        style="width: {approvedPct}%"
                    ></div>
                {/if}
                {#if stats.pending > 0}
                    <div
                        class="bg-yellow-400 transition-all duration-500"
                        style="width: {pendingPct}%"
                    ></div>
                {/if}
                {#if stats.rejected > 0}
                    <div
                        class="bg-red-500 transition-all duration-500"
                        style="width: {rejectedPct}%"
                    ></div>
                {/if}
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-green-500"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Disetujui</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-gray-900 dark:text-white"
                            >{stats.approved}</span
                        >
                        <span class="ml-1 text-xs text-gray-400">({approvedPct}%)</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-yellow-400"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Menunggu</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-gray-900 dark:text-white"
                            >{stats.pending}</span
                        >
                        <span class="ml-1 text-xs text-gray-400">({pendingPct}%)</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-red-500"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Ditolak</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-gray-900 dark:text-white"
                            >{stats.rejected}</span
                        >
                        <span class="ml-1 text-xs text-gray-400">({rejectedPct}%)</span>
                    </div>
                </div>
            </div>
        {:else}
            <div class="flex h-32 items-center justify-center text-sm text-gray-400">
                Belum ada data booking
            </div>
        {/if}
    </div>
</div>

<!-- Bottom Row -->
<div class="grid gap-6 lg:grid-cols-2">
    <!-- Top Cars -->
    <div
        class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
    >
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Mobil Terpopuler</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Berdasarkan jumlah booking</p>
            </div>
            <Trophy class="h-5 w-5 text-yellow-500" />
        </div>

        {#if topCars.length === 0}
            <div class="py-8 text-center text-sm text-gray-400">Belum ada data</div>
        {:else}
            <div class="space-y-3">
                {#each topCars as car, i}
                    <div
                        class="flex items-center gap-4 rounded-xl p-3 {i === 0
                            ? 'bg-yellow-50 ring-1 ring-yellow-200 dark:bg-yellow-900/10 dark:ring-yellow-800'
                            : 'hover:bg-gray-50 dark:hover:bg-gray-800/50'}"
                    >
                        <div
                            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-bold {i ===
                            0
                                ? 'bg-yellow-400 text-yellow-900'
                                : i === 1
                                  ? 'bg-gray-300 text-gray-700'
                                  : i === 2
                                    ? 'bg-orange-300 text-orange-800'
                                    : 'bg-gray-100 text-gray-500 dark:bg-gray-800'}"
                        >
                            {i + 1}
                        </div>
                        <div
                            class="flex h-9 w-12 shrink-0 items-center justify-center rounded-lg bg-gray-100 p-1 dark:bg-gray-800"
                        >
                            {#if car.car_image}
                                <img
                                    src={car.car_image}
                                    alt={car.car_name}
                                    class="h-full w-full object-contain"
                                />
                            {:else}
                                <Car class="h-5 w-5 text-gray-400" />
                            {/if}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">
                                {car.car_name}
                            </p>
                            <p class="text-[10px] text-gray-400">{car.total_bookings} booking</p>
                        </div>
                        <span
                            class="text-sm font-bold whitespace-nowrap text-green-600 dark:text-green-400"
                            >{formatCurrency(car.total_revenue)}</span
                        >
                    </div>
                {/each}
            </div>
        {/if}
    </div>

    <!-- Recent Bookings -->
    <div
        class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
    >
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Booking Terakhir</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">5 reservasi terbaru</p>
            </div>
            <Calendar class="h-5 w-5 text-gray-400" />
        </div>

        {#if recentBookings.length === 0}
            <div class="py-8 text-center text-sm text-gray-400">Belum ada booking</div>
        {:else}
            <div class="space-y-3">
                {#each recentBookings as booking}
                    <div
                        class="flex items-center gap-3 rounded-xl p-3 transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50"
                    >
                        <div
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400"
                        >
                            {booking.name?.charAt(0)?.toUpperCase() || 'U'}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-gray-900 dark:text-white">
                                {booking.name}
                            </p>
                            <p class="text-[10px] text-gray-400">
                                {booking.car_name} · {formatDate(booking.created_at)}
                            </p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">
                                {formatCurrency(booking.total_price)}
                            </p>
                            <span
                                class="inline-flex rounded-full px-1.5 py-0.5 text-[9px] font-bold {getStatusColor(
                                    booking.status,
                                )}"
                            >
                                {getStatusLabel(booking.status)}
                            </span>
                        </div>
                    </div>
                {/each}
            </div>
        {/if}
    </div>
</div>

<style>
    @media print {
        :global(nav),
        :global([data-sidebar]),
        :global(header),
        :global([data-slot='sidebar-trigger']) {
            display: none !important;
        }
        :global(body) {
            background: white !important;
        }
        :global([data-slot='sidebar-inset']) {
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>
