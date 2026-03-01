<script>
    import AppLayout from '@/layouts/app-layout.svelte';
    import Head from '@/components/head.svelte';
    import { Button } from "@/components/ui/button/index.js";
    import { CheckCircle2, Clock, Car, Calendar, ArrowRight, Home } from 'lucide-svelte';
    import { Link } from '@inertiajs/svelte';
    import { Separator } from "@/components/ui/separator/index.js";

    let { booking } = $props();

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
            month: 'long',
            year: 'numeric',
        });
    }
</script>

<Head title="Booking Berhasil" />

<AppLayout>
    <div class="py-12 sm:py-20">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Success Card -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-xl ring-1 ring-gray-900/5 dark:ring-white/10 text-center">
                
                <!-- Success Header -->
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 px-8 py-12 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10">
                        <svg class="absolute left-0 top-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <circle cx="80" cy="20" r="40" fill="currentColor"/>
                            <circle cx="20" cy="80" r="30" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce shadow-lg">
                            <CheckCircle2 class="w-10 h-10 text-white" />
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">Booking Berhasil!</h1>
                        <p class="text-green-100 text-lg">Pesanan Anda sedang kami proses.</p>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="px-8 -mt-5 relative z-10">
                    <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300 font-semibold text-sm shadow-md">
                        <Clock class="w-4 h-4" />
                        Menunggu Konfirmasi Admin
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="px-8 py-8 space-y-6">
                    <div class="text-left p-5 bg-gray-50 dark:bg-gray-800/50 rounded-2xl space-y-4 ring-1 ring-gray-100 dark:ring-gray-800">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-xl p-2 flex items-center justify-center shrink-0 ring-1 ring-gray-200 dark:ring-gray-700">
                                <img src={booking.car_image} alt={booking.car_name} class="w-full h-full object-contain" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{booking.car_name}</h3>
                                <p class="text-sm text-gray-500">ID: <span class="font-mono font-semibold">ORD-{String(booking.id).padStart(5, '0')}</span></p>
                            </div>
                        </div>

                        <Separator />

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-400 text-xs uppercase font-bold tracking-wider mb-1">Tanggal Mulai</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{formatDate(booking.start_date)}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase font-bold tracking-wider mb-1">Tanggal Selesai</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{formatDate(booking.end_date)}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase font-bold tracking-wider mb-1">Durasi</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{booking.duration} Hari</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase font-bold tracking-wider mb-1">Total Bayar</p>
                                <p class="font-bold text-blue-600 dark:text-blue-400 text-lg">{formatCurrency(booking.total_price)}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-900/30 text-left">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            <span class="font-bold">Apa selanjutnya?</span> Tim admin kami akan memverifikasi pembayaran Anda. Setelah dikonfirmasi, status pesanan akan berubah menjadi <span class="font-bold text-green-600 dark:text-green-400">"Disetujui"</span>. Anda dapat memantau status di halaman Riwayat Order.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <Link href="/riwayat-order" class="flex-1">
                            <Button class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl h-12 font-bold gap-2 shadow-lg shadow-blue-500/20">
                                <Calendar class="w-4 h-4" />
                                Lihat Riwayat Order
                            </Button>
                        </Link>
                        <Link href="/" class="flex-1">
                            <Button variant="outline" class="w-full rounded-xl h-12 font-bold gap-2 border-gray-300 dark:border-gray-700">
                                <Home class="w-4 h-4" />
                                Kembali ke Beranda
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</AppLayout>
