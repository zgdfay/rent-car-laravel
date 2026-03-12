<script module>
    import LayoutDashboardIcon from '@lucide/svelte/icons/layout-dashboard';
    import CarIcon from '@lucide/svelte/icons/car';
    import UsersIcon from '@lucide/svelte/icons/users';
    import CalendarCheckIcon from '@lucide/svelte/icons/calendar-check';
    import FileBarChartIcon from '@lucide/svelte/icons/file-bar-chart';
    import Settings2Icon from '@lucide/svelte/icons/settings-2';
    import LifeBuoyIcon from '@lucide/svelte/icons/life-buoy';
    import GlobeIcon from '@lucide/svelte/icons/globe';

    const data = {
        user: {
            name: 'Admin',
            email: 'admin@drs.com',
            avatar: '',
        },
        navMain: [
            {
                title: 'Dashboard',
                url: route('dashboard'),
                icon: LayoutDashboardIcon,
                isActive: true,
            },
            {
                title: 'Manajemen Reservasi',
                url: '/admin/reservasi',
                icon: CalendarCheckIcon,
            },
            {
                title: 'Manajemen Mobil',
                url: '/admin/mobil',
                icon: CarIcon,
            },
            {
                title: 'Manajemen User',
                url: '/admin/users',
                icon: UsersIcon,
            },
            {
                title: 'Laporan',
                url: '/admin/laporan',
                icon: FileBarChartIcon,
            },
            {
                title: 'Pengaturan',
                url: route('profile.edit'),
                icon: Settings2Icon,
            },
        ],
        navSecondary: [
            {
                title: 'Lihat Website',
                url: '/',
                icon: GlobeIcon,
            },
        ],
    };
</script>

<script>
    import NavMain from './nav-main.svelte';
    import NavSecondary from './nav-secondary.svelte';
    import NavUser from './nav-user.svelte';
    import * as Sidebar from '@/components/ui/sidebar/index.js';
    import { Link, page } from '@inertiajs/svelte';
    let { ref = $bindable(null), ...restProps } = $props();

    let user = $page.props.auth.user;
    data.user.name = user.name;
    data.user.email = user.email;
</script>

<Sidebar.Root bind:ref variant="inset" {...restProps}>
    <Sidebar.Header>
        <Sidebar.Menu>
            <Sidebar.MenuItem>
                <Sidebar.MenuButton size="lg">
                    {#snippet child({ props })}
                        <Link href="/dashboard" {...props}>
                            <div
                                class="flex aspect-square size-8 items-center justify-center rounded-lg bg-blue-600 text-sm font-extrabold text-white"
                            >
                                D
                            </div>
                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-bold">DRS Rental</span>
                                <span class="truncate text-xs text-gray-500">Admin Panel</span>
                            </div>
                        </Link>
                    {/snippet}
                </Sidebar.MenuButton>
            </Sidebar.MenuItem>
        </Sidebar.Menu>
    </Sidebar.Header>
    <Sidebar.Content>
        <NavMain items={data.navMain} />
        <NavSecondary items={data.navSecondary} class="mt-auto" />
    </Sidebar.Content>
    <Sidebar.Footer>
        <NavUser user={data.user} />
    </Sidebar.Footer>
</Sidebar.Root>
