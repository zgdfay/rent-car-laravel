<script module>
    export { default as layout } from '@/layouts/authenticated-layout.svelte';
</script>

<script>
    import { router } from '@inertiajs/svelte';
    import { Button } from '@/components/ui/button/index.js';
    import * as AlertDialog from '@/components/ui/alert-dialog/index.js';
    import {
        Car,
        Users as UsersIcon,
        Fuel,
        Settings2,
        CircleCheck,
        CircleX,
        Search,
        Plus,
        X,
        Trash2,
        Edit2,
        Upload,
    } from 'lucide-svelte';

    let { cars = [] } = $props();

    let searchQuery = $state('');
    let filterAvailability = $state('all');
    let showAddModal = $state(false);
    let imagePreview = $state(null);
    let alertDialogState = $state({ open: false, carId: null });
    let editingId = $state(null);

    // Form state
    let form = $state({
        name: '',
        category: '',
        price_per_day: '',
        seats: '5',
        transmission: 'Otomatis',
        fuel: 'Bensin',
        is_available: true,
        image: null,
    });

    let isSubmitting = $state(false);

    let filteredCars = $derived.by(() => {
        let result = cars;
        if (filterAvailability === 'available') result = result.filter((c) => c.is_available);
        else if (filterAvailability === 'unavailable')
            result = result.filter((c) => !c.is_available);
        if (searchQuery.trim()) {
            const q = searchQuery.toLowerCase();
            result = result.filter(
                (c) => c.name.toLowerCase().includes(q) || c.category.toLowerCase().includes(q),
            );
        }
        return result;
    });

    const availableCount = $derived(cars.filter((c) => c.is_available).length);
    const unavailableCount = $derived(cars.filter((c) => !c.is_available).length);

    let formValid = $derived(
        form.name.trim() && form.category.trim() && form.price_per_day && form.seats,
    );

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    }

    function handleImageChange(e) {
        const file = e.target.files[0];
        if (file) {
            form.image = file;
            const reader = new FileReader();
            reader.onload = (ev) => (imagePreview = ev.target.result);
            reader.readAsDataURL(file);
        }
    }

    function resetForm() {
        form = {
            name: '',
            category: '',
            price_per_day: '',
            seats: '5',
            transmission: 'Otomatis',
            fuel: 'Bensin',
            is_available: true,
            image: null,
        };
        imagePreview = null;
        editingId = null;
    }

    function openModal() {
        resetForm();
        showAddModal = true;
    }

    function openEditModal(car) {
        editingId = car.id;
        form = {
            name: car.name,
            category: car.category,
            price_per_day: car.price_per_day,
            seats: car.seats,
            transmission: car.transmission,
            fuel: car.fuel,
            is_available: car.is_available,
            image: null, // Keep existing unless user uploads new
        };
        imagePreview = car.image; // Assume car.image is an absolute URL or path suitable for src
        showAddModal = true;
    }

    function closeModal() {
        showAddModal = false;
        resetForm();
    }

    function submitForm() {
        if (!formValid || isSubmitting) return;
        isSubmitting = true;

        const formData = new FormData();
        formData.append('name', form.name);
        formData.append('category', form.category);
        formData.append('price_per_day', form.price_per_day);
        formData.append('seats', form.seats);
        formData.append('transmission', form.transmission);
        formData.append('fuel', form.fuel);
        formData.append('is_available', form.is_available ? '1' : '0');
        if (form.image) formData.append('image', form.image);

        const url = editingId ? `/admin/mobil/${editingId}` : '/admin/mobil';

        router.post(url, formData, {
            forceFormData: true,
            onSuccess: () => {
                closeModal();
                isSubmitting = false;
            },
            onError: () => {
                isSubmitting = false;
            },
        });
    }

    function deleteCar(id) {
        alertDialogState = { open: true, carId: id };
    }

    function confirmDeleteCar() {
        if (alertDialogState.carId) {
            router.delete(`/admin/mobil/${alertDialogState.carId}`, {
                preserveScroll: true,
                onSuccess: () => {
                    alertDialogState = { open: false, carId: null };
                },
            });
        }
    }
