<script>
    import { Button } from "@/components/ui/button/index.js";
    import { Link } from "@inertiajs/svelte";

    const steps = [
        {
            number: "01",
            title: "Pilih Mobil Anda",
            description: "Pilih dari koleksi mobil premium kami yang sesuai dengan kebutuhan perjalanan Anda.",
            icon: `<svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>`
        },
        {
            number: "02",
            title: "Tentukan Tanggal",
            description: "Pilih tanggal mulai sewa dan pengembalian yang fleksibel sesuai jadwal kegiatan Anda.",
            icon: `<svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>`
        },
        {
            number: "03",
            title: "Siap Jalan",
            description: "Ambil mobil yang sudah dibersihkan secara profesional dan rasakan pengalaman berkendara yang aman.",
            icon: `<svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`
        }
    ];
</script>

<section class="py-24 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
            
            <div class="max-w-xl">
                <h2 class="text-base font-semibold leading-7 text-blue-600 dark:text-blue-400">Cara Sewa</h2>
                <p class="mt-2 text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-5xl">3 Langkah Mudah Menyewa Mobil</p>
                <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">
                    Kami telah menyederhanakan proses penyewaan mobil sehingga Anda dapat dengan cepat menyelesaikan pemesanan dan segera menikmati perjalanan Anda tanpa beban prosedur yang panjang.
                </p>
                
                <div class="mt-10 mb-12 flex items-center lg:mb-0">
                    <Link href="/list-car">
                        <Button size="lg" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-md h-12 px-6">
                            Mulai Sewa Sekarang
                        </Button>
                    </Link>
                </div>
            </div>

            <div class="relative mt-16 sm:mt-24 lg:mt-0 lg:ml-auto">
                <!-- Visual Steps Path -->
                <div class="absolute left-10 top-10 bottom-10 w-0.5 bg-gradient-to-b from-blue-600 to-transparent dark:from-blue-500 hidden sm:block"></div>
                
                <div class="flex flex-col gap-10">
                    {#each steps as step, index}
                        <div class="relative flex gap-6 sm:gap-8 hover:-translate-y-1 transition-transform duration-300">
                            <div class="relative flex-shrink-0 flex items-center justify-center w-20 h-20 rounded-2xl bg-white dark:bg-gray-800 shadow-xl ring-1 ring-gray-900/5 dark:ring-white/10 z-10">
                                {@html step.icon}
                                <div class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                    {step.number}
                                </div>
                            </div>
                            
                            <div class="pt-2">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{step.title}</h3>
                                <p class="mt-2 text-base text-gray-600 dark:text-gray-400 leading-relaxed max-w-sm">
                                    {step.description}
                                </p>
                            </div>
                        </div>
                    {/each}
                </div>
                
            </div>
            
        </div>
    </div>
</section>
