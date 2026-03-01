<script>
    import AppLayout from '@/layouts/app-layout.svelte';
    import AuthenticatedLayout from '@/layouts/authenticated-layout.svelte';
    import { page } from '@inertiajs/svelte';
    import DeleteUserForm from './partials/delete-user-form.svelte';
    import UpdatePasswordForm from './partials/update-password-form.svelte';
    import UpdateProfileInformationForm from './partials/update-profile-information-form.svelte';

    let { mustVerifyEmail, status } = $props();

    const isAdmin = $derived($page.props.auth?.user?.role === 'admin');
</script>

{#if isAdmin}
<AuthenticatedLayout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                <UpdateProfileInformationForm {mustVerifyEmail} {status} class="max-w-xl" />
            </div>
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                <UpdatePasswordForm class="max-w-xl" />
            </div>
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </div>
</AuthenticatedLayout>
{:else}
<AppLayout>
    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Profil Saya</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Kelola informasi akun dan keamanan Anda.</p>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-900 p-6 sm:p-8 shadow-sm rounded-2xl ring-1 ring-gray-900/5 dark:ring-white/10">
                    <UpdateProfileInformationForm {mustVerifyEmail} {status} class="max-w-xl" />
                </div>
                <div class="bg-white dark:bg-gray-900 p-6 sm:p-8 shadow-sm rounded-2xl ring-1 ring-gray-900/5 dark:ring-white/10">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>
                <div class="bg-white dark:bg-gray-900 p-6 sm:p-8 shadow-sm rounded-2xl ring-1 ring-gray-900/5 dark:ring-white/10">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </div>
</AppLayout>
{/if}
