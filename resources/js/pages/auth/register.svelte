<script>
    import { Link, useForm } from '@inertiajs/svelte';
    import GuestLayout from '@/layouts/guest-layout.svelte';
    import InputError from '@/components/input-error.svelte';
    import { User, Mail, Lock, UserPlus, ArrowRight, Eye, EyeOff } from 'lucide-svelte';

    import { Label } from '@/components/ui/label/index.js';
    import { Button } from '@/components/ui/button/index.js';
    import { Input } from '@/components/ui/input/index.js';

    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    let showPassword = $state(false);
    let showConfirmPassword = $state(false);

    const submit = (e) => {
        e.preventDefault();
        $form.post(route('register'));
    };
</script>

<GuestLayout>
    <div class="mb-8 text-center sm:text-left">
        <h1 class="mb-2 text-3xl font-extrabold text-gray-900 dark:text-white">Buat Akun</h1>
        <p class="text-gray-500 dark:text-gray-400">
            Daftar sekarang untuk mulai menyewa kendaraan impian Anda.
        </p>
    </div>

    <form onsubmit={submit} class="space-y-5">
        <div class="space-y-2">
            <Label for="name">Nama Lengkap</Label>
            <div class="group relative">
                <User
                    class="absolute top-3.5 left-3.5 h-4 w-4 text-gray-400 transition-colors group-focus-within:text-blue-600"
                />
                <Input
                    id="name"
                    type="text"
                    bind:value={$form.name}
                    placeholder="Masukkan nama lengkap"
                    class="h-11 rounded-xl border-gray-200 bg-gray-50/50 pl-10 focus:ring-blue-600 dark:border-gray-800 dark:bg-gray-800/50"
                    required
                    autofocus
                    autocomplete="name"
                />
            </div>
            <InputError message={$form.errors.name} />
        </div>

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
                    autocomplete="username"
                />
            </div>
            <InputError message={$form.errors.email} />
        </div>

        <div class="space-y-2">
            <Label for="password">Kata Sandi</Label>
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
                    autocomplete="new-password"
                />
                <button
                    type="button"
                    onclick={() => (showPassword = !showPassword)}
                    class="absolute top-3.5 right-3 cursor-pointer text-gray-400 hover:text-gray-600 focus:outline-none dark:hover:text-gray-300"
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

        <div class="space-y-2">
            <Label for="password_confirmation">Konfirmasi Kata Sandi</Label>
            <div class="group relative">
                <Lock
                    class="absolute top-3.5 left-3.5 h-4 w-4 text-gray-400 transition-colors group-focus-within:text-blue-600"
                />
                <Input
                    id="password_confirmation"
                    type={showConfirmPassword ? 'text' : 'password'}
                    bind:value={$form.password_confirmation}
                    placeholder="••••••••"
                    class="h-11 rounded-xl border-gray-200 bg-gray-50/50 pr-10 pl-10 focus:ring-blue-600 dark:border-gray-800 dark:bg-gray-800/50"
                    required
                    autocomplete="new-password"
                />
                <button
                    type="button"
                    onclick={() => (showConfirmPassword = !showConfirmPassword)}
                    class="absolute top-3.5 right-3 cursor-pointer text-gray-400 hover:text-gray-600 focus:outline-none dark:hover:text-gray-300"
                >
                    {#if showConfirmPassword}
                        <EyeOff class="h-4 w-4" />
                    {:else}
                        <Eye class="h-4 w-4" />
                    {/if}
                </button>
            </div>
            <InputError message={$form.errors.password_confirmation} />
        </div>

        <Button
            type="submit"
            class="text-md mt-4 h-12 w-full gap-2 rounded-xl bg-blue-600 font-bold text-white shadow-lg shadow-blue-500/20 transition-all hover:scale-[1.01] hover:bg-blue-700 active:scale-[0.99] dark:bg-blue-500 dark:hover:bg-blue-600"
            disabled={$form.processing}
        >
            {#if $form.processing}
                <div
                    class="h-4 w-4 animate-spin rounded-full border-2 border-white/30 border-t-white"
                ></div>
                Memuat...
            {:else}
                <UserPlus class="h-4 w-4" /> Daftar Sekarang
            {/if}
        </Button>

        <div class="border-t border-gray-100 pt-6 text-center dark:border-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Sudah punya akun?
                <Link
                    href={route('login')}
                    class="inline-flex items-center gap-1 font-bold text-gray-900 transition-colors hover:text-blue-600 dark:text-white dark:hover:text-blue-400"
                >
                    Masuk ke Akun <ArrowRight class="h-3 w-3" />
                </Link>
            </p>
        </div>
    </form>
</GuestLayout>
