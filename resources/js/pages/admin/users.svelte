<script module>
    export { default as layout } from '@/layouts/authenticated-layout.svelte';
</script>

<script>
    import { router } from '@inertiajs/svelte';
    import { Button } from '@/components/ui/button/index.js';
    import * as AlertDialog from '@/components/ui/alert-dialog/index.js';
    import {
        Users,
        Shield,
        ShieldCheck,
        User,
        Mail,
        Calendar,
        Search,
        Plus,
        X,
        Key,
        Trash2,
        Edit2,
    } from 'lucide-svelte';

    let { users = [] } = $props();

    let searchQuery = $state('');
    let filterRole = $state('all');
    let showAddModal = $state(false);
    let alertDialogState = $state({ open: false, userId: null });
    let editingId = $state(null);

    // Form state
    let form = $state({
        name: '',
        email: '',
        password: '',
        role: 'customer',
    });

    let isSubmitting = $state(false);
    let formErrors = $state({});

    let filteredUsers = $derived.by(() => {
        let result = users;
        if (filterRole === 'admin') result = result.filter((u) => u.role === 'admin');
        else if (filterRole === 'customer') result = result.filter((u) => u.role === 'customer');
        if (searchQuery.trim()) {
            const q = searchQuery.toLowerCase();
            result = result.filter(
                (u) => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q),
            );
        }
        return result;
    });

    const adminCount = $derived(users.filter((u) => u.role === 'admin').length);
    const customerCount = $derived(users.filter((u) => u.role === 'customer').length);

    let formValid = $derived(
        form.name.trim() && form.email.trim() && (editingId ? true : form.password.length >= 6),
    );

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        });
    }

    function updateRole(userId, newRole) {
        router.patch(
            `/admin/users/${userId}/role`,
            { role: newRole },
            {
                preserveScroll: true,
            },
        );
    }

    function resetForm() {
        form = { name: '', email: '', password: '', role: 'customer' };
        formErrors = {};
        editingId = null;
    }

    function openModal() {
        resetForm();
        showAddModal = true;
    }

    function openEditModal(user) {
        editingId = user.id;
        form = { name: user.name, email: user.email, password: '', role: user.role };
        formErrors = {};
        showAddModal = true;
    }

    function closeModal() {
        showAddModal = false;
        resetForm();
    }

    function submitForm() {
        if (!formValid || isSubmitting) return;
        isSubmitting = true;
        formErrors = {};

        const url = editingId ? `/admin/users/${editingId}` : '/admin/users';
        const method = editingId ? 'put' : 'post';

        router[method](url, form, {
            onSuccess: () => {
                closeModal();
                isSubmitting = false;
            },
            onError: (errors) => {
                formErrors = errors;
                isSubmitting = false;
            },
        });
    }

    function deleteUser(id) {
        alertDialogState = { open: true, userId: id };
    }

    function confirmDeleteUser() {
        if (alertDialogState.userId) {
            router.delete(`/admin/users/${alertDialogState.userId}`, {
                preserveScroll: true,
                onSuccess: () => {
                    alertDialogState = { open: false, userId: null };
                },
            });
        }
    }
</script>

