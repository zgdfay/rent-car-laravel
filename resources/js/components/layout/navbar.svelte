<script>
    import { Link, page, router } from '@inertiajs/svelte';
    import { Button } from '@/components/ui/button/index.js';
    import { User, LogOut, ChevronDown, LayoutDashboard, History } from 'lucide-svelte';

    let dropdownOpen = $state(false);

    function toggleDropdown() {
        dropdownOpen = !dropdownOpen;
    }

    function closeDropdown() {
        dropdownOpen = false;
    }

    function logout() {
        closeDropdown();
        router.post('/logout');
    }
</script>

<svelte:window
    onclick={() => {
        if (dropdownOpen) dropdownOpen = false;
    }}
/>

<nav
    class="sticky top-0 z-50 flex h-16 w-full items-center justify-between border-b bg-white/90 px-6 backdrop-blur-md dark:border-gray-800 dark:bg-gray-900/90"
>
    <div class="flex items-center">
        <Link
            href="/"
            class="text-primary flex items-center gap-2 text-xl font-bold text-gray-900 dark:text-white"
        >
            <div class="">
                <img src="/assets/images/logo-drs.png" alt="Logo" class="h-10 w-auto" />
            </div>
            <span class="text- blue-600 text-2xl font-extrabold tracking-tight dark:text-blue-400"
                >DRS RENTAL</span
            >
        </Link>
    </div>

    <div
        class="hidden items-center space-x-8 text-sm font-medium text-gray-600 md:flex dark:text-gray-300"
    >
        <Link
            href="/"
            class="transition-colors {$page.url === '/'
                ? 'font-bold text-blue-600 dark:text-blue-400'
                : 'hover:text-gray-900 dark:hover:text-white'}">Beranda</Link
        >
        <Link
            href="/list-car"
            class="transition-colors {$page.url.startsWith('/list-car')
                ? 'font-bold text-blue-600 dark:text-blue-400'
                : 'hover:text-gray-900 dark:hover:text-white'}">Daftar Mobil</Link
        >
        <Link
            href="/tentang"
            class="transition-colors {$page.url.startsWith('/tentang')
                ? 'font-bold text-blue-600 dark:text-blue-400'
                : 'hover:text-gray-900 dark:hover:text-white'}">Tentang</Link
        >
        <Link
            href="/kontak"
            class="transition-colors {$page.url.startsWith('/kontak')
                ? 'font-bold text-blue-600 dark:text-blue-400'
                : 'hover:text-gray-900 dark:hover:text-white'}">Kontak</Link
        >
    </div>

    <div class="flex items-center space-x-3">
        {#if $page.props.auth?.user}
            <Link href="/riwayat-order">
                <Button
                    variant="default"
                    class="h-9 gap-1.5 rounded-xl bg-blue-600 text-sm text-white hover:bg-blue-700"
                >
                    <History class="h-3.5 w-3.5" />
                    Riwayat Order
                </Button>
            </Link>

            <!-- User Dropdown -->
            <div class="relative">
                <button
                    onclick={(e) => {
                        e.stopPropagation();
                        toggleDropdown();
                    }}
                    class="flex cursor-pointer items-center gap-2 rounded-xl border border-gray-200 px-3 py-1.5 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
                >
                    <div
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white"
                    >
                        {$page.props.auth.user.name?.charAt(0)?.toUpperCase() || 'U'}
                    </div>
                    <span
                        class="hidden max-w-[120px] truncate text-sm font-medium text-gray-700 sm:block dark:text-gray-300"
                    >
                        {$page.props.auth.user.name}
                    </span>
                    <ChevronDown
                        class="h-3.5 w-3.5 text-gray-400 transition-transform {dropdownOpen
                            ? 'rotate-180'
                            : ''}"
                    />
                </button>

                {#if dropdownOpen}
                    <div
                        class="animate-in fade-in slide-in-from-top-2 absolute right-0 z-50 mt-2 w-56 rounded-xl bg-white py-1.5 shadow-lg ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10"
                        onclick={(e) => e.stopPropagation()}
                    >
                        <!-- User Info -->
                        <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                            <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">
                                {$page.props.auth.user.name}
                            </p>
                            <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                                {$page.props.auth.user.email}
                            </p>
                        </div>

                        <!-- Menu Items -->
                        {#if $page.props.auth.user.role === 'admin'}
                            <Link
                                href="/dashboard"
                                onclick={closeDropdown}
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                            >
                                <LayoutDashboard class="h-4 w-4 text-gray-400" />
                                Dashboard
                            </Link>
                        {/if}

                        <Link
                            href="/profile"
                            onclick={closeDropdown}
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                        >
                            <User class="h-4 w-4 text-gray-400" />
                            Profil Saya
                        </Link>

                        <div class="my-1.5 border-t border-gray-100 dark:border-gray-800"></div>

                        <button
                            onclick={logout}
                            class="flex w-full cursor-pointer items-center gap-3 px-4 py-2.5 text-sm text-red-600 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                        >
                            <LogOut class="h-4 w-4" />
                            Keluar
                        </button>
                    </div>
                {/if}
            </div>
        {:else}
            <Link
                href="/login"
                class="text-sm font-medium text-gray-600 transition-colors hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                >Masuk</Link
            >
            <Link href="/register">
                <Button
                    variant="default"
                    class="rounded-xl bg-blue-600 text-white hover:bg-blue-700">Daftar</Button
                >
            </Link>
        {/if}
    </div>
</nav>
