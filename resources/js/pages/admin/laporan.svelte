<script module>
    export { default as layout } from '@/layouts/authenticated-layout.svelte';
</script>

<script>
    import { TrendingUp, Users, Car, Calendar, DollarSign, Clock, CheckCircle2, XCircle, BarChart3, Trophy, Printer, Download } from 'lucide-svelte';
    import { Button } from '@/components/ui/button/index.js';

    let { stats = {}, monthlyRevenue = [], topCars = [], recentBookings = [] } = $props();

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency', currency: 'IDR', minimumFractionDigits: 0,
        }).format(amount);
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    // Calculate max revenue for bar chart scaling
    const maxRevenue = $derived(Math.max(...monthlyRevenue.map(m => m.revenue), 1));
    const maxCount = $derived(Math.max(...monthlyRevenue.map(m => m.count), 1));

    function getStatusColor(status) {
        switch (status) {
            case 'pending': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300';
            case 'approved': return 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300';
            case 'rejected': return 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function getStatusLabel(status) {
        switch (status) {
            case 'pending': return 'Menunggu';
            case 'approved': return 'Disetujui';
            case 'rejected': return 'Ditolak';
            default: return status;
        }
    }

    // Booking status distribution for donut
    const totalBookings = $derived(stats.pending + stats.approved + stats.rejected);
    const pendingPct = $derived(totalBookings > 0 ? ((stats.pending / totalBookings) * 100).toFixed(1) : 0);
    const approvedPct = $derived(totalBookings > 0 ? ((stats.approved / totalBookings) * 100).toFixed(1) : 0);
    const rejectedPct = $derived(totalBookings > 0 ? ((stats.rejected / totalBookings) * 100).toFixed(1) : 0);

    function printReport() {
        window.print();
    }

    function downloadCSV() {
        // Build CSV from monthly revenue + stats
        let csv = 'Laporan DSR Rental\n\n';

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
        monthlyRevenue.forEach(m => {
            csv += `${m.month},${m.revenue},${m.count}\n`;
        });
        csv += '\n';

        // Top cars
        csv += 'MOBIL TERPOPULER\n';
        csv += 'Nama Mobil,Jumlah Booking,Total Pendapatan\n';
        topCars.forEach(car => {
            csv += `${car.car_name},${car.total_bookings},${car.total_revenue}\n`;
        });
        csv += '\n';

        // Recent bookings
        csv += 'BOOKING TERAKHIR\n';
        csv += 'Nama,Mobil,Total,Status,Tanggal\n';
        recentBookings.forEach(b => {
            csv += `${b.name},${b.car_name},${b.total_price},${getStatusLabel(b.status)},${formatDate(b.created_at)}\n`;
        });

        // Download
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.setAttribute('href', url);
        a.setAttribute('download', `laporan-dsr-rental-${new Date().toISOString().slice(0, 10)}.csv`);
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
</script>

<style>
    @media print {
        :global(nav), :global([data-sidebar]), :global(header), :global([data-slot="sidebar-trigger"]) {
            display: none !important;
        }
        :global(body) {
            background: white !important;
        }
        :global([data-slot="sidebar-inset"]) {
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ringkasan data dan statistik penyewaan kendaraan.</p>
    </div>
    <div class="flex gap-2 print:hidden">
        <Button onclick={downloadCSV} variant="outline" class="rounded-xl gap-1.5 h-9 text-sm">
            <Download class="w-4 h-4" /> Download CSV
        </Button>
        <Button onclick={printReport} class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl gap-1.5 h-9 text-sm">
            <Printer class="w-4 h-4" /> Cetak Laporan
        </Button>
    </div>
</div>

<!-- Overview Stats -->
<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mb-6">
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan</p>
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                <TrendingUp class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
            </div>
        </div>
        <p class="text-2xl font-extrabold text-gray-900 dark:text-white">{formatCurrency(stats.total_revenue || 0)}</p>
        <p class="text-xs text-gray-400 mt-1">Dari {stats.approved || 0} booking disetujui</p>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Booking</p>
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <Calendar class="w-5 h-5 text-blue-600 dark:text-blue-400" />
            </div>
        </div>
        <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{stats.total_bookings || 0}</p>
        <p class="text-xs text-gray-400 mt-1">Rata-rata {formatCurrency(stats.avg_revenue || 0)}/booking</p>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total User</p>
            <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <Users class="w-5 h-5 text-purple-600 dark:text-purple-400" />
            </div>
        </div>
        <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{stats.total_users || 0}</p>
        <p class="text-xs text-gray-400 mt-1">Pengguna terdaftar</p>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Mobil</p>
            <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                <Car class="w-5 h-5 text-orange-600 dark:text-orange-400" />
            </div>
        </div>
        <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{stats.total_cars || 0}</p>
        <p class="text-xs text-gray-400 mt-1">Kendaraan tersedia</p>
    </div>
</div>

<!-- Charts Row -->
<div class="grid gap-6 lg:grid-cols-3 mb-6">
    <!-- Monthly Revenue Chart -->
    <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Pendapatan Bulanan</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">6 bulan terakhir</p>
            </div>
            <BarChart3 class="w-5 h-5 text-gray-400" />
        </div>

        {#if monthlyRevenue.length > 0}
            <div class="flex items-end gap-3 h-48">
                {#each monthlyRevenue as m}
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="text-[10px] font-bold text-gray-900 dark:text-white whitespace-nowrap">
                            {m.revenue > 0 ? formatCurrency(m.revenue) : '-'}
                        </div>
                        <div class="w-full flex flex-col gap-1 items-center">
                            <!-- Revenue bar -->
                            <div 
                                class="w-full rounded-t-lg bg-blue-500 dark:bg-blue-600 transition-all duration-500"
                                style="height: {maxRevenue > 0 ? Math.max((m.revenue / maxRevenue) * 140, 4) : 4}px"
                            ></div>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] font-medium text-gray-500 dark:text-gray-400">{m.month}</p>
                            <p class="text-[10px] text-gray-400">{m.count} booking</p>
                        </div>
                    </div>
                {/each}
            </div>
        {:else}
            <div class="h-48 flex items-center justify-center text-gray-400 text-sm">
                Belum ada data
            </div>
        {/if}
    </div>

    <!-- Booking Status Distribution -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Status Booking</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">Distribusi status reservasi</p>

        {#if totalBookings > 0}
            <!-- Stacked bar -->
            <div class="h-6 rounded-full overflow-hidden flex mb-6 bg-gray-100 dark:bg-gray-800">
                {#if stats.approved > 0}
                    <div class="bg-green-500 transition-all duration-500" style="width: {approvedPct}%"></div>
                {/if}
                {#if stats.pending > 0}
                    <div class="bg-yellow-400 transition-all duration-500" style="width: {pendingPct}%"></div>
                {/if}
                {#if stats.rejected > 0}
                    <div class="bg-red-500 transition-all duration-500" style="width: {rejectedPct}%"></div>
                {/if}
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Disetujui</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{stats.approved}</span>
                        <span class="text-xs text-gray-400 ml-1">({approvedPct}%)</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Menunggu</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{stats.pending}</span>
                        <span class="text-xs text-gray-400 ml-1">({pendingPct}%)</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Ditolak</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{stats.rejected}</span>
                        <span class="text-xs text-gray-400 ml-1">({rejectedPct}%)</span>
                    </div>
                </div>
            </div>
        {:else}
            <div class="h-32 flex items-center justify-center text-gray-400 text-sm">
                Belum ada data booking
            </div>
        {/if}
    </div>
</div>

<!-- Bottom Row -->
<div class="grid gap-6 lg:grid-cols-2">
    <!-- Top Cars -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Mobil Terpopuler</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Berdasarkan jumlah booking</p>
            </div>
            <Trophy class="w-5 h-5 text-yellow-500" />
        </div>

        {#if topCars.length === 0}
            <div class="py-8 text-center text-gray-400 text-sm">Belum ada data</div>
        {:else}
            <div class="space-y-3">
                {#each topCars as car, i}
                    <div class="flex items-center gap-4 p-3 rounded-xl {i === 0 ? 'bg-yellow-50 dark:bg-yellow-900/10 ring-1 ring-yellow-200 dark:ring-yellow-800' : 'hover:bg-gray-50 dark:hover:bg-gray-800/50'}">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0 {i === 0 ? 'bg-yellow-400 text-yellow-900' : i === 1 ? 'bg-gray-300 text-gray-700' : i === 2 ? 'bg-orange-300 text-orange-800' : 'bg-gray-100 dark:bg-gray-800 text-gray-500'}">
                            {i + 1}
                        </div>
                        <div class="w-12 h-9 bg-gray-100 dark:bg-gray-800 rounded-lg p-1 flex items-center justify-center shrink-0">
                            {#if car.car_image}
                                <img src={car.car_image} alt={car.car_name} class="w-full h-full object-contain" />
                            {:else}
                                <Car class="w-5 h-5 text-gray-400" />
                            {/if}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-gray-900 dark:text-white truncate">{car.car_name}</p>
                            <p class="text-[10px] text-gray-400">{car.total_bookings} booking</p>
                        </div>
                        <span class="text-sm font-bold text-green-600 dark:text-green-400 whitespace-nowrap">{formatCurrency(car.total_revenue)}</span>
                    </div>
                {/each}
            </div>
        {/if}
    </div>

    <!-- Recent Bookings -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Booking Terakhir</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">5 reservasi terbaru</p>
            </div>
            <Calendar class="w-5 h-5 text-gray-400" />
        </div>

        {#if recentBookings.length === 0}
            <div class="py-8 text-center text-gray-400 text-sm">Belum ada booking</div>
        {:else}
            <div class="space-y-3">
                {#each recentBookings as booking}
                    <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-xs font-bold text-blue-600 dark:text-blue-400 shrink-0">
                            {booking.name?.charAt(0)?.toUpperCase() || 'U'}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm text-gray-900 dark:text-white truncate">{booking.name}</p>
                            <p class="text-[10px] text-gray-400">{booking.car_name} · {formatDate(booking.created_at)}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{formatCurrency(booking.total_price)}</p>
                            <span class="inline-flex px-1.5 py-0.5 rounded-full text-[9px] font-bold {getStatusColor(booking.status)}">
                                {getStatusLabel(booking.status)}
                            </span>
                        </div>
                    </div>
                {/each}
            </div>
        {/if}
    </div>
</div>
