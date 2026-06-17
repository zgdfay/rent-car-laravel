<script>
    import { Button } from "@/components/ui/button/index.js";
    import { Link } from "@inertiajs/svelte";
    import { Users, Settings2, Zap } from "lucide-svelte";

    let { featuredCars = [] } = $props();
    let availableFeaturedCars = $derived(featuredCars.filter(car => car.is_available));

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }
</script>

<section class="py-24 bg-gray-50 dark:bg-gray-800/50">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-end mb-12 sm:mb-16">
            <div class="max-w-2xl">
                <h2 class="text-base font-semibold leading-7 text-blue-600 dark:text-blue-400">Armada Pilihan</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">Temukan Mobil Impian Anda</p>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">Pilihan kendaraan terbaik untuk menemani setiap cerita dan perjalanan bisnis Anda.</p>
            </div>
            
            <div class="mt-6 sm:mt-0 flex-shrink-0">
                <Link href="/list-car">
                    <Button variant="outline" class="hidden sm:flex group transition-all text-blue-600 border-blue-600 hover:bg-blue-600 hover:text-white dark:text-blue-400 dark:border-blue-400 dark:hover:bg-blue-500 dark:hover:text-white rounded-full px-6">
                        Lihat Semua Mobil
                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </Button>
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {#each availableFeaturedCars as car}
                <div class="group relative flex flex-col bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 ring-1 ring-gray-900/5 dark:ring-white/10 hover:-translate-y-2">
                    <div class="relative h-48 sm:h-56 w-full overflow-hidden bg-gray-200 dark:bg-gray-800 p-4 pb-0 flex items-end justify-center">
                        <img 
                            src={car.image} 
                            alt={car.name}
                            on:error={(e) => e.target.src = '/assets/images/hero-ferrari.png'}
                            class="h-full w-full object-contain object-bottom transition-transform duration-500 group-hover:scale-105"
                        />
                        <div class="absolute top-4 left-4 flex gap-2 flex-col sm:flex-row">
                            <span class="inline-flex items-center rounded-full bg-white/90 dark:bg-gray-900/90 px-3 py-1 text-xs font-medium text-gray-900 dark:text-white backdrop-blur-sm shadow-sm ring-1 ring-black/5">
                                {car.category}
                            </span>
                        </div>
                    </div>
                        <div class="flex flex-col p-5 sm:p-6">
                            <div class="mb-4 text-left">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-2">{car.name}</h3>
                                <p class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400 whitespace-nowrap">{formatCurrency(car.price_per_day)}<span class="text-sm font-normal text-gray-500 dark:text-gray-400">/hr</span></p>
                            </div>
                        
                        <div class="grid grid-cols-3 gap-2 mt-auto pt-5 border-t border-gray-100 dark:border-gray-800 mb-6">
                            <div class="flex flex-col items-center justify-center py-3 px-1 rounded-2xl bg-gray-50/80 dark:bg-gray-800/80 text-center">
                                <Users class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500 dark:text-gray-400 mb-2 stroke-[1.5]" />
                                <span class="text-[10px] sm:text-xs font-semibold text-gray-700 dark:text-gray-300 w-full truncate">{car.seats} Kursi</span>
                            </div>
                            <div class="flex flex-col items-center justify-center py-3 px-1 rounded-2xl bg-gray-50/80 dark:bg-gray-800/80 text-center">
                                <Settings2 class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500 dark:text-gray-400 mb-2 stroke-[1.5]" />
                                <span class="text-[10px] sm:text-xs font-semibold text-gray-700 dark:text-gray-300 w-full truncate">{car.transmission}</span>
                            </div>
                            <div class="flex flex-col items-center justify-center py-3 px-1 rounded-2xl bg-gray-50/80 dark:bg-gray-800/80 text-center">
                                <Zap class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500 dark:text-gray-400 mb-2 stroke-[1.5]" />
                                <span class="text-[10px] sm:text-xs font-semibold text-gray-700 dark:text-gray-300 w-full truncate">{car.fuel}</span>
                            </div>
                        </div>
                        
                        <Link href={`/booking/${car.id}`} class="block w-full">
                            <Button 
                                class="w-full bg-gray-900 hover:bg-gray-800 text-white dark:bg-white dark:hover:bg-gray-100 dark:text-gray-900 rounded-xl h-12 shadow-sm font-semibold"
                            >
                                Sewa Sekarang
                            </Button>
                        </Link>
                    </div>
                </div>
            {/each}
        </div>
        
        <!-- Mobile "View All" Button -->
        <div class="mt-10 sm:hidden flex justify-center">
            <Link href="/list-car" class="w-full">
                <Button variant="outline" class="w-full text-blue-600 border-blue-600 hover:bg-blue-600 hover:text-white rounded-xl h-12">
                    Lihat Semua Mobil
                </Button>
            </Link>
        </div>
    </div>
</section>