</script>

<!-- Add Modal Overlay -->
{#if showAddModal}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick={closeModal}></div>
        <div
            class="relative z-10 max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-2xl ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
        >
            <!-- Modal Header -->
            <div
                class="sticky top-0 z-10 flex items-center justify-between rounded-t-2xl border-b border-gray-100 bg-white px-6 py-4 dark:border-gray-800 dark:bg-gray-900"
            >
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    {editingId ? 'Edit Mobil' : 'Tambah Mobil Baru'}
                </h2>
                <button
                    onclick={closeModal}
                    class="cursor-pointer rounded-lg p-1 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800"
                >
                    <X class="h-5 w-5 text-gray-400" />
                </button>
            </div>

            <!-- Modal Body -->
            <div class="space-y-4 px-6 py-5">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Nama Mobil *</label
                    >
                    <input
                        type="text"
                        bind:value={form.name}
                        placeholder="contoh: Toyota Avanza"
                        class="w-full rounded-xl border border-gray-200 bg-transparent px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-700 dark:text-white"
                    />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Kategori *</label
                        >
                        <input
                            type="text"
                            bind:value={form.category}
                            placeholder="contoh: SUV"
                            class="w-full rounded-xl border border-gray-200 bg-transparent px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-700 dark:text-white"
                        />
                    </div>
                    <div>
                        <label
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Harga/Hari (Rp) *</label
                        >
                        <input
                            type="number"
                            bind:value={form.price_per_day}
                            placeholder="400000"
                            class="w-full rounded-xl border border-gray-200 bg-transparent px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-700 dark:text-white"
                        />
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Kursi</label
                        >
                        <input
                            type="number"
                            bind:value={form.seats}
                            class="w-full rounded-xl border border-gray-200 bg-transparent px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-700 dark:text-white"
                        />
                    </div>
                    <div>
                        <label
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Transmisi</label
                        >
                        <select
                            bind:value={form.transmission}
                            class="w-full rounded-xl border border-gray-200 bg-transparent px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-700 dark:text-white"
                        >
                            <option value="Otomatis">Otomatis</option>
                            <option value="Manual">Manual</option>
                            <option value="Manual/Oto">Manual/Oto</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >BBM</label
                        >
                        <select
                            bind:value={form.fuel}
                            class="w-full rounded-xl border border-gray-200 bg-transparent px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-700 dark:text-white"
                        >
                            <option value="Bensin">Bensin</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Hybrid">Hybrid</option>
                            <option value="Listrik">Listrik</option>
                        </select>
                    </div>
                </div>

                <!-- Availability -->
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input
                            type="checkbox"
                            bind:checked={form.is_available}
                            class="peer sr-only"
                        />
                        <div
                            class="peer h-5 w-10 rounded-full bg-gray-200 peer-checked:bg-blue-600 peer-focus:ring-2 peer-focus:ring-blue-300 peer-focus:outline-none after:absolute after:start-[2px] after:top-[2px] after:h-4 after:w-4 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-5 peer-checked:after:border-white dark:border-gray-600 dark:bg-gray-700 dark:peer-focus:ring-blue-800"
                        ></div>
                    </label>
                    <span class="text-sm text-gray-700 dark:text-gray-300"
                        >{form.is_available ? 'Tersedia' : 'Tidak Tersedia'}</span
                    >
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Foto Mobil</label
                    >
                    {#if imagePreview}
                        <div class="relative mb-2">
                            <img
                                src={imagePreview}
                                alt="Preview"
                                class="h-40 w-full rounded-xl bg-gray-50 object-contain dark:bg-gray-800"
                            />
                            <button
                                onclick={() => {
                                    form.image = null;
                                    imagePreview = null;
                                }}
                                class="absolute top-2 right-2 cursor-pointer rounded-full bg-red-500 p-1 text-white transition-colors hover:bg-red-600"
                            >
                                <X class="h-3.5 w-3.5" />
                            </button>
                        </div>
                    {/if}
                    <label
                        class="flex h-24 w-full cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 transition-colors hover:border-blue-500 dark:border-gray-600"
                    >
                        <Upload class="mb-1 h-5 w-5 text-gray-400" />
                        <span class="text-xs text-gray-500">Klik untuk upload gambar</span>
                        <input
                            type="file"
                            accept="image/*"
                            onchange={handleImageChange}
                            class="hidden"
                        />
                    </label>
                </div>
            </div>

            <!-- Modal Footer -->
            <div
                class="sticky bottom-0 flex justify-end gap-3 rounded-b-2xl border-t border-gray-100 bg-white px-6 py-4 dark:border-gray-800 dark:bg-gray-900"
            >
                <Button variant="outline" onclick={closeModal} class="rounded-xl">Batal</Button>
                <Button
                    onclick={submitForm}
                    disabled={!formValid || isSubmitting}
                    class="gap-1.5 rounded-xl bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50"
                >
                    {#if !editingId}<Plus class="h-4 w-4" />{/if}
                    {isSubmitting ? 'Menyimpan...' : editingId ? 'Perbarui Mobil' : 'Tambah Mobil'}
                </Button>
            </div>
        </div>
    </div>
{/if}

<!-- Header -->
<div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Mobil</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Daftar semua kendaraan yang tersedia untuk disewakan.
        </p>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex gap-2">
            <div
                class="rounded-lg bg-green-100 px-3 py-1.5 text-xs font-bold text-green-800 dark:bg-green-900/30 dark:text-green-300"
            >
                {availableCount} Tersedia
            </div>
            <div
                class="rounded-lg bg-red-100 px-3 py-1.5 text-xs font-bold text-red-800 dark:bg-red-900/30 dark:text-red-300"
            >
                {unavailableCount} Tidak Tersedia
            </div>
        </div>
        <Button
            onclick={openModal}
            class="h-9 gap-1.5 rounded-xl bg-blue-600 text-white hover:bg-blue-700"
        >
            <Plus class="h-4 w-4" /> Tambah Mobil
        </Button>
    </div>
</div>

<!-- Filters -->
<div
    class="mb-6 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
>
    <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
        <div class="flex gap-2">
            {#each [{ key: 'all', label: 'Semua' }, { key: 'available', label: 'Tersedia' }, { key: 'unavailable', label: 'Tidak Tersedia' }] as f}
                <button
                    onclick={() => (filterAvailability = f.key)}
                    class="cursor-pointer rounded-lg px-3.5 py-1.5 text-xs font-semibold transition-all {filterAvailability ===
                    f.key
                        ? 'bg-blue-100 text-blue-800 ring-2 ring-blue-500 ring-offset-1 dark:bg-blue-900/50 dark:text-blue-300'
                        : 'bg-gray-50 text-gray-500 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700'}"
                >
                    {f.label}
                </button>
            {/each}
        </div>
        <div class="relative w-full sm:w-64">
            <Search class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" />
            <input
                type="text"
                bind:value={searchQuery}
                placeholder="Cari mobil..."
                class="w-full rounded-lg border border-gray-200 bg-transparent py-2 pr-3 pl-9 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-700 dark:text-white"
            />
        </div>
    </div>
</div>

<!-- Car Grid -->
{#if filteredCars.length === 0}
    <div
        class="rounded-2xl bg-white p-16 text-center ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
    >
        <div
            class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800"
        >
            <Car class="h-8 w-8 text-gray-400" />
        </div>
        <h3 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">
            Tidak ada mobil ditemukan
        </h3>
        <p class="text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian.</p>
    </div>
{:else}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        {#each filteredCars as car}
            <div
                class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5 transition-shadow hover:shadow-md dark:bg-gray-900 dark:ring-white/10"
            >
                <!-- Image -->
                <div
                    class="relative flex h-44 items-center justify-center bg-gray-50 p-6 dark:bg-gray-800/50"
                >
                    {#if car.image}
                        <img
                            src={car.image}
                            alt={car.name}
                            class="max-h-full w-auto object-contain"
                        />
                    {:else}
                        <Car class="h-16 w-16 text-gray-300" />
                    {/if}
                    <div class="absolute top-3 right-3">
                        {#if car.is_available}
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-1 text-[10px] font-bold text-green-800 dark:bg-green-900/50 dark:text-green-300"
                            >
                                <CircleCheck class="h-3 w-3" /> Tersedia
                            </span>
                        {:else}
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-1 text-[10px] font-bold text-red-800 dark:bg-red-900/50 dark:text-red-300"
                            >
                                <CircleX class="h-3 w-3" /> Tidak Tersedia
                            </span>
                        {/if}
                    </div>
                    <div class="absolute top-3 left-3 flex gap-2">
                        <!-- Edit button -->
                        <button
                            onclick={() => openEditModal(car)}
                            class="cursor-pointer rounded-lg bg-blue-500 p-1.5 text-white opacity-0 transition-opacity group-hover:opacity-100 hover:bg-blue-600"
                        >
                            <Edit2 class="h-3.5 w-3.5" />
                        </button>
                        <!-- Delete button -->
                        <button
                            onclick={() => deleteCar(car.id)}
                            class="cursor-pointer rounded-lg bg-red-500 p-1.5 text-white opacity-0 transition-opacity group-hover:opacity-100 hover:bg-red-600"
                        >
                            <Trash2 class="h-3.5 w-3.5" />
                        </button>
                    </div>
                </div>

                <!-- Details -->
                <div class="p-5">
                    <div class="mb-3 flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">{car.name}</h3>
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                {car.category}
                            </p>
                        </div>
                        <span
                            class="text-sm font-bold whitespace-nowrap text-blue-600 dark:text-blue-400"
                            >{formatCurrency(car.price_per_day)}</span
                        >
                    </div>

                    <div
                        class="flex items-center gap-4 border-t border-gray-100 pt-3 text-xs text-gray-500 dark:border-gray-800 dark:text-gray-400"
                    >
                        <span class="flex items-center gap-1">
                            <UsersIcon class="h-3.5 w-3.5" />
                            {car.seats} Kursi
                        </span>
                        <span class="flex items-center gap-1">
                            <Settings2 class="h-3.5 w-3.5" />
                            {car.transmission}
                        </span>
                        <span class="flex items-center gap-1">
                            <Fuel class="h-3.5 w-3.5" />
                            {car.fuel}
                        </span>
                    </div>
                </div>
            </div>
        {/each}
    </div>
{/if}

<div class="mt-6 text-center text-xs text-gray-400">
    Menampilkan {filteredCars.length} dari {cars.length} mobil
</div>

<AlertDialog.Root bind:open={alertDialogState.open}>
    <AlertDialog.Content>
        <AlertDialog.Header>
            <AlertDialog.Title>Hapus Mobil?</AlertDialog.Title>
            <AlertDialog.Description>
                Tindakan ini tidak dapat dibatalkan. Mobil akan dihapus permanen dari sistem.
            </AlertDialog.Description>
        </AlertDialog.Header>
        <AlertDialog.Footer>
            <AlertDialog.Cancel onclick={() => (alertDialogState.open = false)}
                >Batal</AlertDialog.Cancel
            >
            <AlertDialog.Action
                onclick={confirmDeleteCar}
                class="bg-red-600 focus:ring-red-600 sm:hover:bg-red-700">Hapus</AlertDialog.Action
            >
        </AlertDialog.Footer>
    </AlertDialog.Content>
</AlertDialog.Root>
