<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=Merriweather:wght@400;700;900&display=swap">
@vite(['resources/css/guest.css', 'resources/js/app.js'])
<style type="text/tailwindcss">
    @layer base {
        body {
            @apply bg-background-light text-slate-900;
        }
        .dark body {
            @apply bg-background-dark text-slate-100;
        }
    }
</style>