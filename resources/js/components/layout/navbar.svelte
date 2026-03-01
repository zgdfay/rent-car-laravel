<script>
    import { Link, page, router } from "@inertiajs/svelte";
    import { Button } from "@/components/ui/button/index.js";
    import { User, LogOut, ChevronDown, LayoutDashboard, History } from "lucide-svelte";

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

<svelte:window onclick={() => { if (dropdownOpen) dropdownOpen = false; }} />

<nav class="sticky top-0 z-50 flex h-16 w-full items-center justify-between border-b bg-white/90 backdrop-blur-md px-6 dark:bg-gray-900/90 dark:border-gray-800">
    <div class="flex items-center">
        <Link href="/" class="flex text-primary items-center gap-2 text-xl font-bold text-gray-900 dark:text-white">
            <span class="text-2xl font-extrabold tracking-tight text-blue-600 dark:text-blue-400">DSR</span>
        </Link>
    </div>
    
    <div class="hidden items-center space-x-8 text-sm font-medium text-gray-600 md:flex dark:text-gray-300">
        <Link href="/" class="transition-colors {$page.url === '/' ? 'text-blue-600 dark:text-blue-400 font-bold' : 'hover:text-gray-900 dark:hover:text-white'}">Beranda</Link>
        <Link href="/list-car" class="transition-colors {$page.url.startsWith('/list-car') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'hover:text-gray-900 dark:hover:text-white'}">Daftar Mobil</Link>
        <Link href="/tentang" class="transition-colors {$page.url.startsWith('/tentang') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'hover:text-gray-900 dark:hover:text-white'}">Tentang</Link>
        <Link href="/kontak" class="transition-colors {$page.url.startsWith('/kontak') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'hover:text-gray-900 dark:hover:text-white'}">Kontak</Link>
    </div>

    <div class="flex items-center space-x-3">
        {#if $page.props.auth?.user}
            <Link href="/riwayat-order">
                <Button variant="default" class="bg-blue-600 text-white hover:bg-blue-700 rounded-xl gap-1.5 h-9 text-sm">
                    <History class="w-3.5 h-3.5" />
                    Riwayat Order
                </Button>
            </Link>

            <!-- User Dropdown -->
            <div class="relative">
                <button 
                    onclick={(e) => { e.stopPropagation(); toggleDropdown(); }}
                    class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                >
                    <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                        {$page.props.auth.user.name?.charAt(0)?.toUpperCase() || 'U'}
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:block max-w-[120px] truncate">
                        {$page.props.auth.user.name}
                    </span>
                    <ChevronDown class="w-3.5 h-3.5 text-gray-400 transition-transform {dropdownOpen ? 'rotate-180' : ''}" />
                </button>

                {#if dropdownOpen}
                    <div 
                        class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-900 rounded-xl shadow-lg ring-1 ring-gray-900/5 dark:ring-white/10 py-1.5 z-50 animate-in fade-in slide-in-from-top-2"
                        onclick={(e) => e.stopPropagation()}
                    >
                        <!-- User Info -->
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{$page.props.auth.user.name}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{$page.props.auth.user.email}</p>
                        </div>

                        <!-- Menu Items -->
                        {#if $page.props.auth.user.role === 'admin'}
                            <Link 
                                href="/dashboard" 
                                onclick={closeDropdown}
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                            >
                                <LayoutDashboard class="w-4 h-4 text-gray-400" />
                                Dashboard
                            </Link>
                        {/if}

                        <Link 
                            href="/profile" 
                            onclick={closeDropdown}
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                        >
                            <User class="w-4 h-4 text-gray-400" />
                            Profil Saya
                        </Link>

                        <div class="border-t border-gray-100 dark:border-gray-800 my-1.5"></div>

                        <button 
                            onclick={logout}
                            class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors cursor-pointer"
                        >
                            <LogOut class="w-4 h-4" />
                            Keluar
                        </button>
                    </div>
                {/if}
            </div>
        {:else}
            <Link href="/login" class="text-sm font-medium text-gray-600 transition-colors hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Masuk</Link>
            <Link href="/register">
                <Button variant="default" class="bg-blue-600 text-white hover:bg-blue-700 rounded-xl">Daftar</Button>
            </Link>
        {/if}
    </div>
</nav>
