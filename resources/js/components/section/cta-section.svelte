<script>
    import { Button } from "@/components/ui/button/index.js";
    import { Link } from "@inertiajs/svelte";
</script>

<section class="py-24 bg-blue-600 dark:bg-blue-900 relative overflow-hidden">
    <!-- Decorative background patterns -->
    <div class="absolute inset-0 opacity-10">
        <svg class="absolute left-0 top-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0 100 C 20 0 50 0 100 100 Z" fill="currentColor"></path>
        </svg>
    </div>
    
    <div class="absolute right-0 top-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-white opacity-10 blur-3xl"></div>
    <div class="absolute left-0 bottom-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-blue-400 opacity-20 blur-3xl"></div>

    <div class="mx-auto max-w-7xl px-6 lg:px-8 relative z-10 text-center">
        <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl max-w-2xl mx-auto">
            Siap untuk Memulai Perjalanan Anda?
        </h2>
        <p class="mt-6 text-xl text-blue-100 max-w-2xl mx-auto leading-relaxed">
            Bergabunglah dengan ribuan pelanggan yang telah mempercayakan perjalanan mereka bersama kami. Pesan sekarang dan nikmati pengalaman berkendara terbaik.
        </p>
        
        <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center items-center">
            <Link href="/list-car">
                <Button size="lg" class="bg-white text-blue-600 hover:bg-gray-100 hover:text-blue-700 font-bold px-8 h-14 rounded-xl text-lg shadow-xl shadow-blue-900/20 w-full sm:w-auto transition-transform hover:-translate-y-1">
                    Sewa Mobil Sekarang
                </Button>
            </Link>
            <Link href="/contact">
                <Button size="lg" variant="default" class="bg-gray-900 hover:bg-gray-800 text-white dark:bg-white dark:hover:bg-gray-100 dark:text-gray-900 px-8 h-14 rounded-xl text-lg w-full sm:w-auto transition-colors">
                    Hubungi Kami
                </Button>
            </Link>
        </div>
        
        <div class="mt-12 flex items-center justify-center gap-x-8 gap-y-4 flex-wrap text-blue-100 text-sm font-medium">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Tanpa Biaya Tersembunyi
            </div>
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Pembatalan Fleksibel
            </div>
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Dukungan 24/7
            </div>
        </div>
    </div>
</section>
