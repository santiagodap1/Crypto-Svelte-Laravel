<script lang="ts">
	import { onDestroy, onMount } from 'svelte';
	import { goto } from '$app/navigation';
	import { fetchWatchlist } from '$lib/services/watchlistService';
	import { auth, type AuthState } from '$lib/stores/auth';
	import type { CoinMarket, WatchlistItem } from '$lib/types';
	import { formatCurrency, formatNumber, formatPercent } from '$lib/utils/format';

	let loading = true;
	let error = '';
	let markets: CoinMarket[] = [];
	let items: WatchlistItem[] = [];
	let session: AuthState;

	const unsubscribe = auth.subscribe(($auth) => (session = $auth));
	onDestroy(unsubscribe);

	onMount(async () => {
		if (!session?.isAuthenticated) {
			loading = false;
			return;
		}

		try {
			const data = await fetchWatchlist(true);
			if (Array.isArray(data)) {
				items = data;
				markets = [];
			} else {
				items = data.items;
				markets = data.markets;
			}
		} catch (err) {
			error = 'Could not load your watchlist';
		} finally {
			loading = false;
		}
	});
</script>

<div class="space-y-8">
	<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
		<div>
			<p class="text-sm text-gray-400">Personalized tracking</p>
			<h1 class="text-4xl font-black">Your watchlist</h1>
		</div>
		<a
			href="/"
			class="px-4 py-3 rounded-xl border border-white/10 hover:border-primary transition text-sm"
		>
			Explore more assets
		</a>
	</div>

	{#if !session?.isAuthenticated}
		<div class="card p-10 text-center space-y-4">
			<h3 class="text-2xl font-bold">Log in to save assets</h3>
			<p class="text-gray-400">
				Create an account to keep your list synchronized and available on all your devices.
			</p>
			<div class="flex justify-center gap-3">
				<a
					href="/login"
					class="px-5 py-3 rounded-xl bg-primary/10 border border-primary text-primary font-semibold"
					>Log in</a
				>
				<a
					href="/register"
					class="px-5 py-3 rounded-xl bg-gradient-to-r from-primary to-secondary font-semibold shadow-lg shadow-primary/20"
					>Create account</a
				>
			</div>
		</div>
	{:else if loading}
		<div class="flex justify-center py-14">
			<div
				class="h-12 w-12 rounded-full border-2 border-primary border-t-transparent animate-spin"
			></div>
		</div>
	{:else if items.length === 0}
		<div class="card p-10 text-center space-y-4">
			<h3 class="text-2xl font-bold">You have no saved assets yet</h3>
			<p class="text-gray-400">
				Search for your favorite projects and click "Add to watchlist" to see them here.
			</p>
			<button
				class="px-5 py-3 rounded-xl bg-primary/10 border border-primary text-primary font-semibold"
				on:click={() => goto('/')}
			>
				Go to market
			</button>
		</div>
	{:else if markets.length === 0}
		<div class="card p-8 text-center space-y-3">
			<h3 class="text-xl font-semibold">No market data</h3>
			<p class="text-gray-400">
				Your IDs are saved but we could not retrieve prices at this moment.
			</p>
		</div>
	{:else}
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
			{#each markets as coin}
				<button
					type="button"
					class="card p-5 hover:border-primary/60 transition cursor-pointer text-left"
					on:click={() => goto(`/coin/${coin.id}`)}
				>
					<div class="flex items-center justify-between mb-4">
						<div class="flex items-center space-x-3">
							<img src={coin.image} alt={coin.name} class="h-10 w-10 rounded-full" />
							<div>
								<p class="font-semibold">{coin.name}</p>
								<p class="text-xs text-gray-400 uppercase">{coin.symbol}</p>
							</div>
						</div>
						<span
							class={`px-3 py-1 rounded-full text-xs font-semibold ${
								coin.price_change_percentage_24h >= 0
									? 'bg-emerald-500/10 text-emerald-400'
									: 'bg-rose-500/10 text-rose-400'
							}`}
						>
							{formatPercent(coin.price_change_percentage_24h)}
						</span>
					</div>
					<p class="text-3xl font-mono">{formatCurrency(coin.current_price)}</p>
					<p class="text-xs text-gray-400 mt-2">Cap: {formatNumber(coin.market_cap)}</p>
				</button>
			{/each}
		</div>
	{/if}
</div>
