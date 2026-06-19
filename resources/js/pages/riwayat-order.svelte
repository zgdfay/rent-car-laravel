<script>
    import Head from '@/components/head.svelte';
    import AppLayout from '@/layouts/app-layout.svelte';
    import { Car, Calendar, CreditCard, CheckCircle2, Clock, XCircle, AlertCircle, ShoppingBag, Upload, FileText, Info } from 'lucide-svelte';
    import { Button } from "@/components/ui/button/index.js";
    import { Link, router } from '@inertiajs/svelte';

    let { bookings = [] } = $props();

    function getStatusLabel(status) {
        switch (status) {
            case 'pending': return 'Menunggu Konfirmasi';
            case 'approved': return 'Disetujui';
            case 'rejected': return 'Ditolak';
            case 'pending_penalty': return 'Menunggu Pembayaran Denda';
            case 'completed': return 'Selesai';
            default: return status;
        }
    }

    function getStatusBadgeColor(status) {
        switch (status) {
            case 'approved': 
            case 'completed': return 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300';
            case 'pending': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300';
            case 'pending_penalty': return 'bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-300';
            case 'rejected': return 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300';
            default: return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300';
        }
    }

    function getStatusIcon(status) {
        switch (status) {
            case 'approved': 
            case 'completed': return CheckCircle2;
            case 'pending': return Clock;
            case 'rejected': return XCircle;
            case 'pending_penalty': return AlertCircle;
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

    let fileInputs = $state({});
    let processingIds = $state({});

    function handleFileChange(e, orderId) {
        if (e.target.files && e.target.files.length > 0) {
            fileInputs[orderId] = e.target.files[0];
        } else {
            fileInputs[orderId] = null;
        }
    }

    function handleUploadProof(orderId) {
        const file = fileInputs[orderId];
        if (!file) return;

        processingIds[orderId] = true;
        
        router.post(`/bookings/${orderId}/return-proof`, {
            _method: 'post',
            return_payment_proof: file
        }, {
            forceFormData: true,
            onFinish: () => {
                processingIds[orderId] = false;
            },
            onSuccess: () => {
                fileInputs[orderId] = null;
            }
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
                                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Total Biaya Sewa</p>
                                                <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                                    {#if order.status === 'completed' || order.status === 'pending_penalty'}
                                                        {formatCurrency(order.total_price - (order.late_fee + order.damage_fee))}
                                                    {:else}
                                                        {formatCurrency(order.total_price)}
                                                    {/if}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Penalty Payment Section -->
                                {#if order.status === 'pending_penalty'}
                                    <div class="mt-6 bg-orange-50 dark:bg-orange-900/20 border border-orange-100 dark:border-orange-800/50 rounded-xl p-5">
                                        <div class="flex items-start gap-3">
                                            <Info class="w-5 h-5 text-orange-600 dark:text-orange-400 shrink-0 mt-0.5" />
                                            <div class="flex-1">
                                                <h4 class="text-sm font-bold text-orange-900 dark:text-orange-300 mb-2">Tagihan Denda Pengembalian</h4>
                                                
                                                <div class="space-y-2 mb-4 text-sm text-orange-800 dark:text-orange-200">
                                                    {#if order.late_fee > 0}
                                                        <div class="flex justify-between">
                                                            <span>Biaya Keterlambatan</span>
                                                            <span class="font-medium">{formatCurrency(order.late_fee)}</span>
                                                        </div>
                                                    {/if}
                                                    {#if order.damage_fee > 0}
                                                        <div class="flex justify-between">
                                                            <span>Biaya Kerusakan</span>
                                                            <span class="font-medium">{formatCurrency(order.damage_fee)}</span>
                                                        </div>
                                                    {/if}
                                                    <div class="flex justify-between pt-2 border-t border-orange-200 dark:border-orange-800/50 font-bold text-base">
                                                        <span>Total Tagihan Denda</span>
                                                        <span>{formatCurrency(order.late_fee + order.damage_fee)}</span>
                                                    </div>
                                                </div>

                                                <div class="bg-white dark:bg-gray-900 rounded-lg p-4 mb-4 border border-orange-100 dark:border-orange-800/50">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Silakan transfer denda ke rekening berikut:</p>
                                                    <p class="font-bold text-gray-900 dark:text-white text-lg">BCA 123456789</p>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">a.n. Rent Car Berkah</p>
                                                </div>

                                                <div class="space-y-3">
                                                    <label for="proof-{order.id}" class="block text-sm font-medium text-orange-900 dark:text-orange-300">
                                                        Upload Bukti Pembayaran Denda
                                                    </label>
                                                    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                                                        <input 
                                                            type="file" 
                                                            id="proof-{order.id}" 
                                                            accept="image/png, image/jpeg, image/jpg"
                                                            class="block w-full text-sm text-gray-500 dark:text-gray-400
                                                                file:mr-4 file:py-2.5 file:px-4
                                                                file:rounded-lg file:border-0
                                                                file:text-sm file:font-semibold
                                                                file:bg-orange-600 file:text-white
                                                                hover:file:bg-orange-700
                                                                file:cursor-pointer cursor-pointer
                                                                bg-white dark:bg-gray-900 rounded-lg border border-orange-200 dark:border-orange-800/50"
                                                            onchange={(e) => handleFileChange(e, order.id)}
                                                        />
                                                        <Button 
                                                            class="w-full sm:w-auto shrink-0" 
                                                            disabled={!fileInputs[order.id] || processingIds[order.id]}
                                                            onclick={() => handleUploadProof(order.id)}
                                                        >
                                                            {#if processingIds[order.id]}
                                                                <Clock class="w-4 h-4 mr-2 animate-spin" />
                                                                Mengunggah...
                                                            {:else}
                                                                <Upload class="w-4 h-4 mr-2" />
                                                                Kirim Bukti
                                                            {/if}
                                                        </Button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {/if}
                                
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
