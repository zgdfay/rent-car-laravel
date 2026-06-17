<script>
    import AppLayout from '@/layouts/app-layout.svelte';
    import { Button } from '@/components/ui/button/index.js';
    import { Link } from '@inertiajs/svelte';
    import { Users, Settings2, Zap } from 'lucide-svelte';

    export let cars = [];

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    }

    // Extract unique categories reactively
    $: categories = [...new Set((cars || []).map((car) => car.category))];
    const transmissions = ['Otomatis', 'Manual', 'Manual/Oto'];

    // State
    let searchQuery = '';
    let selectedCategory = 'Semua';
    let selectedTransmissions = [];
    let itemsPerPage = 6;
    let visibleCount = itemsPerPage;

    // Reactive filtered data
    $: filteredCars = (cars || []).filter((car) => {
        const matchesSearch = car.name.toLowerCase().includes(searchQuery.toLowerCase());
        const matchesCategory = selectedCategory === 'Semua' || car.category === selectedCategory;
        const matchesTransmission =
            selectedTransmissions.length === 0 || selectedTransmissions.includes(car.transmission);

        return matchesSearch && matchesCategory && matchesTransmission;
    });

    // Handle pagination (Load More)
    $: displayedCars = filteredCars.slice(0, visibleCount);
    $: hasMore = visibleCount < filteredCars.length;
    let isLoading = false;

    function loadMore() {
        isLoading = true;
        setTimeout(() => {
            visibleCount += itemsPerPage;
            isLoading = false;
        }, 800); // Simulate network request delay
    }

    // Reset visible count when filters change
    $: if (searchQuery || selectedCategory || selectedTransmissions) {
        visibleCount = itemsPerPage;
    }

    function toggleTransmission(trans) {
        if (selectedTransmissions.includes(trans)) {
            selectedTransmissions = selectedTransmissions.filter((t) => t !== trans);
        } else {
            selectedTransmissions = [...selectedTransmissions, trans];
        }
    }
</script>

<svelte:head>
    <title>Daftar Mobil - DRS Rental</title>
</svelte:head>

