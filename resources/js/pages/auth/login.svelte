<script>
    import { Link, useForm } from '@inertiajs/svelte';
    import GuestLayout from '@/layouts/guest-layout.svelte';
    import InputError from '@/components/input-error.svelte';
    import { Mail, Lock, LogIn, ArrowRight, Eye, EyeOff } from 'lucide-svelte';

    import { Label } from '@/components/ui/label/index.js';
    import { Button } from '@/components/ui/button/index.js';
    import { Input } from '@/components/ui/input/index.js';
    import { Checkbox } from '@/components/ui/checkbox/index.js';

    let { canResetPassword, status } = $props();

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    let showPassword = $state(false);

    const submit = (e) => {
        e.preventDefault();
        $form.post(route('login'));
    };
</script>

<GuestLayout>
    <div class="mb-8 text-center sm:text-left">
        <h1 class="mb-2 text-3xl font-extrabold text-gray-900 dark:text-white">Selamat Datang</h1>
        <p class="text-gray-500 dark:text-gray-400">
            Silakan masuk ke akun Anda untuk melanjutkan.
        </p>
    </div>

    {#if status}
        <div
            class="mb-6 rounded-2xl border border-green-100 bg-green-50 p-4 text-sm font-medium text-green-600 dark:border-green-900/30 dark:bg-green-900/20 dark:text-green-400"
        >
            {status}
        </div>
    {/if}

    <form onsubmit={submit} class="space-y-5">
        <div class="space-y-2">
            <Label for="email">Alamat Email</Label>
            <div class="group relative">
                <Mail
                    class="absolute top-3.5 left-3.5 h-4 w-4 text-gray-400 transition-colors group-focus-within:text-blue-600"
                />
                <Input
                    id="email"
                    type="email"
                    bind:value={$form.email}
                    placeholder="email@contoh.com"
                    class="h-11 rounded-xl border-gray-200 bg-gray-50/50 pl-10 focus:ring-blue-600 dark:border-gray-800 dark:bg-gray-800/50"
                    required
                    autofocus
                    autocomplete="username"
                />
            </div>
            <InputError message={$form.errors.email} />
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <Label for="password">Kata Sandi</Label>
            </div>
            <div class="group relative">
                <Lock
                    class="absolute top-3.5 left-3.5 h-4 w-4 text-gray-400 transition-colors group-focus-within:text-blue-600"
                />
                <Input
                    id="password"
                    type={showPassword ? 'text' : 'password'}
                    bind:value={$form.password}
                    placeholder="••••••••"
                    class="h-11 rounded-xl border-gray-200 bg-gray-50/50 pr-10 pl-10 focus:ring-blue-600 dark:border-gray-800 dark:bg-gray-800/50"
                    required
                    autocomplete="current-password"
                />
                <button
                    type="button"
                    onclick={() => (showPassword = !showPassword)}
                    class="absolute top-3 right-3 cursor-pointer text-gray-400 hover:text-gray-600 focus:outline-none dark:hover:text-gray-300"
                >
                    {#if showPassword}
                        <EyeOff class="h-4 w-4" />
                    {:else}
                        <Eye class="h-4 w-4" />
                    {/if}
                </button>
            </div>
            <InputError message={$form.errors.password} />
        </div>

        <div class="flex items-center justify-between py-2">
            <label class="group flex cursor-pointer items-center">
                <Checkbox
                    bind:checked={$form.remember}
                    class="rounded-md border-gray-300 text-blue-600 transition-all focus:ring-blue-600 dark:border-gray-700"
                />
                <span
                    class="ms-2 text-sm font-medium text-gray-600 transition-colors group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-gray-200"
                >
                    Ingat saya
                </span>
            </label>
            {#if canResetPassword}
                <Link
                    href={route('password.request')}
                    class="text-xs font-semibold text-blue-600 transition-colors hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
                >
                    Lupa Kata Sandi?
                </Link>
            {/if}
        </div>

        <Button
            type="submit"
            class="text-md h-12 w-full gap-2 rounded-xl bg-blue-600 font-bold text-white shadow-lg shadow-blue-500/20 transition-all hover:scale-[1.01] hover:bg-blue-700 active:scale-[0.99] dark:bg-blue-500 dark:hover:bg-blue-600"
            disabled={$form.processing}
        >
            {#if $form.processing}
                <div
                    class="h-4 w-4 animate-spin rounded-full border-2 border-white/30 border-t-white"
                ></div>
                Memuat...
            {:else}
                <LogIn class="h-4 w-4" /> Masuk Sekarang
            {/if}
        </Button>

        <div class="border-t border-gray-100 pt-6 text-center dark:border-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Belum punya akun?
                <Link
                    href={route('register')}
                    class="inline-flex items-center gap-1 font-bold text-gray-900 transition-colors hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                >
                    Daftar Sekarang <ArrowRight class="h-3 w-3" />
                </Link>
            </p>
        </div>
    </form>
</GuestLayout>
