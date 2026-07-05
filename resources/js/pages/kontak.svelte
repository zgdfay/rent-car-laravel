<script>
    import AppLayout from '@/layouts/app-layout.svelte';
    import { Button } from '@/components/ui/button/index.js';
    import { MapPin, Phone, Mail, Clock } from 'lucide-svelte';
    import { toast } from 'svelte-sonner';
</script>

<svelte:head>
    <title>Kontak Kami - DRS Rental</title>
</svelte:head>

<AppLayout>
    <main class="grow">
        <!-- Hero Section -->
        <div
            class="relative flex min-h-[35vh] flex-col justify-center overflow-hidden bg-blue-600 px-6 pt-32 pb-24 text-center sm:px-12 dark:bg-blue-900"
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

            <div class="relative z-10 mx-auto max-w-3xl">
                <h1
                    class="mb-4 text-3xl font-extrabold tracking-tight text-white sm:mb-6 sm:text-5xl lg:text-6xl"
                >
                    Hubungi Kami
                </h1>
                <p class="mx-auto max-w-2xl px-4 text-lg text-blue-100 sm:text-xl">
                    Tim kami selalu siap membantu Anda menemukan solusi transportasi terbaik kapan
                    pun Anda butuhkan.
                </p>
            </div>
        </div>

        <!-- Contact Content Section -->
        <section class="bg-gray-50 py-16 sm:py-24 dark:bg-gray-800/50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-16">
                    <!-- Contact Form -->
                    <div
                        class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-900/5 sm:p-10 dark:bg-gray-900 dark:ring-white/10"
                    >
                        <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                            Kirimkan Pesan
                        </h2>
                        <form
                            class="space-y-6"
                            on:submit|preventDefault={() =>
                                toast.success(
                                    'Pesan Anda terkirim! Tim kami akan segera merespon.',
                                )}
                        >
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label
                                        for="first-name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Nama Depan</label
                                    >
                                    <input
                                        type="text"
                                        id="first-name"
                                        class="mt-2 block w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                        required
                                    />
                                </div>
                                <div>
                                    <label
                                        for="last-name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Nama Belakang</label
                                    >
                                    <input
                                        type="text"
                                        id="last-name"
                                        class="mt-2 block w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                        required
                                    />
                                </div>
                            </div>

                            <div>
                                <label
                                    for="email"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Email</label
                                >
                                <input
                                    type="email"
                                    id="email"
                                    class="mt-2 block w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                    required
                                />
                            </div>

                            <div>
                                <label
                                    for="phone"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Nomer Telepon</label
                                >
                                <input
                                    type="tel"
                                    id="phone"
                                    class="mt-2 block w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                />
                            </div>

                            <div>
                                <label
                                    for="message"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Pesan</label
                                >
                                <textarea
                                    id="message"
                                    rows="4"
                                    class="mt-2 block w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                    required
                                ></textarea>
                            </div>

                            <Button
                                type="submit"
                                class="h-12 w-full rounded-xl bg-blue-600 text-lg text-white hover:bg-blue-700"
                            >
                                Kirim Pesan
                            </Button>
                        </form>
                    </div>

                    <!-- Contact Details -->
                    <div class="flex flex-col justify-center">
                        <div class="mb-10 lg:mb-14">
                            <h2
                                class="mb-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white"
                            >
                                Informasi Kontak
                            </h2>
                            <p class="text-lg text-gray-600 dark:text-gray-400">
                                Butuh respon cepat? Hubungi kami langsung melalui saluran di bawah
                                ini atau kunjungi kantor fisik kami.
                            </p>
                        </div>

                        <dl class="space-y-8">
                            <div class="relative pl-14">
                                <dt class="font-bold text-gray-900 dark:text-white">
                                    <div
                                        class="absolute top-0 left-0 flex items-center justify-center rounded-xl bg-blue-100 p-3 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400"
                                    >
                                        <MapPin class="h-6 w-6" />
                                    </div>
                                    <span class="text-lg">Alamat Kantor</span>
                                </dt>
                                <dd class="mt-2 text-base text-gray-600 dark:text-gray-400">
                                    Jl. Kolonel Sugiono 3<br />
                                    Pasuruan, 67142<br />
                                    Indonesia
                                </dd>
                            </div>

                            <div class="relative pl-14">
                                <dt class="font-bold text-gray-900 dark:text-white">
                                    <div
                                        class="absolute top-0 left-0 flex items-center justify-center rounded-xl bg-blue-100 p-3 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400"
                                    >
                                        <Phone class="h-6 w-6" />
                                    </div>
                                    <span class="text-lg">Telepon Pelayanan</span>
                                </dt>
                                <dd class="mt-2 text-base text-gray-600 dark:text-gray-400">
                                    +62 813 3333 2215 (WhatsApp Aktif 24/7)
                                </dd>
                            </div>

                            <div class="relative pl-14">
                                <dt class="font-bold text-gray-900 dark:text-white">
                                    <div
                                        class="absolute top-0 left-0 flex items-center justify-center rounded-xl bg-blue-100 p-3 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400"
                                    >
                                        <Mail class="h-6 w-6" />
                                    </div>
                                    <span class="text-lg">Email</span>
                                </dt>
                                <dd class="mt-2 text-base text-gray-600 dark:text-gray-400">
                                    info@drs-rental.com<br />
                                    support@drs-rental.com
                                </dd>
                            </div>

                            <div class="relative pl-14">
                                <dt class="font-bold text-gray-900 dark:text-white">
                                    <div
                                        class="absolute top-0 left-0 flex items-center justify-center rounded-xl bg-blue-100 p-3 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400"
                                    >
                                        <Clock class="h-6 w-6" />
                                    </div>
                                    <span class="text-lg">Jam Operasional Pelayanan</span>
                                </dt>
                                <dd class="mt-2 text-base text-gray-600 dark:text-gray-400">
                                    Layanan Rental: 06.00 - 22.00 (Setiap Hari)<br />
                                    Layanan Darurat Teknis: 24 Jam
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </section>
    </main>
</AppLayout>
