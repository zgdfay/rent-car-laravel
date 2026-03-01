<script>
    import { useForm, Link } from "@inertiajs/svelte";
    import GuestLayout from "@/layouts/guest-layout.svelte";
    import InputError from "@/components/input-error.svelte";
    import { Mail, KeyRound, ArrowRight } from "lucide-svelte";
    import { Label } from "@/components/ui/label/index.js";
    import { Button } from "@/components/ui/button/index.js";
    import { Input } from "@/components/ui/input/index.js";

    let { status } = $props();

    const form = useForm({
        email: "",
    });

    const submit = (e) => {
        e.preventDefault();
        $form.post(route("password.email"));
    };
</script>

<GuestLayout>
    <div class="mb-8 text-center sm:text-left">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Lupa Kata Sandi</h1>
        <p class="text-gray-500 dark:text-gray-400">
            Jangan khawatir! Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
        </p>
    </div>

    {#if status}
        <div class="mb-6 p-4 rounded-2xl bg-green-50 dark:bg-green-900/20 text-sm font-medium text-green-600 dark:text-green-400 border border-green-100 dark:border-green-900/30">
            {status}
        </div>
    {/if}

    <form onsubmit={submit} class="space-y-6">
        <div class="space-y-2">
            <Label for="email">Alamat Email</Label>
            <div class="relative group">
                <Mail class="absolute left-3.5 top-3.5 w-4 h-4 text-gray-400 group-focus-within:text-blue-600 transition-colors" />
                <Input
                    id="email"
                    type="email"
                    bind:value={$form.email}
                    placeholder="email@contoh.com"
                    class="pl-10 h-11 rounded-xl bg-gray-50/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-800 focus:ring-blue-600"
                    required
                    autofocus
                    autocomplete="username"
                />
            </div>
            <InputError message={$form.errors.email} />
        </div>

        <Button
            type="submit"
            class="w-full h-12 bg-blue-600 hover:bg-blue-700 text-white dark:bg-blue-500 dark:hover:bg-blue-600 rounded-xl text-md font-bold shadow-lg shadow-blue-500/20 transition-all hover:scale-[1.01] active:scale-[0.99] gap-2"
            disabled={$form.processing}
        >
            {#if $form.processing}
                <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                Memuat...
            {:else}
                <KeyRound class="w-4 h-4" /> Kirim Tautan Reset
            {/if}
        </Button>

        <div class="pt-6 text-center border-t border-gray-100 dark:border-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                Tiba-tiba ingat? 
                <Link href={route('login')} class="font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors inline-flex items-center gap-1">
                    Kembali ke Masuk <ArrowRight class="w-3 h-3" />
                </Link>
            </p>
        </div>
    </form>
</GuestLayout>
