<script>
    import Navbar from "@/components/layout/navbar.svelte";
    import Footer from "@/components/layout/footer.svelte";

    let { children } = $props();
</script>

<div class="min-h-screen bg-white dark:bg-gray-900 flex flex-col">
    <Navbar />
    
    <main class="grow">
        {@render children?.()}
    </main>
    
    <Footer />
</div>
