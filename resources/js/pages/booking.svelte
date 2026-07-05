<script>
    import AppLayout from '@/layouts/app-layout.svelte';
    import Head from '@/components/head.svelte';
    import { Button } from '@/components/ui/button/index.js';
    import { Input } from '@/components/ui/input/index.js';
    import { Label } from '@/components/ui/label/index.js';
    import {
        Calendar,
        User,
        Phone,
        MapPin,
        Car,
        Info,
        Upload,
        CreditCard,
        ShieldCheck,
        X,
    } from 'lucide-svelte';
    import { Separator } from '@/components/ui/separator/index.js';
    import { page, router } from '@inertiajs/svelte';
    import { get } from 'svelte/store';

    let { car, errors = {} } = $props();

    const currentUser = get(page).props.auth?.user || {};

    // Form State
    let name = $state(currentUser.name || '');
    let whatsapp = $state(currentUser.whatsapp || '');
    let address = $state(currentUser.address || '');
    let saveBiodata = $state(true);
    let isEditingBiodata = $state(!currentUser.whatsapp || !currentUser.address);
    let startDate = $state('');
    let endDate = $state('');
    let proofFile = $state(null);
    let previewUrl = $state(null);
    let isSubmitting = $state(false);

    // Summary calculation
    let duration = $derived.by(() => {
        if (!startDate || !endDate) return 0;
        const start = new Date(startDate);
        const end = new Date(endDate);
        if (isNaN(start.getTime()) || isNaN(end.getTime())) return 0;

        const diffTime = end - start;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays > 0 ? diffDays : 0;
    });

    let totalPrice = $derived.by(() => {
        if (duration <= 0) return 0;
        const pricePerDay = car.price_per_day || 0;
        return pricePerDay * duration;
    });

    let isFormValid = $derived.by(() => {
        return (
            name.trim() !== '' &&
            whatsapp.trim() !== '' &&
            address.trim() !== '' &&
            startDate !== '' &&
            endDate !== '' &&
            duration > 0 &&
            proofFile !== null
        );
    });

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    }

    function handleFileChange(e) {
        const file = e.target.files[0];
        if (file) {
            proofFile = file;
            // Create preview URL
            if (previewUrl) URL.revokeObjectURL(previewUrl);
            previewUrl = URL.createObjectURL(file);
        }
    }

    function removeFile() {
        proofFile = null;
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }
        // Reset file input
        const input = document.getElementById('proof');
        if (input) input.value = '';
    }

    function handleSubmit(e) {
        e.preventDefault();
        if (!isFormValid || isSubmitting) return;

        isSubmitting = true;

        const pricePerDay = car.price_per_day || 0;

        const formData = new FormData();
        formData.append('car_id', car.id);
        formData.append('car_name', car.name);
        formData.append('car_image', car.image);
        formData.append('car_price_per_day', pricePerDay);
        formData.append('name', name);
        formData.append('whatsapp', whatsapp);
        formData.append('address', address);
        formData.append('start_date', startDate);
        formData.append('end_date', endDate);
        formData.append('duration', duration);
        formData.append('total_price', totalPrice);
        formData.append('payment_proof', proofFile);
        formData.append('save_biodata', saveBiodata ? '1' : '0');

        router.post('/booking', formData, {
            forceFormData: true,
            onFinish: () => {
                isSubmitting = false;
            },
        });
    }
</script>

<Head title="Booking Mobil - {car.name}" />

