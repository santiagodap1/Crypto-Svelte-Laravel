<script lang="ts">
	import { goto } from '$app/navigation';
	import { clearAuth, auth, type AuthState } from '$lib/stores/auth';
	import { logout } from '$lib/services/authService';
	import { onDestroy } from 'svelte';

	let state: AuthState;
	const unsubscribe = auth.subscribe(($auth) => (state = $auth));

	onDestroy(unsubscribe);

	async function handleLogout() {
		try {
			await logout();
		} catch (err) {
			console.error('Error on logout', err);
		} finally {
			clearAuth();
			goto('/login');
		}
	}
</script>

<nav class="backdrop-blur bg-dark/70 border-b border-white/5 sticky top-0 z-40">
	<div class="container mx-auto px-4 py-4 flex items-center justify-between">
		<a href="/" class="flex items-center space-x-3">
			<div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-lg font-black">
				C
			</div>
			<div>
				<p class="text-sm text-gray-400">Powered by CoinGecko</p>
				<p class="text-xl font-bold">Crypto Pulse</p>
			</div>
		</a>

		<div class="flex items-center space-x-6 text-sm font-medium">
			<a href="/" class="hover:text-primary transition">Mercado</a>
			<a href="/watchlist" class="hover:text-primary transition">Watchlist</a>
		</div>

		{#if state?.isAuthenticated}
			<div class="flex items-center space-x-3">
				<div class="text-right">
					<p class="text-sm text-gray-400">Hola</p>
					<p class="font-semibold">{state.user?.name}</p>
				</div>
				<button
					on:click|preventDefault={handleLogout}
					class="px-3 py-2 rounded-lg border border-white/10 hover:border-primary text-sm transition"
				>
					Cerrar sesión
				</button>
			</div>
		{:else}
			<div class="flex items-center space-x-3">
				<a href="/login" class="px-3 py-2 rounded-lg border border-white/10 hover:border-primary text-sm">
					Ingresar
				</a>
				<a
					href="/register"
					class="px-4 py-2 rounded-lg bg-gradient-to-r from-primary to-secondary text-sm font-semibold shadow-lg shadow-primary/20"
				>
					Crear cuenta
				</a>
			</div>
		{/if}
	</div>
</nav>
