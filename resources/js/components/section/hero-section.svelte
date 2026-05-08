<script>
    import { Button } from '@/components/ui/button/index.js';
    import { Link } from '@inertiajs/svelte';
</script>

<section class="relative overflow-hidden bg-white pt-16 pb-16 md:pt-24 lg:pt-32 dark:bg-gray-900">
    <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
            <!-- Text Content -->
            <div class="max-w-2xl">
                <div
                    class="mb-6 inline-flex items-center rounded-full border border-blue-600/20 bg-blue-50/50 px-3 py-1 text-sm font-medium text-blue-600 dark:border-blue-700/30 dark:bg-blue-900/20 dark:text-blue-400"
                >
                    <span class="mr-2 flex h-2 w-2 rounded-full bg-blue-600"></span>
                    Now Available in Multiple Cities
                </div>

                <h1
                    class="mb-6 text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl dark:text-white"
                >
                    Mulai Perjalanan Anda.<br />
                    <span
                        class="bg-linear-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent dark:from-blue-400 dark:to-indigo-400"
                    >
                        Sewa yang Terbaik.
                    </span>
                </h1>

                <p class="mb-8 max-w-2xl text-lg text-gray-600 sm:text-xl dark:text-gray-300">
                    Nikmati pengalaman mobilitas premium dengan armada kendaraan mewah, sport, dan
                    praktis kami. Pemesanan mudah, tarif transparan, dan pelayanan tanpa kompromi.
                </p>

                <div class="mb-12 flex flex-col gap-4 sm:flex-row">
                    <Link href="/list-car" class="w-full sm:w-auto">
                        <Button
                            size="lg"
                            class="h-14 w-full rounded-xl bg-blue-600 px-8 font-medium text-white shadow-xl shadow-blue-600/20 transition-all hover:-translate-y-1 hover:bg-blue-700"
                        >
                            Lihat Koleksi Mobil
                        </Button>
                    </Link>
                    <Link href="/about" class="w-full sm:w-auto">
                        <Button
                            variant="outline"
                            size="lg"
                            class="h-14 w-full rounded-xl border-gray-300 px-8 font-medium text-gray-700 transition-all hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                        >
                            Pelajari Lebih Lanjut
                        </Button>
                    </Link>
                </div>

                <!-- Quick Stats -->
                <div
                    class="grid grid-cols-3 gap-4 border-t border-gray-100 pt-8 sm:gap-8 dark:border-gray-800"
                >
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">50+</p>
                        <p class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Mobil Premium
                        </p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">10k+</p>
                        <p class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Klien Puas
                        </p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">24/7</p>
                        <p class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Dukungan Tim
                        </p>
                    </div>
                </div>
            </div>

            <!-- Image/Visual -->
            <div
                class="pointer-events-none relative -mr-8 w-full max-w-lg sm:-mr-12 lg:m-0 lg:ml-auto lg:w-[110%] lg:max-w-none xl:w-[120%]"
            >
                <div
                    class="absolute -inset-x-16 -inset-y-24 z-0 rounded-full bg-linear-to-tr from-blue-100/50 to-indigo-50/50 opacity-50 blur-3xl dark:from-blue-900/20 dark:to-indigo-900/20"
                ></div>
                <img
                    src="/assets/images/hero-drs.jpg"
                    alt="Luxury Sports Car"
                    class="relative z-10 h-auto w-full scale-105 transform rounded-2xl object-contain object-center drop-shadow-2xl transition-transform duration-700 hover:scale-110 lg:translate-x-8 xl:translate-x-12"
                />

                <!-- Floating decorative element removed to accommodate the larger car image size -->
            </div>
        </div>
    </div>
</section>
