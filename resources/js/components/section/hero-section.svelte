<script>
    import { Button } from "@/components/ui/button/index.js";
    import { Link } from "@inertiajs/svelte";
</script>

<section class="relative overflow-hidden bg-white dark:bg-gray-900 pt-16 md:pt-24 lg:pt-32 pb-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Text Content -->
            <div class="max-w-2xl">
                <div class="inline-flex items-center rounded-full border border-blue-600/20 bg-blue-50/50 px-3 py-1 text-sm font-medium text-blue-600 dark:bg-blue-900/20 dark:border-blue-700/30 dark:text-blue-400 mb-6">
                    <span class="flex h-2 w-2 rounded-full bg-blue-600 mr-2"></span>
                    Now Available in Multiple Cities
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-6">
                    Mulai Perjalanan Anda.<br/>
                    <span class="text-transparent bg-clip-text bg-linear-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">
                        Sewa yang Terbaik.
                    </span>
                </h1>
                
                <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-2xl">
                    Nikmati pengalaman mobilitas premium dengan armada kendaraan mewah, sport, dan praktis kami. Pemesanan mudah, tarif transparan, dan pelayanan tanpa kompromi.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 mb-12">
                    <Link href="/list-car" class="w-full sm:w-auto">
                        <Button size="lg" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl h-14 px-8 shadow-xl shadow-blue-600/20 transition-all hover:-translate-y-1">
                            Lihat Koleksi Mobil
                        </Button>
                    </Link>
                    <Link href="/about" class="w-full sm:w-auto">
                        <Button variant="outline" size="lg" class="w-full border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium rounded-xl h-14 px-8 transition-all">
                            Pelajari Lebih Lanjut
                        </Button>
                    </Link>
                </div>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-4 sm:gap-8 pt-8 border-t border-gray-100 dark:border-gray-800">
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">50+</p>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Mobil Premium</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">10k+</p>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Klien Puas</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">24/7</p>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Dukungan Tim</p>
                    </div>
                </div>
            </div>
            
            <!-- Image/Visual -->
            <div class="relative lg:ml-auto w-full max-w-lg lg:max-w-none lg:w-[110%] xl:w-[120%] pointer-events-none -mr-8 sm:-mr-12 lg:m-0">
                <div class="absolute -inset-y-24 -inset-x-16 bg-linear-to-tr from-blue-100/50 to-indigo-50/50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-full blur-3xl opacity-50 z-0"></div>
                <img 
                    src="/assets/images/hero-ferrari.png" 
                    alt="Luxury Sports Car" 
                    class="relative z-10 w-full h-auto object-contain object-center drop-shadow-2xl scale-105 transform transition-transform duration-700 hover:scale-110 lg:translate-x-8 xl:translate-x-12"
                />
                
                <!-- Floating decorative element removed to accommodate the larger car image size -->
            </div>
        </div>
    </div>
</section>