<AppLayout>
    <div class="py-6 sm:py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-8">
                <ol class="flex space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li>
                        <a href="/" class="hover:text-gray-900 dark:hover:text-white">Beranda</a>
                    </li>
                    <li class="flex items-center">
                        <span class="mx-2">/</span>
                        <a href="/list-car" class="hover:text-gray-900 dark:hover:text-white"
                            >Daftar Mobil</a
                        >
                    </li>
                    <li class="flex items-center">
                        <span class="mx-2">/</span>
                        <span class="font-medium text-gray-900 dark:text-white"
                            >Booking {car.name}</span
                        >
                    </li>
                </ol>
            </nav>
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                    Booking Kendaraan
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Lengkapi formulir di bawah ini untuk melakukan penyewaan.
                </p>
            </div>

            <form
                onsubmit={handleSubmit}
                class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12"
            >
                <!-- Left Grid: Form -->
                <div class="space-y-6 lg:col-span-7">
                    <div
                        class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 sm:p-8 dark:bg-gray-900 dark:ring-white/10"
                    >
                        <div class="mb-6 flex items-center justify-between">
                            <h2
                                class="flex items-center gap-2 text-xl font-bold text-gray-900 dark:text-white"
                            >
                                <User class="h-5 w-5 text-blue-600" /> Informasi Penyewa
                            </h2>
                            {#if currentUser.whatsapp && currentUser.address}
                                <button
                                    type="button"
                                    onclick={() => (isEditingBiodata = !isEditingBiodata)}
                                    class="text-xs font-semibold text-blue-600 underline hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
                                >
                                    {isEditingBiodata
                                        ? 'Gunakan Biodata Akun'
                                        : 'Ubah Data Penyewa'}
                                </button>
                            {/if}
                        </div>

                        {#if !isEditingBiodata && currentUser.whatsapp && currentUser.address}
                            <div
                                class="space-y-3 rounded-2xl border border-blue-100 bg-blue-50/50 p-4 dark:border-blue-900/30 dark:bg-blue-900/10"
                            >
                                <div class="flex items-center justify-between">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800 dark:bg-blue-900/50 dark:text-blue-200"
                                    >
                                        <ShieldCheck
                                            class="h-3.5 w-3.5 text-blue-600 dark:text-blue-400"
                                        />
                                        Menggunakan Biodata Akun
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                                    <div>
                                        <span
                                            class="block text-xs text-gray-500 dark:text-gray-400"
                                            >Nama Lengkap</span
                                        >
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white"
                                            >{name}</span
                                        >
                                    </div>
                                    <div>
                                        <span
                                            class="block text-xs text-gray-500 dark:text-gray-400"
                                            >No. WhatsApp</span
                                        >
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white"
                                            >{whatsapp}</span
                                        >
                                    </div>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-500 dark:text-gray-400"
                                        >Alamat Lengkap</span
                                    >
                                    <span class="font-semibold text-gray-900 dark:text-white"
                                        >{address}</span
                                    >
                                </div>
                            </div>
                        {:else}
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div class="space-y-2">
                                        <Label for="name">Nama Lengkap</Label>
                                        <Input
                                            id="name"
                                            placeholder="Masukkan nama lengkap"
                                            bind:value={name}
                                            required
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="whatsapp">No. WhatsApp</Label>
                                        <Input
                                            id="whatsapp"
                                            placeholder="Contoh: 08123456789"
                                            bind:value={whatsapp}
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <Label for="address">Alamat Lengkap</Label>
                                    <textarea
                                        id="address"
                                        bind:value={address}
                                        class="flex min-h-[100px] w-full rounded-xl border border-gray-200 bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-gray-500 focus-visible:ring-1 focus-visible:ring-blue-500 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-800 dark:text-white"
                                        placeholder="Masukkan alamat lengkap Anda"
                                        required
                                    ></textarea>
                                </div>

                                <div class="flex items-center gap-2 pt-1">
                                    <input
                                        type="checkbox"
                                        id="saveBiodata"
                                        bind:checked={saveBiodata}
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    />
                                    <label
                                        for="saveBiodata"
                                        class="cursor-pointer text-xs text-gray-600 dark:text-gray-400"
                                    >
                                        Simpan perubahan kontak ini ke Profil Biodata saya
                                    </label>
                                </div>
                            </div>
                        {/if}
                    </div>

                    <div
                        class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 sm:p-8 dark:bg-gray-900 dark:ring-white/10"
                    >
                        <h2
                            class="mb-6 flex items-center gap-2 text-xl font-bold text-gray-900 dark:text-white"
                        >
                            <Calendar class="h-5 w-5 text-blue-600" /> Jadwal Sewa
                        </h2>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="startDate">Tanggal Mulai</Label>
                                <Input type="date" id="startDate" bind:value={startDate} required />
                            </div>
                            <div class="space-y-2">
                                <Label for="endDate">Tanggal Selesai</Label>
                                <Input type="date" id="endDate" bind:value={endDate} required />
                            </div>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div
                        class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 sm:p-8 dark:bg-gray-900 dark:ring-white/10"
                    >
                        <h2
                            class="mb-6 flex items-center gap-2 text-xl font-bold text-gray-900 dark:text-white"
                        >
                            <CreditCard class="h-5 w-5 text-blue-600" /> Pembayaran
                        </h2>

                        <div
                            class="mb-8 rounded-2xl border border-blue-100 bg-blue-50 p-4 dark:border-blue-900/30 dark:bg-blue-900/20"
                        >
                            <div class="mb-4 flex items-center gap-3">
                                <img
                                    src="/assets/images/bca-logo.png"
                                    alt="BCA"
                                    class="h-10 w-auto rounded"
                                />
                            </div>
                            <div class="space-y-1">
                                <p
                                    class="text-xs font-bold tracking-tight text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Nomor Rekening:
                                </p>
                                <p
                                    class="font-mono text-2xl leading-none font-bold tracking-wider text-gray-900 dark:text-white"
                                >
                                    1234 5678 90
                                </p>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    A/N <span class="font-bold">DRS Rental Mobil Indonesia</span>
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="proof">Upload Bukti Pembayaran</Label>
                            <div class="group relative">
                                <input
                                    type="file"
                                    id="proof"
                                    onchange={handleFileChange}
                                    class="hidden"
                                    accept="image/*"
                                    required
                                />
                                {#if previewUrl}
                                    <!-- Image Preview -->
                                    <div
                                        class="relative w-full overflow-hidden rounded-2xl border-2 border-green-300 bg-gray-50 dark:border-green-700 dark:bg-gray-800/30"
                                    >
                                        <img
                                            src={previewUrl}
                                            alt="Preview bukti pembayaran"
                                            class="max-h-64 w-full object-contain"
                                        />
                                        <div
                                            class="absolute right-0 bottom-0 left-0 bg-gradient-to-t from-black/60 to-transparent p-4"
                                        >
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="mr-4 truncate text-sm font-medium text-white"
                                                    >{proofFile?.name}</span
                                                >
                                                <button
                                                    type="button"
                                                    onclick={removeFile}
                                                    class="flex items-center gap-1.5 rounded-lg bg-red-500 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-red-600"
                                                >
                                                    <X class="h-3 w-3" /> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                {:else}
                                    <!-- Upload Area -->
                                    <label
                                        for="proof"
                                        class="flex h-40 w-full border-spacing-4 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50/50 transition-all group-hover:border-blue-500/50 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-800/30 dark:hover:bg-gray-800/50"
                                    >
                                        <div class="flex flex-col items-center gap-2 text-gray-400">
                                            <div
                                                class="flex h-12 w-12 items-center justify-center rounded-full border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800"
                                            >
                                                <Upload class="h-6 w-6" />
                                            </div>
                                            <p class="text-sm">Klik untuk upload bukti transfer</p>
                                            <p class="text-[10px] opacity-70">
                                                JPG, PNG (Max. 2MB)
                                            </p>
                                        </div>
                                    </label>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Grid: Summary Card -->
                <div class="lg:sticky lg:top-24 lg:col-span-5">
                    <div
                        class="overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
                    >
                        <div
                            class="border-b border-gray-100 bg-gray-50 p-6 dark:border-gray-800 dark:bg-gray-800/50"
                        >
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                Ringkasan Penyewaan
                            </h3>
                        </div>

                        <div class="space-y-6 p-6 sm:p-8">
                            <!-- Car Info -->
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-24 w-24 shrink-0 items-center justify-center rounded-2xl bg-gray-100 p-2 dark:bg-gray-800"
                                >
                                    <img
                                        src={car.image}
                                        alt={car.name}
                                        class="h-full w-full object-contain"
                                    />
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">
                                        {car.name}
                                    </h4>
                                    <div class="mt-1 flex items-center gap-3 text-sm text-gray-500">
                                        <span class="flex items-center gap-1"
                                            ><Car class="h-4 w-4" /> {car.category}</span
                                        >
                                        <span class="flex items-center gap-1"
                                            ><Info class="h-4 w-4" /> {car.transmission}</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <Separator />

                            <!-- Rental Details -->
                            <div class="space-y-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Harga Sewa / Hari</span>
                                    <span class="font-bold text-gray-900 dark:text-white"
                                        >{formatCurrency(car.price_per_day)}</span
                                    >
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Durasi Sewa</span>
                                    <span class="font-bold text-gray-900 dark:text-white"
                                        >{duration} Hari</span
                                    >
                                </div>

                                {#if startDate && endDate}
                                    <div
                                        class="space-y-2 rounded-xl border border-blue-50 bg-gray-50 p-3 text-xs dark:border-blue-900/20 dark:bg-gray-800"
                                    >
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-400 italic">Mulai:</span>
                                            <span
                                                class="font-semibold text-gray-900 dark:text-white"
                                                >{startDate}</span
                                            >
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-400 italic">Selesai:</span>
                                            <span
                                                class="font-semibold text-gray-900 dark:text-white"
                                                >{endDate}</span
                                            >
                                        </div>
                                    </div>
                                {/if}
                            </div>

                            <Separator />

                            <!-- Total -->
                            <div class="flex items-end justify-between">
                                <div>
                                    <p
                                        class="mb-1 text-xs font-bold tracking-wider text-gray-500 uppercase"
                                    >
                                        Total Pembayaran
                                    </p>
                                    <p
                                        class="text-3xl font-extrabold text-blue-600 dark:text-blue-400"
                                    >
                                        {formatCurrency(totalPrice)}
                                    </p>
                                </div>
                            </div>

                            {#if Object.keys(errors).length > 0}
                                <div class="rounded-xl bg-red-50 p-4 border border-red-200">
                                    <p class="text-sm font-semibold text-red-800 mb-2">Terjadi kesalahan:</p>
                                    <ul class="list-disc pl-5 text-xs text-red-600">
                                        {#each Object.values(errors) as error}
                                            <li>{error}</li>
                                        {/each}
                                    </ul>
                                </div>
                            {/if}

                            <Button
                                type="submit"
                                disabled={!isFormValid || isSubmitting}
                                class="h-14 w-full rounded-2xl bg-blue-600 text-lg font-bold text-white shadow-lg shadow-blue-500/20 transition-all hover:scale-[1.02] hover:bg-blue-700 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:scale-100 dark:bg-blue-500 dark:hover:bg-blue-600"
                            >
                                {#if isSubmitting}
                                    <div
                                        class="mr-2 h-5 w-5 animate-spin rounded-full border-2 border-white/30 border-t-white"
                                    ></div>
                                    Memproses...
                                {:else}
                                    Sewa Sekarang
                                {/if}
                            </Button>

                            <div
                                class="flex items-center justify-center gap-2 text-[10px] text-gray-400"
                            >
                                <ShieldCheck class="h-3 w-3" />
                                <span>Penyewaan aman dan terpercaya</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</AppLayout>
