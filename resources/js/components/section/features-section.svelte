<script>
    const features = [
        {
            title: "Koleksi Premium",
            description: "Pilih kendaraan dari berbagai jenis seperti mobil mewah, sport, hingga mobil fungsional keluarga yang dirawat dengan standar tertinggi.",
            icon: `<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>`
        },
        {
            title: "Dukungan 24/7",
            description: "Tim perwakilan pelanggan kami berdedikasi dan siap dihubungi kapan saja untuk membantu atau menangani situasi darurat di jalan.",
            icon: `<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>`
        },
        {
            title: "Jadwal Fleksibel",
            description: "Menyewa untuk harian, mingguan, atau bulanan dengan kemudahan adaptasi pada pilihan waktu perpanjangan atau pengembalian.",
            icon: `<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>`
        },
        {
            title: "Asuransi Menyeluruh",
            description: "Berkendara dengan rasa damai karena setiap mobil kami telah difasilitasi dengan program asuransi perlindungan penuh secara menyeluruh.",
            icon: `<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>`
        }
    ];
</script>

<section class="py-24 bg-gray-50 dark:bg-gray-800/50">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-base/7 font-semibold text-blue-600 dark:text-blue-400">Why Choose Us</h2>
            <p class="mt-2 text-4xl font-semibold tracking-tight text-gray-900 dark:text-white sm:text-5xl">Segalanya untuk Kenyamanan Anda</p>
            <p class="mt-6 text-lg/8 text-gray-600 dark:text-gray-300">Kami memberikan layanan premium untuk memastikan perjalanan Anda aman, nyaman, dan tak terlupakan.</p>
        </div>
        <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
            <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-4">
                {#each features as feature}
                    <div class="flex flex-col items-center text-center">
                        <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100 dark:bg-blue-900/40">
                            {@html feature.icon}
                        </div>
                        <dt class="text-xl font-semibold leading-7 text-gray-900 dark:text-white">
                            {feature.title}
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-400">
                            <p class="flex-auto">{feature.description}</p>
                        </dd>
                    </div>
                {/each}
            </dl>
        </div>
    </div>
</section>
