<script>
    import AppLayout from '@/layouts/app-layout.svelte';
</script>

<svelte:head>
    <title>Tentang Kami - DRS Rental</title>
</svelte:head>

<AppLayout>
    <main class="grow">
        <!-- Hero Section -->
        <div
            class="relative flex min-h-[40vh] flex-col justify-center overflow-hidden bg-blue-600 px-6 py-24 text-center sm:px-12 sm:py-32 dark:bg-blue-900"
        >
            <div class="absolute inset-0 opacity-10">
                <svg
                    class="absolute top-0 left-0 h-full w-full"
                    viewBox="0 0 100 100"
                    preserveAspectRatio="none"
                >
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="currentColor"></path>
                </svg>
            </div>

            <div class="relative z-10 mx-auto mt-4 max-w-3xl sm:mt-8">
                <h1
                    class="mb-4 text-3xl font-extrabold tracking-tight text-white sm:mb-6 sm:text-5xl lg:text-6xl"
                >
                    Tentang DRS Rental
                </h1>
                <p class="mx-auto max-w-2xl px-4 text-lg text-blue-100 sm:text-xl">
                    Mitra perjalanan terpercaya Anda, menyediakan kendaraan berkualitas untuk setiap
                    momen berharga.
                </p>
            </div>
        </div>

        <!-- Content Section -->
        <section class="bg-white py-16 sm:py-24 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2 lg:gap-24">
                    <div>
                        <h2
                            class="mb-6 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white"
                        >
                            Cerita Kami
                        </h2>
                        <div class="space-y-6 text-lg text-gray-600 dark:text-gray-300">
                            <p>
                                Berdiri sejak tahun 2018, DRS Rental lahir dari sebuah visi
                                sederhana: membuat perjalanan menjadi lebih mudah, aman, dan
                                berkesan bagi semua orang. Kami memahami bahwa kendaraan bukan
                                sekadar alat transportasi, melainkan kunci untuk menciptakan
                                pengalaman tak terlupakan bersama keluarga, teman, atau rekan
                                bisnis.
                            </p>
                            <p>
                                Dimulai dengan hanya lima armada, dedikasi kami terhadap kualitas
                                layanan dan kepuasan pelanggan telah membawa kami tumbuh menjadi
                                salah satu penyedia layanan rental mobil terpercaya di kota ini.
                            </p>
                            <p>
                                Hari ini, kami bangga menyajikan beragam pilihan kendaraan mulai
                                dari mobil kota yang efisien, SUV keluarga yang tangguh, hingga
                                mobil premium untuk momen spesial Anda, semuanya dalam kondisi prima
                                dan terawat dengan standar tertinggi.
                            </p>
                        </div>
                    </div>

                    <div class="relative">
                        <div
                            class="absolute inset-0 scale-105 rotate-3 transform rounded-3xl bg-gradient-to-tr from-blue-100 to-transparent dark:from-blue-900/30"
                        ></div>
                        <img
                            src="/assets/images/showroom-image.jpg"
                            alt="DRS Rental Fleet"
                            class="relative rounded-3xl object-cover shadow-2xl ring-1 ring-gray-900/5 dark:ring-white/10"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Values Section -->
        <section class="bg-gray-50 py-16 sm:py-24 dark:bg-gray-800/50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto mb-16 max-w-3xl text-center">
                    <h2
                        class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white"
                    >
                        Mengapa Memilih Kami?
                    </h2>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                        Kami tidak hanya menyewakan mobil, kami berkomitmen memberikan pelayanan
                        terbaik.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <!-- Value 1 -->
                    <div
                        class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-900/5 transition-shadow hover:shadow-lg dark:bg-gray-900 dark:ring-white/10"
                    >
                        <div
                            class="mb-6 inline-flex items-center justify-center rounded-2xl bg-blue-100 p-3 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400"
                        >
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                />
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-gray-900 dark:text-white">
                            Keamanan Terjamin
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Seluruh unit kami secara rutin diperiksa dan dirawat oleh mekanik
                            tersertifikasi untuk memastikan keamanan perjalanan Anda.
                        </p>
                    </div>

                    <!-- Value 2 -->
                    <div
                        class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-900/5 transition-shadow hover:shadow-lg dark:bg-gray-900 dark:ring-white/10"
                    >
                        <div
                            class="mb-6 inline-flex items-center justify-center rounded-2xl bg-blue-100 p-3 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400"
                        >
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-gray-900 dark:text-white">
                            Layanan 24 Jam
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Tim bantuan teknis kami selalu siap sedia 24/7 untuk membantu Anda jika
                            terjadi kendala di jalan.
                        </p>
                    </div>

                    <!-- Value 3 -->
                    <div
                        class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-900/5 transition-shadow hover:shadow-lg dark:bg-gray-900 dark:ring-white/10"
                    >
                        <div
                            class="mb-6 inline-flex items-center justify-center rounded-2xl bg-blue-100 p-3 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400"
                        >
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"
                                />
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-gray-900 dark:text-white">
                            Harga Transparan
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Tidak ada biaya tersembunyi. Semua tarif yang Anda lihat adalah tarif
                            jujur yang akan Anda bayarkan.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>
</AppLayout>
