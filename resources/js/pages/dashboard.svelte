<script module>
    export { default as layout } from '@/layouts/authenticated-layout.svelte';
</script>

<script>
    import { router, page } from '@inertiajs/svelte';
    import { Button } from "@/components/ui/button/index.js";
    import { Separator } from "@/components/ui/separator/index.js";
    import { Car, Users, Clock, CheckCircle2, XCircle, DollarSign, TrendingUp, Eye, CalendarDays } from 'lucide-svelte';

    let { bookings = [], stats = {} } = $props();

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    function getStatusLabel(status) {
        switch (status) {
            case 'pending': return 'Menunggu';
            case 'approved': return 'Disetujui';
            case 'rejected': return 'Ditolak';
            default: return status;
        }
    }

    function getStatusColor(status) {
        switch (status) {
            case 'pending': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300';
            case 'approved': return 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300';
            case 'rejected': return 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function updateStatus(bookingId, status) {
        router.patch(`/admin/bookings/${bookingId}/status`, { status }, {
            preserveScroll: true,
        });
    }
</script>

<!-- Stats Cards -->
<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mb-8">
    <!-- Total Booking -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Booking</p>
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <Car class="w-5 h-5 text-blue-600 dark:text-blue-400" />
            </div>
        </div>
        <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{stats.total || 0}</p>
        <p class="text-xs text-gray-400 mt-1">Semua pesanan</p>
    </div>

    <!-- Pending -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu</p>
            <div class="w-10 h-10 rounded-xl bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                <Clock class="w-5 h-5 text-yellow-600 dark:text-yellow-400" />
            </div>
        </div>
        <p class="text-3xl font-extrabold text-yellow-600 dark:text-yellow-400">{stats.pending || 0}</p>
        <p class="text-xs text-gray-400 mt-1">Perlu dikonfirmasi</p>
    </div>

    <!-- Approved -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Disetujui</p>
            <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <CheckCircle2 class="w-5 h-5 text-green-600 dark:text-green-400" />
            </div>
        </div>
        <p class="text-3xl font-extrabold text-green-600 dark:text-green-400">{stats.approved || 0}</p>
        <p class="text-xs text-gray-400 mt-1">Booking aktif</p>
    </div>

    <!-- Revenue Active -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendapatan Aktif</p>
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <TrendingUp class="w-5 h-5 text-blue-600 dark:text-blue-400" />
            </div>
        </div>
        <p class="text-2xl font-extrabold text-gray-900 dark:text-white">{formatCurrency(stats.revenue_active || 0)}</p>
        <p class="text-xs text-gray-400 mt-1">Booking disetujui</p>
    </div>

    <!-- Revenue Completed -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendapatan Selesai</p>
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                <TrendingUp class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
            </div>
        </div>
        <p class="text-2xl font-extrabold text-gray-900 dark:text-white">{formatCurrency(stats.revenue_completed || 0)}</p>
        <p class="text-xs text-gray-400 mt-1">Booking selesai</p>
    </div>
</div>

<!-- Recent Bookings Table -->
<div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Booking</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola pesanan dan konfirmasi pembayaran.</p>
        </div>
    </div>

    {#if bookings.length === 0}
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                <Car class="w-8 h-8 text-gray-400" />
            </div>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada booking masuk.</p>
        </div>
    {:else}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 text-left">
                        <th class="px-6 py-3.5 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3.5 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Penyewa</th>
                        <th class="px-6 py-3.5 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Mobil</th>
                        <th class="px-6 py-3.5 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3.5 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3.5 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Bukti</th>
                        <th class="px-6 py-3.5 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    {#each bookings as booking}
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-semibold text-gray-900 dark:text-white">ORD-{String(booking.id).padStart(5, '0')}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{booking.name}</p>
                                    <p class="text-xs text-gray-500">{booking.whatsapp}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-10 bg-gray-100 dark:bg-gray-800 rounded-lg p-1 flex items-center justify-center shrink-0">
                                        <img src={booking.car_image} alt={booking.car_name} class="w-full h-full object-contain" />
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white text-xs">{booking.car_name}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-gray-900 dark:text-white">{formatDate(booking.start_date)}</p>
                                <p class="text-xs text-gray-400">s/d {formatDate(booking.end_date)}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">{booking.duration} hari</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900 dark:text-white text-xs">{formatCurrency(booking.total_price)}</span>
                            </td>
                            <td class="px-6 py-4">
                                {#if booking.payment_proof}
                                    <a href="/storage/{booking.payment_proof}" target="_blank" class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                        <Eye class="w-3.5 h-3.5" />
                                        Lihat
                                    </a>
                                {:else}
                                    <span class="text-xs text-gray-400">-</span>
                                {/if}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {getStatusColor(booking.status)}">
                                    {getStatusLabel(booking.status)}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                {#if booking.status === 'pending'}
                                    <div class="flex items-center justify-end gap-2">
                                        <Button 
                                            size="sm"
                                            onclick={() => updateStatus(booking.id, 'approved')}
                                            class="bg-green-600 hover:bg-green-700 text-white rounded-lg h-8 text-xs px-3 gap-1"
                                        >
                                            <CheckCircle2 class="w-3.5 h-3.5" /> Setujui
                                        </Button>
                                        <Button 
                                            size="sm"
                                            variant="outline"
                                            onclick={() => updateStatus(booking.id, 'rejected')}
                                            class="rounded-lg h-8 text-xs px-3 gap-1 border-red-200 text-red-600 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20"
                                        >
                                            <XCircle class="w-3.5 h-3.5" /> Tolak
                                        </Button>
                                    </div>
                                {:else}
                                    <span class="text-xs text-gray-400">—</span>
                                {/if}
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    {/if}
</div>
