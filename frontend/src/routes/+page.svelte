<script lang="ts">
	import { goto } from '$app/navigation';
	import { onMount } from 'svelte';
	import { getMarkets, getTrending } from '$lib/services/coinService';
	import type { CoinMarket } from '$lib/types';
	import {
		formatCompactCurrency,
		formatCurrency,
		formatNumber,
		formatPercent
	} from '$lib/utils/format';

	let coins: CoinMarket[] = [];
	let trending: any[] = [];
	let loading = true;
	let search = '';

	$: filtered = coins.filter(
		(coin) =>
			coin.name.toLowerCase().includes(search.toLowerCase()) ||
			coin.symbol.toLowerCase().includes(search.toLowerCase())
	);

	$: spotlight = coins.slice(0, 3);

	onMount(async () => {
		try {
			// Fetch both markets and trending coins in parallel
			const [marketData, trendingData] = await Promise.all([
				getMarkets({ per_page: 60 }),
				getTrending()
			]);
			coins = marketData;
			trending = trendingData; // Assign trending data
		} catch (error) {
			console.error('Failed to fetch data', error); // Changed error message to be more general
		} finally {
			loading = false;
		}
	});
</script>

<div class="space-y-10">
	<section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
		<div class="lg:col-span-7 space-y-6">
			<div
				class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-white/10 text-sm text-gray-300"
			>
				<span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
				<span>Live market updates</span>
			</div>
			<h1 class="text-4xl sm:text-5xl font-black leading-tight">
				Control the crypto pulse with <span
					class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary"
					>clean data</span
				> and a polished UX.
			</h1>
			<p class="text-lg text-gray-300 max-w-2xl">
				Authentication with Laravel Sanctum, persistent watchlist and direct data from CoinGecko.
				Track your assets with clarity and without noise.
			</p>
			<div class="flex flex-wrap gap-4">
				<a
					href="/register"
					class="px-6 py-3 rounded-xl bg-gradient-to-r from-primary to-secondary font-semibold shadow-lg shadow-primary/30"
				>
					Create account
				</a>
				<button
					class="px-6 py-3 rounded-xl border border-white/15 hover:border-primary transition"
					on:click={() => goto('/watchlist')}
				>
					View my watchlist
				</button>
			</div>
			<div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
				{#each spotlight as coin}
					<div class="card p-4">
						<div class="flex items-center justify-between mb-2">
							<div class="flex items-center space-x-3">
								<img src={coin.image} alt={coin.name} class="h-8 w-8 rounded-full" />
								<div>
									<p class="font-semibold">{coin.name}</p>
									<p class="text-xs text-gray-400 uppercase">{coin.symbol}</p>
								</div>
							</div>
							<span
								class={coin.price_change_percentage_24h >= 0 ? 'text-emerald-400' : 'text-rose-400'}
							>
								{formatPercent(coin.price_change_percentage_24h)}
							</span>
						</div>
						<p class="text-2xl font-mono">{formatCurrency(coin.current_price)}</p>
						<p class="text-xs text-gray-400 mt-1">MC {formatNumber(coin.market_cap)}</p>
					</div>
				{/each}
			</div>
		</div>
		<div class="lg:col-span-5 card p-6 space-y-5">
			<div class="flex items-center justify-between">
				<div>
					<p class="text-sm text-gray-400">Smart search</p>
					<p class="text-xl font-semibold">Explore the market</p>
				</div>
				<span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold">
					CoinGecko API
				</span>
			</div>
			<div class="relative">
				<input
					type="search"
					placeholder="Search by name or ticker"
					bind:value={search}
					class="w-full bg-white/5 border border-white/10 focus:border-primary focus:ring-2 focus:ring-primary/30 rounded-xl px-4 py-3 outline-none"
				/>
				<div class="absolute right-3 top-3 text-xs text-gray-400 bg-white/5 px-2 py-1 rounded">
					⌘K
				</div>
			</div>
			<p class="text-sm text-gray-400">
				Showing {filtered.length} of {coins.length} assets. Filters applied on client to avoid unnecessary
				calls.
			</p>
			<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
				<div class="glass rounded-xl p-4">
					<p class="text-gray-400">Total Cap</p>
					<p class="text-xl font-semibold">
						{formatCompactCurrency(filtered[0]?.market_cap ?? 0)}
					</p>
				</div>
				<div class="glass rounded-xl p-4">
					<p class="text-gray-400">24h Average</p>
					<p class="text-xl font-semibold">
						{formatPercent(
							(filtered.reduce((acc, c) => acc + (c.price_change_percentage_24h ?? 0), 0) ?? 0) /
								(filtered.length || 1)
						)}
					</p>
				</div>
				<div class="glass rounded-xl p-4">
					<p class="text-gray-400">Listed</p>
					<p class="text-xl font-semibold">{filtered.length}</p>
				</div>
			</div>
		</div>
	</section>

	<section class="card p-6 overflow-hidden">
		<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
			<div>
				<p class="text-sm text-gray-400">Live Market</p>
				<h2 class="text-2xl font-bold">Top Capitalization</h2>
			</div>
			<div class="text-sm text-gray-400">Data in USD • Automatically updates from CoinGecko</div>
		</div>
		{#if loading}
			<div class="flex justify-center py-10">
				<div
					class="h-12 w-12 border-2 border-primary border-t-transparent rounded-full animate-spin"
				></div>
			</div>
		{:else}
			<div class="overflow-x-auto">
				<table class="w-full text-left text-sm">
					<thead class="text-gray-400 uppercase tracking-wide">
						<tr class="border-b border-white/10">
							<th class="py-3">Asset</th>
							<th class="py-3">Price</th>
							<th class="py-3">24h</th>
							<th class="py-3">Market Cap</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-white/5">
						{#each filtered as coin}
							<tr
								class="hover:bg-white/5 cursor-pointer transition"
								on:click={() => goto(`/coin/${coin.id}`)}
							>
								<td class="py-3">
									<div class="flex items-center space-x-3">
										<img src={coin.image} alt={coin.name} class="h-8 w-8 rounded-full" />
										<div>
											<p class="font-semibold">{coin.name}</p>
											<p class="text-xs text-gray-400 uppercase">{coin.symbol}</p>
										</div>
									</div>
								</td>
								<td class="py-3 font-mono">{formatCurrency(coin.current_price)}</td>
								<td class="py-3">
									<span
										class={`px-3 py-1 rounded-full text-xs font-semibold ${
											coin.price_change_percentage_24h >= 0
												? 'bg-emerald-500/10 text-emerald-400'
												: 'bg-rose-500/10 text-rose-400'
										}`}
									>
										{formatPercent(coin.price_change_percentage_24h)}
									</span>
								</td>
								<td class="py-3 text-gray-300">{formatNumber(coin.market_cap)}</td>
							</tr>
						{/each}
					</tbody>
				</table>
			</div>
		{/if}
	</section>
</div>
