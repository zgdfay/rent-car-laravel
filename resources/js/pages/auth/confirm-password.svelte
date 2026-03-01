<script>
    import { useForm } from '@inertiajs/svelte';
    import Head from '@/components/head.svelte';
    import GuestLayout from '@/layouts/guest-layout.svelte';
    import InputError from '@/components/input-error.svelte';
    import { Lock, ShieldCheck, Eye, EyeOff } from 'lucide-svelte';
    import { Label } from '@/components/ui/label/index.js';
    import { Button } from '@/components/ui/button/index.js';
    import { Input } from '@/components/ui/input/index.js';

    const form = useForm({
        password: '',
    });

    let showPassword = $state(false);

    const submit = (e) => {
        e.preventDefault();
        $form.post(route('password.confirm'), {
            onFinish: () => $form.reset(),
        });
    };
</script>

<Head title="Konfirmasi Kata Sandi" />

<GuestLayout>
    <div class="mb-8 text-center sm:text-left">
        <h1 class="mb-2 text-3xl font-extrabold text-gray-900 dark:text-white">Konfirmasi Sandi</h1>
        <p class="text-gray-500 dark:text-gray-400">
            Ini adalah area aman. Silakan konfirmasi kata sandi Anda sebelum melanjutkan.
        </p>
    </div>

    <form onsubmit={submit} class="space-y-6">
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
                    autocomplete="current-password"
                    autofocus
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
                <ShieldCheck class="h-4 w-4" /> Konfirmasi
            {/if}
        </Button>
    </form>
</GuestLayout>
