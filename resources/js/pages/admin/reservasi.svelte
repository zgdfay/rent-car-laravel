<script module>
    export { default as layout } from '@/layouts/authenticated-layout.svelte';
</script>

<script>
    import { router, page } from '@inertiajs/svelte';
    import { Button } from '@/components/ui/button/index.js';
    import { Input } from '@/components/ui/input/index.js';
    import { Separator } from '@/components/ui/separator/index.js';
    import * as AlertDialog from '@/components/ui/alert-dialog/index.js';
    import {
        Car,
        Clock,
        CheckCircle2,
        XCircle,
        CheckSquare,
        Eye,
        Search,
        Filter,
        CalendarDays,
        User,
        Phone,
        MapPin,
        ChevronDown,
        ChevronUp,
        X,
        Trash2,
    } from 'lucide-svelte';

    let { bookings = [], filters = {} } = $props();

    let activeFilter = $state(filters.status || 'all');
    let searchQuery = $state(filters.search || '');
    let expandedId = $state(null);
    let alertDialogState = $state({ open: false, bookingId: null });
    let returnDialogState = $state({ open: false, booking: null });
    let returnForm = $state({
        return_date: new Date().toISOString().split('T')[0],
        late_fee: 0,
        damage_fee: 0,
    });

    // Auto-calculate late fee when return date changes
    $effect(() => {
        if (returnDialogState.booking && returnForm.return_date) {
            const endDate = new Date(returnDialogState.booking.end_date);
            const returnDate = new Date(returnForm.return_date);
            endDate.setHours(0, 0, 0, 0);
            returnDate.setHours(0, 0, 0, 0);

            const diffTime = returnDate.getTime() - endDate.getTime();
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays > 0) {
                // E.g., penalty is basic daily price per day late
                returnForm.late_fee = diffDays * returnDialogState.booking.car_price_per_day;
            } else {
                returnForm.late_fee = 0;
            }
        }
    });

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        });
    }

    function formatDateTime(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    }

    function getStatusLabel(status) {
        switch (status) {
            case 'pending':
                return 'Menunggu';
            case 'approved':
                return 'Disetujui';
            case 'rejected':
                return 'Ditolak';
            default:
                return status;
        }
    }

    function getStatusColor(status) {
        switch (status) {
            case 'pending':
                return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300';
            case 'approved':
                return 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300';
            case 'completed':
                return 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300';
            case 'rejected':
                return 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    function getStatusIcon(status) {
        switch (status) {
            case 'pending':
                return Clock;
            case 'approved':
                return CheckCircle2;
            case 'completed':
                return CheckSquare;
            case 'rejected':
                return XCircle;
            default:
                return Clock;
        }
    }

    function updateStatus(bookingId, status) {
        router.patch(
            `/admin/bookings/${bookingId}/status`,
            { status },
            {
                preserveScroll: true,
            },
        );
    }

    function openReturnDialog(booking) {
        returnDialogState = { open: true, booking };
        returnForm = {
            return_date: new Date().toISOString().split('T')[0],
            late_fee: 0,
            damage_fee: 0,
        };
    }

    let isSubmittingReturn = $state(false);

    function submitReturn() {
        if (!returnDialogState.booking) return;
        isSubmittingReturn = true;

        router.post(`/admin/bookings/${returnDialogState.booking.id}/return`, returnForm, {
            preserveScroll: true,
            onSuccess: () => {
                returnDialogState = { open: false, booking: null };
                isSubmittingReturn = false;
            },
            onError: () => {
                isSubmittingReturn = false;
            },
        });
    }

    function deleteBooking(id) {
        alertDialogState = { open: true, bookingId: id };
    }

    function confirmDeleteBooking() {
        if (alertDialogState.bookingId) {
            router.delete(`/admin/bookings/${alertDialogState.bookingId}`, {
                preserveScroll: true,
                onSuccess: () => {
                    alertDialogState = { open: false, bookingId: null };
                },
            });
        }
    }

    function applyFilter(status) {
        activeFilter = status;
        router.get(
            '/admin/reservasi',
            {
                status: status === 'all' ? undefined : status,
                search: searchQuery || undefined,
            },
            { preserveState: true, preserveScroll: true },
        );
    }

    function handleSearch() {
        router.get(
            '/admin/reservasi',
            {
                status: activeFilter === 'all' ? undefined : activeFilter,
                search: searchQuery || undefined,
            },
            { preserveState: true, preserveScroll: true },
        );
    }

    function toggleExpand(id) {
        expandedId = expandedId === id ? null : id;
    }

    const statusFilters = [
        {
            key: 'all',
            label: 'Semua',
            color: 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
        },
        {
            key: 'pending',
            label: 'Menunggu',
            color: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
        },
        {
            key: 'approved',
            label: 'Disetujui',
            color: 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
        },
        {
            key: 'rejected',
            label: 'Ditolak',
            color: 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
        },
        {
            key: 'completed',
            label: 'Selesai',
            color: 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
        },
    ];

    let pendingCount = $derived(bookings.filter((b) => b.status === 'pending').length);
</script>

<!-- Header -->
<div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Reservasi</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Kelola semua reservasi penyewaan kendaraan.
        </p>
    </div>
    {#if pendingCount > 0}
        <div
            class="rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-2 text-sm font-medium text-yellow-800 dark:border-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300"
        >
            <Clock class="mr-1 inline h-4 w-4" />
            {pendingCount} reservasi menunggu konfirmasi
        </div>
    {/if}
</div>

<!-- Filters & Search -->
<div
    class="mb-6 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
>
    <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
        <!-- Status Filters -->
        <div class="flex flex-wrap gap-2">
            {#each statusFilters as filter}
                <button
                    onclick={() => applyFilter(filter.key)}
                    class="cursor-pointer rounded-lg px-3.5 py-1.5 text-xs font-semibold transition-all {activeFilter ===
                    filter.key
                        ? filter.color + ' ring-2 ring-blue-500 ring-offset-1'
                        : 'bg-gray-50 text-gray-500 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700'}"
                >
                    {filter.label}
                </button>
            {/each}
        </div>

        <!-- Search -->
        <form
            onsubmit={(e) => {
                e.preventDefault();
                handleSearch();
            }}
            class="flex w-full gap-2 sm:w-auto"
        >
            <div class="relative flex-1 sm:w-64">
                <Search class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" />
                <input
                    type="text"
                    bind:value={searchQuery}
                    placeholder="Cari nama / mobil..."
                    class="w-full rounded-lg border border-gray-200 bg-transparent py-2 pr-3 pl-9 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-700 dark:text-white"
                />
            </div>
            <Button
                type="submit"
                size="sm"
                class="h-9 rounded-lg bg-blue-600 px-4 text-white hover:bg-blue-700"
            >
                Cari
            </Button>
        </form>
    </div>
</div>

<!-- Booking List -->
<div
    class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
>
    {#if bookings.length === 0}
        <div class="p-16 text-center">
            <div
                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800"
            >
                <CalendarDays class="h-8 w-8 text-gray-400" />
            </div>
            <h3 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">
                Tidak ada reservasi
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Belum ada reservasi {activeFilter !== 'all'
                    ? `dengan status "${getStatusLabel(activeFilter)}"`
                    : ''}
            </p>
        </div>
    {:else}
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr
                        class="border-b border-gray-100 bg-gray-50/50 text-left dark:border-gray-800 dark:bg-gray-800/30"
                    >
                        <th
                            class="px-5 py-3.5 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >ID</th
                        >
                        <th
                            class="px-5 py-3.5 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Penyewa</th
                        >
                        <th
                            class="px-5 py-3.5 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Mobil</th
                        >
                        <th
                            class="px-5 py-3.5 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Jadwal</th
                        >
                        <th
                            class="px-5 py-3.5 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Total</th
                        >
                        <th
                            class="px-5 py-3.5 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Bukti</th
                        >
                        <th
                            class="px-5 py-3.5 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Status</th
                        >
                        <th
                            class="px-5 py-3.5 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Aksi</th
                        >
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    {#each bookings as booking}
                        {@const StatusIcon = getStatusIcon(booking.status)}
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-5 py-4">
                                <span
                                    class="font-mono text-xs font-bold text-gray-900 dark:text-white"
                                    >ORD-{String(booking.id).padStart(5, '0')}</span
                                >
                                <p class="mt-0.5 text-[10px] text-gray-400">
                                    {formatDateTime(booking.created_at)}
                                </p>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400"
                                    >
                                        {booking.name?.charAt(0)?.toUpperCase() || 'U'}
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-900 dark:text-white"
                                        >
                                            {booking.name}
                                        </p>
                                        <p class="flex items-center gap-1 text-xs text-gray-400">
                                            <Phone class="h-3 w-3" />
                                            {booking.whatsapp}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-14 shrink-0 items-center justify-center rounded-lg bg-gray-100 p-1 dark:bg-gray-800"
                                    >
                                        <img
                                            src={booking.car_image}
                                            alt={booking.car_name}
                                            class="h-full w-full object-contain"
                                        />
                                    </div>
                                    <span class="text-xs font-medium text-gray-900 dark:text-white"
                                        >{booking.car_name}</span
                                    >
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs font-medium text-gray-900 dark:text-white">
                                    {formatDate(booking.start_date)}
                                </p>
                                <p class="text-xs text-gray-400">
                                    s/d {formatDate(booking.end_date)}
                                </p>
                                <p
                                    class="mt-0.5 text-[10px] font-semibold text-blue-600 dark:text-blue-400"
                                >
                                    {booking.duration} hari
                                </p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-sm font-bold text-gray-900 dark:text-white"
                                    >{formatCurrency(booking.total_price)}</span
                                >
                                <p class="text-[10px] text-gray-400">
                                    {formatCurrency(booking.car_price_per_day)}/hari
                                </p>
                            </td>
                            <td class="px-5 py-4">
                                {#if booking.payment_proof}
                                    <a
                                        href="/storage/{booking.payment_proof}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-medium text-blue-600 transition-colors hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20"
                                    >
                                        <Eye class="h-3.5 w-3.5" />
                                        Lihat
                                    </a>
                                {:else}
                                    <span class="text-xs text-gray-400">—</span>
                                {/if}
                            </td>
                            <td class="px-5 py-4">
                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-bold {getStatusColor(
                                        booking.status,
                                    )}"
                                >
                                    <StatusIcon class="h-3 w-3" />
                                    {getStatusLabel(booking.status)}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {#if booking.status === 'pending'}
                                        <Button
                                            size="sm"
                                            onclick={() => updateStatus(booking.id, 'approved')}
                                            class="h-8 gap-1 rounded-lg bg-green-600 px-3 text-xs text-white hover:bg-green-700"
                                        >
                                            <CheckCircle2 class="h-3.5 w-3.5" /> Setujui
                                        </Button>
                                        <Button
                                            size="sm"
                                            variant="outline"
                                            onclick={() => updateStatus(booking.id, 'rejected')}
                                            class="h-8 gap-1 rounded-lg border-red-200 px-3 text-xs text-red-600 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20"
                                        >
                                            <XCircle class="h-3.5 w-3.5" /> Tolak
                                        </Button>
                                    {:else if booking.status === 'approved'}
                                        <Button
                                            size="sm"
                                            onclick={() => openReturnDialog(booking)}
                                            class="h-8 gap-1 rounded-lg bg-blue-600 px-3 text-xs text-white hover:bg-blue-700"
                                        >
                                            <CheckSquare class="h-3.5 w-3.5" /> Proses Pengembalian
                                        </Button>
                                    {:else}
                                        <span
                                            class="mr-2 text-xs font-medium text-red-500 dark:text-red-400"
                                            >✗ Ditolak</span
                                        >
                                    {/if}

                                    <Button
                                        size="sm"
                                        variant="ghost"
                                        onclick={() => deleteBooking(booking.id)}
                                        class="h-8 w-8 rounded-lg p-0 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20"
                                        title="Hapus Reservasi"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </Button>

                                    <Button
                                        size="sm"
                                        variant="ghost"
                                        onclick={() => toggleExpand(booking.id)}
                                        class="h-8 w-8 rounded-lg p-0 text-gray-400 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20"
                                    >
                                        {#if expandedId === booking.id}
                                            <ChevronUp class="h-4 w-4" />
                                        {:else}
                                            <ChevronDown class="h-4 w-4" />
                                        {/if}
                                    </Button>
                                </div>
                            </td>
                        </tr>

                        <!-- Expandable Detail Row -->
                        {#if expandedId === booking.id}
                            <tr class="bg-blue-50/50 dark:bg-blue-900/10">
                                <td colspan="8" class="px-5 py-4">
                                    <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-3">
                                        <div class="flex items-start gap-2">
                                            <MapPin class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" />
                                            <div>
                                                <p
                                                    class="mb-1 text-xs font-bold text-gray-400 uppercase"
                                                >
                                                    Alamat
                                                </p>
                                                <p class="text-gray-900 dark:text-white">
                                                    {booking.address}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <User class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" />
                                            <div>
                                                <p
                                                    class="mb-1 text-xs font-bold text-gray-400 uppercase"
                                                >
                                                    Email User
                                                </p>
                                                <p class="text-gray-900 dark:text-white">
                                                    {booking.user?.email || '-'}
                                                </p>
                                            </div>
                                        </div>
                                        {#if booking.payment_proof}
                                            <div>
                                                <p
                                                    class="mb-2 text-xs font-bold text-gray-400 uppercase"
                                                >
                                                    Bukti Pembayaran
                                                </p>
                                                <a
                                                    href="/storage/{booking.payment_proof}"
                                                    target="_blank"
                                                >
                                                    <img
                                                        src="/storage/{booking.payment_proof}"
                                                        alt="Bukti"
                                                        class="h-auto w-32 rounded-lg border border-gray-200 transition-opacity hover:opacity-80 dark:border-gray-700"
                                                    />
                                                </a>
                                            </div>
                                        {/if}
                                    </div>
                                </td>
                            </tr>
                        {/if}
                    {/each}
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div
            class="border-t border-gray-100 bg-gray-50/50 px-5 py-3 text-xs text-gray-500 dark:border-gray-800 dark:bg-gray-800/20"
        >
            Menampilkan {bookings.length} reservasi
        </div>
    {/if}
</div>

<AlertDialog.Root bind:open={alertDialogState.open}>
    <AlertDialog.Content>
        <AlertDialog.Header>
            <AlertDialog.Title>Apakah Anda yakin?</AlertDialog.Title>
            <AlertDialog.Description>
                Tindakan ini tidak dapat dibatalkan. Ini akan menghapus data reservasi secara
                permanen beserta bukti pembayarannya.
            </AlertDialog.Description>
        </AlertDialog.Header>
        <AlertDialog.Footer>
            <AlertDialog.Cancel onclick={() => (alertDialogState.open = false)}
                >Batal</AlertDialog.Cancel
            >
            <AlertDialog.Action
                onclick={confirmDeleteBooking}
                class="bg-red-600 focus:ring-red-600 sm:hover:bg-red-700">Hapus</AlertDialog.Action
            >
        </AlertDialog.Footer>
    </AlertDialog.Content>
</AlertDialog.Root>

<!-- Return Dialog -->
<AlertDialog.Root bind:open={returnDialogState.open}>
    <AlertDialog.Content class="sm:max-w-[425px]">
        <AlertDialog.Header>
            <AlertDialog.Title>Proses Pengembalian</AlertDialog.Title>
            <AlertDialog.Description>
                Masukkan detail pengembalian kendaraan. Tindakan ini akan menyelesaikan reservasi
                dan membuat mobil kembali tersedia.
            </AlertDialog.Description>
        </AlertDialog.Header>

        {#if returnDialogState.booking}
            <div class="grid gap-4 py-4">
                <div class="grid grid-cols-4 items-center gap-4">
                    <label for="return_date" class="text-right text-sm font-medium">
                        Tanggal Kembali
                    </label>
                    <Input
                        id="return_date"
                        type="date"
                        bind:value={returnForm.return_date}
                        class="col-span-3"
                    />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <label for="late_fee" class="text-right text-sm font-medium">
                        Denda (Rp)
                    </label>
                    <Input
                        id="late_fee"
                        type="number"
                        min="0"
                        bind:value={returnForm.late_fee}
                        class="col-span-3"
                    />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <label for="damage_fee" class="text-right text-sm font-medium">
                        Biaya Kerusakan (Rp)
                    </label>
                    <Input
                        id="damage_fee"
                        type="number"
                        min="0"
                        bind:value={returnForm.damage_fee}
                        class="col-span-3"
                    />
                </div>
                <div class="mt-4 rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <div class="mb-1 flex justify-between text-sm">
                        <span class="text-gray-500">Total Harga Sewa:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100"
                            >{formatCurrency(returnDialogState.booking.total_price)}</span
                        >
                    </div>
                    <div class="mb-1 flex justify-between text-sm">
                        <span class="text-gray-500">Total Biaya Tambahan:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100"
                            >{formatCurrency(returnForm.late_fee + returnForm.damage_fee)}</span
                        >
                    </div>
                    <Separator class="my-2" />
                    <div class="flex justify-between font-bold">
                        <span class="text-gray-900 dark:text-gray-100">Tagihan Akhir:</span>
                        <span class="text-blue-600 dark:text-blue-400"
                            >{formatCurrency(
                                returnDialogState.booking.total_price +
                                    returnForm.late_fee +
                                    returnForm.damage_fee,
                            )}</span
                        >
                    </div>
                </div>
            </div>
        {/if}

        <AlertDialog.Footer>
            <AlertDialog.Cancel
                onclick={() => (returnDialogState.open = false)}
                disabled={isSubmittingReturn}>Batal</AlertDialog.Cancel
            >
            <Button
                onclick={submitReturn}
                disabled={isSubmittingReturn}
                class="bg-blue-600 text-white hover:bg-blue-700"
            >
                {isSubmittingReturn ? 'Memproses...' : 'Selesaikan Reservasi'}
            </Button>
        </AlertDialog.Footer>
    </AlertDialog.Content>
</AlertDialog.Root>
