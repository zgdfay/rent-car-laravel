<script>
    export let title = 'DRS - Modern Car Rental';
    export let description =
        'Experience premium mobility with our vast fleet of luxury, sport, and practical vehicles.';
</script>

<svelte:head>
    <title>{title}</title>
    <meta name="description" content={description} />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content={title} />
    <meta property="og:description" content={description} />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:title" content={title} />
    <meta property="twitter:description" content={description} />
</svelte:head>
