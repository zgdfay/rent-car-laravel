<script>
    import AppLayout from '@/layouts/app-layout.svelte';
    import HeroSection from '@/components/section/hero-section.svelte';
    import FeaturesSection from '@/components/section/features-section.svelte';
    import HowItWorksSection from '@/components/section/how-it-works-section.svelte';
    import CarFleetSection from '@/components/section/car-fleet-section.svelte';
    import CtaSection from '@/components/section/cta-section.svelte';

    let { featuredCars = [] } = $props();
</script>

<svelte:head>
    <title>Selamat Datang di DRS - Rental Mobil Modern</title>
</svelte:head>

<AppLayout>
    <HeroSection />
    <FeaturesSection />
    <HowItWorksSection />
    <CarFleetSection {featuredCars} />
    <CtaSection />
</AppLayout>
