<script>
    import { useForm, Link } from "@inertiajs/svelte";
    import Head from "@/components/head.svelte";
    import GuestLayout from "@/layouts/guest-layout.svelte";
    import { Button } from "@/components/ui/button/index.js";
    import { MailCheck, LogOut, Send } from "lucide-svelte";

    const { status } = $props();

    const form = useForm({});

    const verificationLinkSent = $derived(status === "verification-link-sent");

    const submit = (e) => {
        e.preventDefault();
        $form.post(route("verification.send"));
    };
</script>

<Head title="Verifikasi Email" />

<GuestLayout>
    <div class="mb-8 text-center sm:text-left">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Verifikasi Email</h1>
        <p class="text-gray-500 dark:text-gray-400">
            Terima kasih telah mendaftar! Sebelum memulai, harap verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan.
        </p>
    </div>

    {#if verificationLinkSent}
        <div class="mb-6 p-4 rounded-2xl bg-green-50 dark:bg-green-900/20 text-sm font-medium text-green-600 dark:text-green-400 border border-green-100 dark:border-green-900/30 flex items-center gap-3">
            <MailCheck class="w-5 h-5 shrink-0" />
            <span>Tautan verifikasi baru telah dikirim ke alamat email Anda.</span>
        </div>
    {/if}

    <form onsubmit={submit} class="space-y-6">
        <Button
            type="submit"
            class="w-full h-12 bg-blue-600 hover:bg-blue-700 text-white dark:bg-blue-500 dark:hover:bg-blue-600 rounded-xl text-md font-bold shadow-lg shadow-blue-500/20 transition-all hover:scale-[1.01] active:scale-[0.99] gap-2"
            disabled={$form.processing}
        >
            {#if $form.processing}
                <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                Memuat...
            {:else}
                <Send class="w-4 h-4" /> Kirim Ulang Email Verifikasi
            {/if}
        </Button>

        <div class="pt-6 text-center border-t border-gray-100 dark:border-gray-800 flex justify-center">
            <Link
                href={route("logout")}
                method="post"
                as="button"
                class="text-sm font-semibold text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors inline-flex items-center gap-2"
            >
                <LogOut class="w-4 h-4" /> Keluar
            </Link>
        </div>
    </form>
</GuestLayout>
