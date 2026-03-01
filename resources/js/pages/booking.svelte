<script>
    import AppLayout from '@/layouts/app-layout.svelte';
    import Head from '@/components/head.svelte';
    import { Button } from "@/components/ui/button/index.js";
    import { Input } from "@/components/ui/input/index.js";
    import { Label } from "@/components/ui/label/index.js";
    import { Calendar, User, Phone, MapPin, Car, Info, Upload, CreditCard, ShieldCheck, X } from 'lucide-svelte';
    import { Separator } from "@/components/ui/separator/index.js";
    import { router } from '@inertiajs/svelte';

    let { car } = $props();

    // Form State
    let name = $state("");
    let whatsapp = $state("");
    let address = $state("");
    let startDate = $state("");
    let endDate = $state("");
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
        return name.trim() !== "" 
            && whatsapp.trim() !== "" 
            && address.trim() !== "" 
            && startDate !== "" 
            && endDate !== "" 
            && duration > 0
            && proofFile !== null;
    });

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-8">
                <ol class="flex space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="/" class="hover:text-gray-900 dark:hover:text-white">Beranda</a></li>
                    <li class="flex items-center">
                        <span class="mx-2">/</span>
                        <a href="/list-car" class="hover:text-gray-900 dark:hover:text-white">Daftar Mobil</a>
                    </li>
                    <li class="flex items-center">
                        <span class="mx-2">/</span>
                        <span class="text-gray-900 dark:text-white font-medium">Booking {car.name}</span>
                    </li>
                </ol>
            </nav>
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Booking Kendaraan</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Lengkapi formulir di bawah ini untuk melakukan penyewaan.</p>
            </div>

            <form onsubmit={handleSubmit} class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Grid: Form -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-8 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <User class="w-5 h-5 text-blue-600" /> Informasi Penyewa
                        </h2>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="name">Nama Lengkap</Label>
                                    <Input id="name" placeholder="Masukkan nama lengkap" bind:value={name} required />
                                </div>
                                <div class="space-y-2">
                                    <Label for="whatsapp">No. WhatsApp</Label>
                                    <Input id="whatsapp" placeholder="Contoh: 08123456789" bind:value={whatsapp} required />
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <Label for="address">Alamat Lengkap</Label>
                                <textarea 
                                    id="address" 
                                    bind:value={address}
                                    class="flex min-h-[100px] w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500 disabled:cursor-not-allowed disabled:opacity-50 dark:text-white"
                                    placeholder="Masukkan alamat lengkap Anda"
                                    required
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-8 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <Calendar class="w-5 h-5 text-blue-600" /> Jadwal Sewa
                        </h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-8 shadow-sm ring-1 ring-gray-900/5 dark:ring-white/10">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <CreditCard class="w-5 h-5 text-blue-600" /> Pembayaran
                        </h2>
                        
                        <div class="mb-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-900/30">
                            <div class="flex items-center gap-3 mb-4">
                                <img src="/assets/images/bca-logo.png" alt="BCA" class="w-auto h-10 rounded" />
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold tracking-tight">Nomor Rekening:</p>
                                <p class="text-2xl font-mono font-bold text-gray-900 dark:text-white tracking-wider leading-none">1234 5678 90</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">A/N <span class="font-bold">DSR Rental Mobil Indonesia</span></p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="proof">Upload Bukti Pembayaran</Label>
                            <div class="relative group">
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
                                    <div class="relative w-full rounded-2xl overflow-hidden border-2 border-green-300 dark:border-green-700 bg-gray-50 dark:bg-gray-800/30">
                                        <img 
                                            src={previewUrl} 
                                            alt="Preview bukti pembayaran" 
                                            class="w-full max-h-64 object-contain"
                                        />
                                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                                            <div class="flex items-center justify-between">
                                                <span class="text-white text-sm font-medium truncate mr-4">{proofFile?.name}</span>
                                                <button 
                                                    type="button"
                                                    onclick={removeFile}
                                                    class="flex items-center gap-1.5 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm"
                                                >
                                                    <X class="w-3 h-3" /> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                {:else}
                                    <!-- Upload Area -->
                                    <label 
                                        for="proof" 
                                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-2xl cursor-pointer bg-gray-50/50 dark:bg-gray-800/30 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all border-spacing-4 group-hover:border-blue-500/50"
                                    >
                                        <div class="flex flex-col items-center gap-2 text-gray-400">
                                            <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm border border-gray-100 dark:border-gray-700">
                                                <Upload class="w-6 h-6" />
                                            </div>
                                            <p class="text-sm">Klik untuk upload bukti transfer</p>
                                            <p class="text-[10px] opacity-70">JPG, PNG (Max. 2MB)</p>
                                        </div>
                                    </label>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Grid: Summary Card -->
                <div class="lg:col-span-5 lg:sticky lg:top-24">
                    <div class="bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                        <div class="p-6 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Ringkasan Penyewaan</h3>
                        </div>
                        
                        <div class="p-6 sm:p-8 space-y-6">
                            <!-- Car Info -->
                            <div class="flex gap-4 items-center">
                                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-2xl p-2 flex items-center justify-center shrink-0">
                                    <img src={car.image} alt={car.name} class="w-full h-full object-contain" />
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">{car.name}</h4>
                                    <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                                        <span class="flex items-center gap-1"><Car class="w-4 h-4" /> {car.category}</span>
                                        <span class="flex items-center gap-1"><Info class="w-4 h-4" /> {car.transmission}</span>
                                    </div>
                                </div>
                            </div>

                            <Separator />

                            <!-- Rental Details -->
                            <div class="space-y-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Harga Sewa / Hari</span>
                                    <span class="font-bold text-gray-900 dark:text-white">{formatCurrency(car.price_per_day)}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Durasi Sewa</span>
                                    <span class="font-bold text-gray-900 dark:text-white">{duration} Hari</span>
                                </div>
                                
                                {#if startDate && endDate}
                                    <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-xl text-xs space-y-2 border border-blue-50 dark:border-blue-900/20">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-400 italic">Mulai:</span>
                                            <span class="text-gray-900 dark:text-white font-semibold">{startDate}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-400 italic">Selesai:</span>
                                            <span class="text-gray-900 dark:text-white font-semibold">{endDate}</span>
                                        </div>
                                    </div>
                                {/if}
                            </div>

                            <Separator />

                            <!-- Total -->
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Total Pembayaran</p>
                                    <p class="text-3xl font-extrabold text-blue-600 dark:text-blue-400">{formatCurrency(totalPrice)}</p>
                                </div>
                            </div>

                            <Button 
                                type="submit" 
                                disabled={!isFormValid || isSubmitting}
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white dark:bg-blue-500 dark:hover:bg-blue-600 rounded-2xl h-14 text-lg font-bold shadow-lg shadow-blue-500/20 transition-all hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                            >
                                {#if isSubmitting}
                                    <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin mr-2"></div>
                                    Memproses...
                                {:else}
                                    Sewa Sekarang
                                {/if}
                            </Button>

                            <div class="flex items-center justify-center gap-2 text-[10px] text-gray-400">
                                <ShieldCheck class="w-3 h-3" />
                                <span>Penyewaan aman dan terpercaya</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</AppLayout>