<AppLayout>
    <!-- Page Header -->
    <div
        class="relative flex min-h-[30vh] flex-col justify-center overflow-hidden bg-blue-600 px-6 py-24 text-center sm:px-12 sm:py-32 dark:bg-blue-900"
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
                Daftar Pilihan Kendaraan
            </h1>
            <p class="mx-auto max-w-2xl px-4 text-lg text-blue-100 sm:text-xl">
                Jelajahi armada kami dan temukan kendaraan yang sempurna untuk rencana perjalanan
                Anda berikutnya.
            </p>
        </div>
    </div>

    <!-- Layout Content -->
    <section class="bg-gray-50 py-12 sm:py-16 dark:bg-gray-800/50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-8 lg:flex-row lg:gap-12">
                <!-- Sidebar Filter -->
                <aside class="w-full lg:w-1/4 lg:flex-shrink-0 xl:w-1/5">
                    <div
                        class="sticky top-24 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 sm:p-8 dark:bg-gray-900 dark:ring-white/10"
                    >
                        <!-- Search -->
                        <div class="mb-8">
                            <h3 class="mb-4 text-base font-bold text-gray-900 dark:text-white">
                                Pencarian
                            </h3>
                            <div class="relative">
                                <input
                                    type="text"
                                    bind:value={searchQuery}
                                    placeholder="Cari mobil..."
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pl-10 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                />
                                <svg
                                    class="absolute top-3.5 left-3 h-5 w-5 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                    />
                                </svg>
                            </div>
                        </div>

                        <hr class="my-8 border-gray-100 dark:border-gray-800" />

                        <!-- Category Filter -->
                        <div class="mb-8">
                            <h3 class="mb-4 text-base font-bold text-gray-900 dark:text-white">
                                Kategori
                            </h3>
                            <div class="space-y-3">
                                <label class="group flex cursor-pointer items-center">
                                    <div class="relative flex items-center justify-center">
                                        <input
                                            type="radio"
                                            name="category"
                                            value="Semua"
                                            bind:group={selectedCategory}
                                            class="peer sr-only"
                                        />
                                        <div
                                            class="flex h-5 w-5 items-center justify-center rounded-md border-2 border-gray-300 transition-all peer-checked:border-blue-600 peer-checked:bg-blue-600 dark:border-gray-600"
                                        >
                                            <svg
                                                class="h-3 w-3 text-white opacity-0 transition-opacity peer-checked:opacity-100"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="3"
                                                ><path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M5 13l4 4L19 7"
                                                /></svg
                                            >
                                        </div>
                                    </div>
                                    <span
                                        class="ml-3 text-sm font-medium text-gray-700 transition-colors group-hover:text-blue-600 dark:text-gray-300 dark:group-hover:text-blue-400"
                                        >Semua Kategori</span
                                    >
                                </label>

                                {#each categories as category}
                                    <label class="group flex cursor-pointer items-center">
                                        <div class="relative flex items-center justify-center">
                                            <input
                                                type="radio"
                                                name="category"
                                                value={category}
                                                bind:group={selectedCategory}
                                                class="peer sr-only"
                                            />
                                            <div
                                                class="flex h-5 w-5 items-center justify-center rounded-md border-2 border-gray-300 transition-all peer-checked:border-blue-600 peer-checked:bg-blue-600 dark:border-gray-600"
                                            >
                                                <svg
                                                    class="h-3 w-3 text-white opacity-0 transition-opacity peer-checked:opacity-100"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="3"
                                                    ><path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7"
                                                    /></svg
                                                >
                                            </div>
                                        </div>
                                        <span
                                            class="ml-3 text-sm font-medium text-gray-700 transition-colors group-hover:text-blue-600 dark:text-gray-300 dark:group-hover:text-blue-400"
                                            >{category}</span
                                        >
                                    </label>
                                {/each}
                            </div>
                        </div>

                        <hr class="my-8 border-gray-100 dark:border-gray-800" />

                        <!-- Transmission Filter -->
                        <div>
                            <h3 class="mb-4 text-base font-bold text-gray-900 dark:text-white">
                                Transmisi
                            </h3>
                            <div class="space-y-3">
                                {#each transmissions as trans}
                                    <label class="group flex cursor-pointer items-center">
                                        <div class="relative flex items-center justify-center">
                                            <input
                                                type="checkbox"
                                                checked={selectedTransmissions.includes(trans)}
                                                on:change={() => toggleTransmission(trans)}
                                                class="peer sr-only"
                                            />
                                            <div
                                                class="flex h-5 w-5 items-center justify-center rounded border border-gray-300 transition-all peer-checked:border-blue-600 peer-checked:bg-blue-600 dark:border-gray-600"
                                            >
                                                <svg
                                                    class="h-3 w-3 text-white opacity-0 transition-opacity peer-checked:opacity-100"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="3"
                                                    ><path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7"
                                                    /></svg
                                                >
                                            </div>
                                        </div>
                                        <span
                                            class="ml-3 text-sm font-medium text-gray-700 transition-colors group-hover:text-blue-600 dark:text-gray-300 dark:group-hover:text-blue-400"
                                            >{trans}</span
                                        >
                                    </label>
                                {/each}
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Main List Area -->
                <div class="w-full lg:w-3/4 xl:w-4/5">
                    <div
                        class="mb-6 flex flex-col items-start justify-between gap-4 sm:mb-8 sm:flex-row sm:items-center"
                    >
                        <p class="font-medium text-gray-600 dark:text-gray-400">
                            Menampilkan <span class="font-bold text-gray-900 dark:text-white"
                                >{displayedCars.length}</span
                            >
                            dari
                            <span class="font-bold text-gray-900 dark:text-white"
                                >{filteredCars.length}</span
                            > kendaraan
                        </p>

                        <!-- Mobile active filters summary -->
                        {#if selectedCategory !== 'Semua' || selectedTransmissions.length > 0}
                            <div class="flex flex-wrap gap-2 sm:justify-end">
                                {#if selectedCategory !== 'Semua'}
                                    <span
                                        class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/40 dark:text-blue-300"
                                    >
                                        {selectedCategory}
                                        <button
                                            on:click={() => (selectedCategory = 'Semua')}
                                            class="ml-2 hover:text-blue-600">&times;</button
                                        >
                                    </span>
                                {/if}
                                {#each selectedTransmissions as trans}
                                    <span
                                        class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800 dark:bg-gray-800 dark:text-gray-300"
                                    >
                                        {trans}
                                        <button
                                            on:click={() => toggleTransmission(trans)}
                                            class="ml-2 hover:text-gray-600">&times;</button
                                        >
                                    </span>
                                {/each}
                            </div>
                        {/if}
                    </div>

                    {#if displayedCars.length === 0}
                        <div
                            class="rounded-3xl bg-white p-12 text-center ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
                        >
                            <svg
                                class="mx-auto h-16 w-16 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"
                                />
                            </svg>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">
                                Tidak ada mobil ditemukan
                            </h3>
                            <p class="mx-auto mt-2 max-w-sm text-gray-500">
                                Kami tidak menemukan mobil yang sesuai dengan kriteria filter Anda.
                                Coba hapus beberapa filter untuk melihat lebih banyak hasil.
                            </p>
                            <button
                                on:click={() => {
                                    searchQuery = '';
                                    selectedCategory = 'Semua';
                                    selectedTransmissions = [];
                                }}
                                class="mt-6 inline-flex h-10 items-center justify-center rounded-xl border border-blue-600 px-6 text-sm font-medium text-blue-600 transition-colors hover:bg-blue-50 dark:border-blue-400 dark:text-blue-400"
                            >
                                Reset Semua Filter
                            </button>
                        </div>
                    {:else}
                        <div class="grid grid-cols-1 gap-6 sm:gap-8 md:grid-cols-2 xl:grid-cols-3">
                            {#each displayedCars as car}
                                <div
                                    class="group relative flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5 transition-all duration-300 hover:shadow-2xl dark:bg-gray-900 dark:ring-white/10 {car.is_available
                                        ? 'hover:-translate-y-2'
                                        : 'opacity-80'}"
                                >
                                    <div
                                        class="relative flex h-48 w-full items-end justify-center overflow-hidden bg-gray-200 p-4 pb-0 sm:h-56 dark:bg-gray-800"
                                    >
                                        <img
                                            src={car.image}
                                            alt={car.name}
                                            on:error={(e) =>
                                                (e.target.src = '/assets/images/hero-ferrari.png')}
                                            class="h-full w-full object-contain object-bottom transition-transform duration-500 {car.is_available
                                                ? 'group-hover:scale-105'
                                                : 'grayscale'}"
                                        />
                                        <div
                                            class="absolute top-4 left-4 flex flex-col gap-2 sm:flex-row"
                                        >
                                            <span
                                                class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-xs font-medium text-gray-900 shadow-sm ring-1 ring-black/5 backdrop-blur-sm dark:bg-gray-900/90 dark:text-white"
                                            >
                                                {car.category}
                                            </span>
                                            {#if !car.is_available}
                                                <span
                                                    class="inline-flex items-center rounded-full bg-red-100/90 px-3 py-1 text-xs font-medium text-red-700 shadow-sm ring-1 ring-red-600/10 backdrop-blur-sm dark:bg-red-900/90 dark:text-red-300"
                                                >
                                                    Sedang Disewa
                                                </span>
                                            {/if}
                                        </div>
                                    </div>

                                    <div
                                        class="flex flex-col p-5 sm:p-6 {car.is_available
                                            ? ''
                                            : 'opacity-60 grayscale'}"
                                    >
                                        <div class="mb-4 text-left">
                                            <h3
                                                class="mb-2 text-xl font-bold text-gray-900 sm:text-2xl dark:text-white"
                                            >
                                                {car.name}
                                            </h3>
                                            <p
                                                class="text-xl font-bold sm:text-2xl {car.is_available
                                                    ? 'text-blue-600 dark:text-blue-400'
                                                    : 'text-gray-500 dark:text-gray-400'} whitespace-nowrap"
                                            >
                                                {formatCurrency(car.price_per_day)}<span
                                                    class="text-sm font-normal text-gray-500 dark:text-gray-400"
                                                    >/hari</span
                                                >
                                            </p>
                                        </div>

                                        <div
                                            class="mt-auto mb-6 grid grid-cols-3 gap-2 border-t border-gray-100 pt-5 dark:border-gray-800"
                                        >
                                            <div
                                                class="flex flex-col items-center justify-center rounded-2xl bg-gray-50/80 px-1 py-3 text-center dark:bg-gray-800/80"
                                            >
                                                <Users
                                                    class="mb-2 h-5 w-5 stroke-[1.5] text-gray-500 sm:h-6 sm:w-6 dark:text-gray-400"
                                                />
                                                <span
                                                    class="w-full truncate text-[10px] font-semibold text-gray-700 sm:text-xs dark:text-gray-300"
                                                    >{car.seats} Kursi</span
                                                >
                                            </div>
                                            <div
                                                class="flex flex-col items-center justify-center rounded-2xl bg-gray-50/80 px-1 py-3 text-center dark:bg-gray-800/80"
                                            >
                                                <Settings2
                                                    class="mb-2 h-5 w-5 stroke-[1.5] text-gray-500 sm:h-6 sm:w-6 dark:text-gray-400"
                                                />
                                                <span
                                                    class="w-full truncate text-[10px] font-semibold text-gray-700 sm:text-xs dark:text-gray-300"
                                                    >{car.transmission}</span
                                                >
                                            </div>
                                            <div
                                                class="flex flex-col items-center justify-center rounded-2xl bg-gray-50/80 px-1 py-3 text-center dark:bg-gray-800/80"
                                            >
                                                <Zap
                                                    class="mb-2 h-5 w-5 stroke-[1.5] text-gray-500 sm:h-6 sm:w-6 dark:text-gray-400"
                                                />
                                                <span
                                                    class="w-full truncate text-[10px] font-semibold text-gray-700 sm:text-xs dark:text-gray-300"
                                                    >{car.fuel}</span
                                                >
                                            </div>
                                        </div>

                                        <Link
                                            href={car.is_available ? `/booking/${car.id}` : '#'}
                                            class="block w-full {car.is_available
                                                ? ''
                                                : 'cursor-not-allowed'}"
                                        >
                                            <Button
                                                disabled={!car.is_available}
                                                class="h-12 w-full rounded-xl bg-gray-900 font-semibold text-white shadow-sm hover:bg-gray-800 disabled:bg-gray-200 disabled:text-gray-500 disabled:opacity-100 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100 disabled:dark:bg-gray-800 disabled:dark:text-gray-600"
                                            >
                                                {car.is_available
                                                    ? 'Sewa Sekarang'
                                                    : 'Tidak Tersedia'}
                                            </Button>
                                        </Link>
                                    </div>
                                </div>
                            {/each}
                        </div>

                        <!-- Load More -->
                        {#if hasMore}
                            <div class="mt-12 flex justify-center">
                                <button
                                    on:click={loadMore}
                                    disabled={isLoading}
                                    class="inline-flex h-12 items-center justify-center rounded-xl border border-gray-300 px-8 text-sm font-medium text-gray-700 transition-all hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                                >
                                    {#if isLoading}
                                        <svg
                                            class="mr-2 -ml-1 h-4 w-4 animate-spin text-gray-700 dark:text-gray-300"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                        Memuat...
                                    {:else}
                                        Muat Lebih Banyak
                                    {/if}
                                </button>
                            </div>
                        {/if}
                    {/if}
                </div>
            </div>
        </div>
    </section>
</AppLayout>
