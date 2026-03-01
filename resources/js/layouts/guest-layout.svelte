<script>
    import { Link } from "@inertiajs/svelte";
    import { ChevronLeft, Home } from "lucide-svelte";
    import { Button } from "@/components/ui/button/index.js";
</script>

<div class="flex min-h-screen flex-col bg-gray-50 dark:bg-gray-950 relative overflow-hidden">
    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] bg-blue-100/50 dark:bg-blue-900/10 blur-[120px] rounded-full"></div>
        <div class="absolute -bottom-[20%] -right-[10%] w-[50%] h-[50%] bg-indigo-100/50 dark:bg-indigo-900/10 blur-[120px] rounded-full"></div>
    </div>

    <!-- Header / Back Button -->
    <header class="relative z-10 w-full max-w-7xl mx-auto px-6 py-8 flex justify-between items-center">
        <Link href="/">
            <Button variant="ghost" class="group flex items-center gap-2 text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-all rounded-full px-4">
                <ChevronLeft class="w-4 h-4 transition-transform group-hover:-translate-x-1" />
                <span class="text-sm font-medium">Kembali ke Beranda</span>
            </Button>
        </Link>
        <div class="hidden sm:block">
            <Link href="/" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform">
                    <Home class="w-5 h-5 text-white" />
                </div>
                <span class="text-xl font-bold tracking-tight text-gray-900 dark:text-white font-heading">DSR <span class="text-blue-600">Rental</span></span>
            </Link>
        </div>
    </header>

    <main class="flex flex-1 flex-col items-center justify-center relative z-10 px-6 pb-12 sm:pb-24">
        <div class="w-full sm:max-w-md">
            <!-- Glassmorphism Card -->
            <div class="overflow-hidden bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-8 sm:p-10 shadow-2xl shadow-blue-500/5 ring-1 ring-gray-900/5 dark:ring-white/10 rounded-[2.5rem] transition-all">
                <slot />
            </div>
            
            <p class="mt-8 text-center text-xs text-gray-400 dark:text-gray-500">
                &copy; {new Date().getFullYear()} DSR Rental Mobil. All rights reserved.
            </p>
        </div>
    </main>
</div>
