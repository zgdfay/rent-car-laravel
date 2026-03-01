<script>
    import AppLayout from "@/layouts/app-layout.svelte";
</script>

<svelte:head>
    <title>Tentang Kami - DSR Rental</title>
</svelte:head>

<AppLayout>
    <main class="grow">
        <!-- Hero Section -->
        <div class="bg-blue-600 dark:bg-blue-900 py-24 sm:py-32 px-6 sm:px-12 text-center relative overflow-hidden flex flex-col justify-center min-h-[40vh]">
            <div class="absolute inset-0 opacity-10">
                <svg class="absolute left-0 top-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="currentColor"></path>
                </svg>
            </div>
            
            <div class="relative z-10 max-w-3xl mx-auto mt-4 sm:mt-8">
                <h1 class="text-3xl font-extrabold text-white sm:text-5xl lg:text-6xl tracking-tight mb-4 sm:mb-6">Tentang DSR Rental</h1>
                <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto px-4">
                    Mitra perjalanan terpercaya Anda, menyediakan kendaraan berkualitas untuk setiap momen berharga.
                </p>
            </div>
        </div>

        <!-- Content Section -->
        <section class="py-16 sm:py-24 bg-white dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24 items-center">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl mb-6">Cerita Kami</h2>
                        <div class="space-y-6 text-lg text-gray-600 dark:text-gray-300">
                            <p>
                                Berdiri sejak tahun 2018, DSR Rental lahir dari sebuah visi sederhana: membuat perjalanan menjadi lebih mudah, aman, dan berkesan bagi semua orang. Kami memahami bahwa kendaraan bukan sekadar alat transportasi, melainkan kunci untuk menciptakan pengalaman tak terlupakan bersama keluarga, teman, atau rekan bisnis.
                            </p>
                            <p>
                                Dimulai dengan hanya lima armada, dedikasi kami terhadap kualitas layanan dan kepuasan pelanggan telah membawa kami tumbuh menjadi salah satu penyedia layanan rental mobil terpercaya di kota ini.
                            </p>
                            <p>
                                Hari ini, kami bangga menyajikan beragam pilihan kendaraan mulai dari mobil kota yang efisien, SUV keluarga yang tangguh, hingga mobil premium untuk momen spesial Anda, semuanya dalam kondisi prima dan terawat dengan standar tertinggi.
                            </p>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-tr from-blue-100 to-transparent dark:from-blue-900/30 rounded-3xl transform rotate-3 scale-105"></div>
                        <img 
                            src="/assets/images/showroom-image.jpg" 
                            alt="DSR Rental Fleet" 
                            class="relative rounded-3xl object-cover shadow-2xl ring-1 ring-gray-900/5 dark:ring-white/10"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Values Section -->
        <section class="py-16 sm:py-24 bg-gray-50 dark:bg-gray-800/50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">Mengapa Memilih Kami?</h2>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Kami tidak hanya menyewakan mobil, kami berkomitmen memberikan pelayanan terbaik.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Value 1 -->
                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 hover:shadow-lg transition-shadow">
                        <div class="inline-flex items-center justify-center p-3 bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400 rounded-2xl mb-6">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Keamanan Terjamin</h3>
                        <p class="text-gray-600 dark:text-gray-400">Seluruh unit kami secara rutin diperiksa dan dirawat oleh mekanik tersertifikasi untuk memastikan keamanan perjalanan Anda.</p>
                    </div>

                    <!-- Value 2 -->
                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 hover:shadow-lg transition-shadow">
                        <div class="inline-flex items-center justify-center p-3 bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400 rounded-2xl mb-6">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Layanan 24 Jam</h3>
                        <p class="text-gray-600 dark:text-gray-400">Tim bantuan teknis kami selalu siap sedia 24/7 untuk membantu Anda jika terjadi kendala di jalan.</p>
                    </div>

                    <!-- Value 3 -->
                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10 hover:shadow-lg transition-shadow">
                        <div class="inline-flex items-center justify-center p-3 bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400 rounded-2xl mb-6">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Harga Transparan</h3>
                        <p class="text-gray-600 dark:text-gray-400">Tidak ada biaya tersembunyi. Semua tarif yang Anda lihat adalah tarif jujur yang akan Anda bayarkan.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
</AppLayout>
