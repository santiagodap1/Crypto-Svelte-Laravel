<script>
	import '../app.css';
	import GlobalStats from '$lib/components/GlobalStats.svelte';
</script>

<div class="min-h-screen bg-dark text-white relative overflow-hidden">
	<GlobalStats />
	<nav class="bg-dark-lighter p-4 border-b border-gray-700 relative z-10">
		<div class="container mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
			<a
				href="/"
				class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary"
			>
				CryptoApp
			</a>
			<div class="flex flex-wrap justify-center gap-4">
				<a href="/" class="hover:text-primary transition">Home</a>
				<a href="/watchlist" class="hover:text-primary transition">Watchlist</a>
				<a href="/exchanges" class="hover:text-primary transition">Exchanges</a>
				<a href="/login" class="hover:text-primary transition">Login</a>
				<a href="/register" class="bg-primary px-4 py-2 rounded-lg hover:bg-secondary transition"
					>Register</a
				>
			</div>
		</div>
	</nav>

	<main class="container mx-auto px-4 py-10 relative">
		<slot />
	</main>
</div>
