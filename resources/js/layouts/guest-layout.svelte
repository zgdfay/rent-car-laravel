<script>
    import { Link } from '@inertiajs/svelte';
    import { ChevronLeft, Home } from 'lucide-svelte';
    import { Button } from '@/components/ui/button/index.js';
</script>

<div class="relative flex min-h-screen flex-col overflow-hidden bg-gray-50 dark:bg-gray-950">
    <!-- Background Accents -->
    <div class="pointer-events-none absolute top-0 left-0 z-0 h-full w-full overflow-hidden">
        <div
            class="absolute -top-[20%] -left-[10%] h-[50%] w-[50%] rounded-full bg-blue-100/50 blur-[120px] dark:bg-blue-900/10"
        ></div>
        <div
            class="absolute -right-[10%] -bottom-[20%] h-[50%] w-[50%] rounded-full bg-indigo-100/50 blur-[120px] dark:bg-indigo-900/10"
        ></div>
    </div>

    <!-- Header / Back Button -->
    <header
        class="relative z-10 mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-8"
    >
        <Link href="/">
            <Button
                variant="ghost"
                class="group flex items-center gap-2 rounded-full px-4 text-gray-600 transition-all hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400"
            >
                <ChevronLeft class="h-4 w-4 transition-transform group-hover:-translate-x-1" />
                <span class="text-sm font-medium">Kembali ke Beranda</span>
            </Button>
        </Link>
        <div class="hidden sm:block">
            <Link href="/" class="group flex items-center gap-2">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 shadow-lg shadow-blue-500/20 transition-transform group-hover:scale-110"
                >
                    <Home class="h-5 w-5 text-white" />
                </div>
                <span
                    class="font-heading text-xl font-bold tracking-tight text-gray-900 dark:text-white"
                    >DRS <span class="text-blue-600">Rental</span></span
                >
            </Link>
        </div>
    </header>

    <main
        class="relative z-10 flex flex-1 flex-col items-center justify-center px-6 pb-12 sm:pb-24"
    >
        <div class="w-full sm:max-w-md">
            <!-- Glassmorphism Card -->
            <div
                class="overflow-hidden rounded-[2.5rem] bg-white/80 p-8 shadow-2xl ring-1 shadow-blue-500/5 ring-gray-900/5 backdrop-blur-xl transition-all sm:p-10 dark:bg-gray-900/80 dark:ring-white/10"
            >
                <slot />
            </div>

            <p class="mt-8 text-center text-xs text-gray-400 dark:text-gray-500">
                &copy; {new Date().getFullYear()} DRS Rental Mobil. All rights reserved.
            </p>
        </div>
    </main>
</div>
