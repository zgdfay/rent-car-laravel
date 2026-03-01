<script>
    import Head from '@/components/head.svelte';
    import AppLayout from '@/layouts/app-layout.svelte';
    import { Car, Calendar, CreditCard, CheckCircle2, Clock, XCircle, AlertCircle, ShoppingBag } from 'lucide-svelte';
    import { Button } from "@/components/ui/button/index.js";
    import { Link } from '@inertiajs/svelte';

    let { bookings = [] } = $props();

    function getStatusLabel(status) {
        switch (status) {
            case 'pending': return 'Menunggu Konfirmasi';
            case 'approved': return 'Disetujui';
            case 'rejected': return 'Ditolak';
            default: return status;
        }
    }

    function getStatusBadgeColor(status) {
        switch (status) {
            case 'approved': return 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300';
            case 'pending': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300';
            case 'rejected': return 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300';
            default: return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300';
        }
    }

    function getStatusIcon(status) {
        switch (status) {
            case 'approved': return CheckCircle2;
            case 'pending': return Clock;
            case 'rejected': return XCircle;
            default: return AlertCircle;
        }
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
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
</script>

<Head title="Riwayat Order" />

<AppLayout>
    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-8">
                <ol class="flex space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="/" class="hover:text-gray-900 dark:hover:text-white">Beranda</a></li>
                    <li class="flex items-center">
                        <span class="mx-2">/</span>
                        <span class="text-gray-900 dark:text-white font-medium">Riwayat Order</span>
                    </li>
                </ol>
            </nav>
            <div class="mb-8">
                <h2 class="text-2xl font-bold font-heading text-gray-900 dark:text-white sm:text-3xl">Riwayat Order Anda</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Kelola dan pantau semua penyewaan kendaraan Anda di sini.</p>
            </div>

            {#if bookings.length === 0}
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-12 text-center ring-1 ring-gray-900/5 dark:ring-white/10 shadow-sm">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                        <ShoppingBag class="w-10 h-10 text-gray-400" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-8">Anda belum memiliki riwayat pesanan. Mulai sewa kendaraan sekarang untuk pengalaman perjalanan terbaik!</p>
                    <Link href="/list-car">
                        <Button class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl h-12 px-8 font-bold gap-2 shadow-lg shadow-blue-500/20">
                            <Car class="w-4 h-4" />
                            Jelajahi Kendaraan
                        </Button>
                    </Link>
                </div>
            {:else}
                <div class="space-y-6">
                    {#each bookings as order}
                        {@const StatusIcon = getStatusIcon(order.status)}
                        <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 sm:rounded-2xl transition-all hover:shadow-md">
                            <div class="p-6">
                                <!-- Order Header -->
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-100 dark:border-gray-800 pb-4 mb-4 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider mb-1">ID Transaksi</p>
                                        <p class="font-mono text-sm font-semibold text-gray-900 dark:text-white">ORD-{String(order.id).padStart(5, '0')}</p>
                                    </div>
                                    <div class="px-3 py-1 rounded-full text-xs font-semibold {getStatusBadgeColor(order.status)} flex items-center gap-1.5 shadow-sm">
                                        <StatusIcon class="w-3.5 h-3.5" />
                                        {getStatusLabel(order.status)}
                                    </div>
                                </div>

                                <!-- Order Content -->
                                <div class="flex flex-col sm:flex-row gap-6">
                                    <!-- Order Image -->
                                    <div class="sm:w-1/4 max-w-[200px] shrink-0 bg-gray-50 dark:bg-gray-800/50 rounded-xl p-3 flex items-center justify-center border border-gray-100 dark:border-gray-800">
                                        <img src={order.car_image} alt={order.car_name} class="w-full h-auto object-contain" />
                                    </div>

                                    <!-- Order Details -->
                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{order.car_name}</h3>
                                            
                                            <div class="space-y-2">
                                                <div class="flex items-start text-sm text-gray-600 dark:text-gray-400">
                                                    <Calendar class="w-4 h-4 mr-2 mt-0.5 shrink-0 text-gray-400" />
                                                    <div>
                                                        <p class="font-medium text-gray-900 dark:text-white">{formatDate(order.start_date)} <span class="text-gray-400 font-normal mx-1">s/d</span> {formatDate(order.end_date)}</p>
                                                        <p class="text-xs mt-0.5">{order.duration} Hari</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-4 md:border-l md:border-gray-100 md:dark:border-gray-800 md:pl-6">
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <CreditCard class="w-4 h-4 mr-2 shrink-0 text-gray-400" />
                                                <span>Transfer Bank</span>
                                            </div>
                                            <div class="pt-2">
                                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Total Biaya</p>
                                                <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{formatCurrency(order.total_price)}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                {#if order.status === 'approved'}
                                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-end gap-3">
                                        <Link href="/list-car">
                                            <Button class="bg-gray-900 hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100 text-white rounded-lg h-9">
                                                Sewa Lagi
                                            </Button>
                                        </Link>
                                    </div>
                                {/if}
                            </div>
                        </div>
                    {/each}
                </div>
            {/if}
        </div>
    </div>
</AppLayout>