<!-- Add Modal -->
{#if showAddModal}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick={closeModal}></div>
        <div
            class="relative z-10 w-full max-w-md rounded-2xl bg-white shadow-2xl ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
        >
            <!-- Modal Header -->
            <div
                class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800"
            >
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    {editingId ? 'Edit User' : 'Tambah User Baru'}
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
                        >Nama Lengkap *</label
                    >
                    <input
                        type="text"
                        bind:value={form.name}
                        placeholder="Nama lengkap"
                        class="w-full rounded-xl border bg-transparent px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:text-white {formErrors.name
                            ? 'border-red-500'
                            : 'border-gray-200 dark:border-gray-700'}"
                    />
                    {#if formErrors.name}<p class="mt-1 text-xs text-red-500">
                            {formErrors.name}
                        </p>{/if}
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Email *</label
                    >
                    <input
                        type="email"
                        bind:value={form.email}
                        placeholder="email@contoh.com"
                        class="w-full rounded-xl border bg-transparent px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:text-white {formErrors.email
                            ? 'border-red-500'
                            : 'border-gray-200 dark:border-gray-700'}"
                    />
                    {#if formErrors.email}<p class="mt-1 text-xs text-red-500">
                            {formErrors.email}
                        </p>{/if}
                </div>
                <div>
                    <label
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Password {#if !editingId}*{/if}
                        <span class="font-normal text-gray-400"
                            >{editingId
                                ? '(kosongkan jika tidak ingin diubah)'
                                : '(min 6 karakter)'}</span
                        >
                    </label>
                    <input
                        type="password"
                        bind:value={form.password}
                        placeholder="••••••"
                        class="w-full rounded-xl border bg-transparent px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:text-white {formErrors.password
                            ? 'border-red-500'
                            : 'border-gray-200 dark:border-gray-700'}"
                    />
                    {#if formErrors.password}<p class="mt-1 text-xs text-red-500">
                            {formErrors.password}
                        </p>{/if}
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Role</label
                    >
                    <div class="grid grid-cols-2 gap-3">
                        <button
                            type="button"
                            onclick={() => (form.role = 'customer')}
                            class="flex cursor-pointer items-center gap-2 rounded-xl border-2 px-4 py-3 transition-all {form.role ===
                            'customer'
                                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'
                                : 'border-gray-200 hover:border-gray-300 dark:border-gray-700'}"
                        >
                            <User
                                class="h-4 w-4 {form.role === 'customer'
                                    ? 'text-blue-600'
                                    : 'text-gray-400'}"
                            />
                            <span
                                class="text-sm font-medium {form.role === 'customer'
                                    ? 'text-blue-700 dark:text-blue-300'
                                    : 'text-gray-600 dark:text-gray-400'}">Customer</span
                            >
                        </button>
                        <button
                            type="button"
                            onclick={() => (form.role = 'admin')}
                            class="flex cursor-pointer items-center gap-2 rounded-xl border-2 px-4 py-3 transition-all {form.role ===
                            'admin'
                                ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20'
                                : 'border-gray-200 hover:border-gray-300 dark:border-gray-700'}"
                        >
                            <ShieldCheck
                                class="h-4 w-4 {form.role === 'admin'
                                    ? 'text-purple-600'
                                    : 'text-gray-400'}"
                            />
                            <span
                                class="text-sm font-medium {form.role === 'admin'
                                    ? 'text-purple-700 dark:text-purple-300'
                                    : 'text-gray-600 dark:text-gray-400'}">Admin</span
                            >
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div
                class="flex justify-end gap-3 border-t border-gray-100 px-6 py-4 dark:border-gray-800"
            >
                <Button variant="outline" onclick={closeModal} class="rounded-xl">Batal</Button>
                <Button
                    onclick={submitForm}
                    disabled={!formValid || isSubmitting}
                    class="gap-1.5 rounded-xl bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50"
                >
                    {#if !editingId}<Plus class="h-4 w-4" />{/if}
                    {isSubmitting ? 'Menyimpan...' : editingId ? 'Perbarui User' : 'Tambah User'}
                </Button>
            </div>
        </div>
    </div>
{/if}

<!-- Header -->
<div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen User</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Kelola semua pengguna terdaftar.
        </p>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex gap-2">
            <div
                class="rounded-lg bg-blue-100 px-3 py-1.5 text-xs font-bold text-blue-800 dark:bg-blue-900/30 dark:text-blue-300"
            >
                {users.length} Total
            </div>
            <div
                class="rounded-lg bg-purple-100 px-3 py-1.5 text-xs font-bold text-purple-800 dark:bg-purple-900/30 dark:text-purple-300"
            >
                {adminCount} Admin
            </div>
            <div
                class="rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-bold text-gray-700 dark:bg-gray-800 dark:text-gray-300"
            >
                {customerCount} Customer
            </div>
        </div>
        <Button
            onclick={openModal}
            class="h-9 gap-1.5 rounded-xl bg-blue-600 text-white hover:bg-blue-700"
        >
            <Plus class="h-4 w-4" /> Tambah User
        </Button>
    </div>
</div>

<!-- Filters -->
<div
    class="mb-6 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
>
    <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
        <div class="flex gap-2">
            {#each [{ key: 'all', label: 'Semua' }, { key: 'admin', label: 'Admin' }, { key: 'customer', label: 'Customer' }] as f}
                <button
                    onclick={() => (filterRole = f.key)}
                    class="cursor-pointer rounded-lg px-3.5 py-1.5 text-xs font-semibold transition-all {filterRole ===
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
                placeholder="Cari nama / email..."
                class="w-full rounded-lg border border-gray-200 bg-transparent py-2 pr-3 pl-9 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-700 dark:text-white"
            />
        </div>
    </div>
</div>

<!-- User Table -->
<div
    class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
>
    {#if filteredUsers.length === 0}
        <div class="p-16 text-center">
            <div
                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800"
            >
                <Users class="h-8 w-8 text-gray-400" />
            </div>
            <h3 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">
                Tidak ada user ditemukan
            </h3>
            <p class="text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian.</p>
        </div>
    {:else}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr
                        class="border-b border-gray-100 bg-gray-50/50 text-left dark:border-gray-800 dark:bg-gray-800/30"
                    >
                        <th
                            class="px-5 py-3.5 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >User</th
                        >
                        <th
                            class="px-5 py-3.5 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Email</th
                        >
                        <th
                            class="px-5 py-3.5 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Role</th
                        >
                        <th
                            class="px-5 py-3.5 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Bergabung</th
                        >
                        <th
                            class="px-5 py-3.5 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Booking</th
                        >
                        <th
                            class="px-5 py-3.5 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Aksi</th
                        >
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    {#each filteredUsers as user}
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-bold {user.role ===
                                        'admin'
                                            ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300'
                                            : 'bg-blue-100  text-blue-700 dark:bg-blue-900/30 dark:text-blue-300'}"
                                    >
                                        {user.name?.charAt(0)?.toUpperCase() || 'U'}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {user.name}
                                        </p>
                                        <p class="text-[10px] text-gray-400">ID: {user.id}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-gray-600 dark:text-gray-300">{user.email}</span>
                            </td>
                            <td class="px-5 py-4">
                                {#if user.role === 'admin'}
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-purple-100 px-2.5 py-1 text-[10px] font-bold text-purple-800 dark:bg-purple-900/50 dark:text-purple-300"
                                    >
                                        <ShieldCheck class="h-3 w-3" /> Admin
                                    </span>
                                {:else}
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-1 text-[10px] font-bold text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                                    >
                                        <User class="h-3 w-3" /> Customer
                                    </span>
                                {/if}
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-xs text-gray-500"
                                    >{formatDate(user.created_at)}</span
                                >
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-sm font-bold text-gray-900 dark:text-white"
                                    >{user.bookings_count ?? 0}</span
                                >
                            </td>
                            <td class="px-5 py-4 text-right">
                                {#if user.role === 'customer'}
                                    <Button
                                        size="sm"
                                        onclick={() => updateRole(user.id, 'admin')}
                                        class="h-8 gap-1 rounded-lg bg-purple-600 px-3 text-xs text-white hover:bg-purple-700"
                                    >
                                        <ShieldCheck class="h-3.5 w-3.5" /> Jadikan Admin
                                    </Button>
                                {:else}
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        onclick={() => updateRole(user.id, 'customer')}
                                        class="h-8 gap-1 rounded-lg border-gray-300 px-3 text-xs dark:border-gray-600"
                                    >
                                        <User class="h-3.5 w-3.5" /> Jadikan Customer
                                    </Button>
                                {/if}
                                <div
                                    class="ml-2 inline-flex items-center border-l pl-2 dark:border-gray-700"
                                >
                                    <button
                                        onclick={() => openEditModal(user)}
                                        class="cursor-pointer rounded-lg p-1.5 text-gray-400 transition-colors hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-400"
                                    >
                                        <Edit2 class="h-4 w-4" />
                                    </button>
                                    <button
                                        onclick={() => deleteUser(user.id)}
                                        class="cursor-pointer rounded-lg p-1.5 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20 dark:hover:text-red-400"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
        <div
            class="border-t border-gray-100 bg-gray-50/50 px-5 py-3 text-xs text-gray-500 dark:border-gray-800 dark:bg-gray-800/20"
        >
            Menampilkan {filteredUsers.length} dari {users.length} user
        </div>
    {/if}
</div>

<AlertDialog.Root bind:open={alertDialogState.open}>
    <AlertDialog.Content>
        <AlertDialog.Header>
            <AlertDialog.Title>Hapus User?</AlertDialog.Title>
            <AlertDialog.Description>
                Tindakan ini tidak dapat dibatalkan. Menghapus user ini juga akan menghapus data
                terkait yang bergantung pada user ini (jika ada).
            </AlertDialog.Description>
        </AlertDialog.Header>
        <AlertDialog.Footer>
            <AlertDialog.Cancel onclick={() => (alertDialogState.open = false)}
                >Batal</AlertDialog.Cancel
            >
            <AlertDialog.Action
                onclick={confirmDeleteUser}
                class="bg-red-600 focus:ring-red-600 sm:hover:bg-red-700">Hapus</AlertDialog.Action
            >
        </AlertDialog.Footer>
    </AlertDialog.Content>
</AlertDialog.Root>
