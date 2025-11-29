<script lang="ts">
	import { onDestroy, onMount } from 'svelte';
	import { page } from '$app/stores';
	import {
		addToWatchlist,
		fetchWatchlist,
		removeFromWatchlist
	} from '$lib/services/watchlistService';
	import { getCoin, getMarketChart } from '$lib/services/coinService';
	import { auth, type AuthState } from '$lib/stores/auth';
	import type { CoinDetail } from '$lib/types';
	import { formatCurrency, formatNumber, formatPercent } from '$lib/utils/format';
	import api from '$lib/services/api'; // Added for chart data
	import Chart from 'chart.js/auto'; // Added for chart

	let coin: CoinDetail | null = null;
	let loading = true;
	let inWatchlist = false;
	let coinId = '';
	let session: AuthState;
	let error = '';
	let chartCanvas: HTMLCanvasElement; // Added for chart
	let chartInstance: Chart; // Added for chart
	let selectedInterval = '7';
	const intervals = [
		{ label: '1D', value: '1' },
		{ label: '7D', value: '7' },
		{ label: '30D', value: '30' },
		{ label: '1Y', value: '365' },
		{ label: 'Max', value: 'max' }
	];

	const unsubscribe = auth.subscribe(($auth) => (session = $auth));
	onDestroy(() => {
		unsubscribe();
		if (chartInstance) {
			chartInstance.destroy();
		}
	});

	$: coinId = $page.params.id ?? '';

	async function hydrateWatchlist() {
		if (!session?.isAuthenticated) return;
		try {
			const items = (await fetchWatchlist(false)) as any[];
			inWatchlist = items.some((item) => item.coin_id === coinId);
		} catch (e) {
			console.error('Watchlist check failed', e);
		}
	}

	async function toggleWatchlist() {
		if (!session?.isAuthenticated) {
			error = 'Log in to save assets';
			return;
		}

		try {
			if (inWatchlist) {
				await removeFromWatchlist(coinId);
			} else {
				await addToWatchlist(coinId);
			}
			inWatchlist = !inWatchlist;
			error = '';
		} catch (e) {
			error = 'Could not update your watchlist';
		}
	}

	async function loadChartData(days: string) {
		selectedInterval = days;
		try {
			const chartRes = await getMarketChart(coinId, days);
			if (chartCanvas) {
				initChart(chartRes.prices);
			}
		} catch (e) {
			console.error('Failed to load chart data', e);
		}
	}

	onMount(async () => {
		if (!coinId) {
			loading = false;
			return;
		}

		try {
			const [coinRes, chartRes] = await Promise.all([
				getCoin(coinId),
				getMarketChart(coinId),
				hydrateWatchlist()
			]);
			coin = coinRes;

			// Initialize chart
			setTimeout(() => {
				if (chartCanvas) {
					initChart(chartRes.prices);
				}
			}, 0);
		} catch (err) {
			error = 'Could not fetch this asset';
		} finally {
			loading = false;
		}
	});

	function initChart(prices: [number, number][]) {
		if (chartInstance) chartInstance.destroy();

		const ctx = chartCanvas.getContext('2d');
		if (!ctx) return;

		// Create gradient
		const gradient = ctx.createLinearGradient(0, 0, 0, 400);
		gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
		gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

		chartInstance = new Chart(chartCanvas, {
			type: 'line',
			data: {
				labels: prices.map((p) => new Date(p[0]).toLocaleDateString()),
				datasets: [
					{
						label: 'Price',
						data: prices.map((p) => p[1]),
						borderColor: '#10B981',
						backgroundColor: gradient,
						borderWidth: 2,
						fill: true,
						tension: 0.4,
						pointRadius: 0,
						pointHoverRadius: 4
					}
				]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: {
						display: false
					},
					tooltip: {
						mode: 'index',
						intersect: false,
						callbacks: {
							label: function (context: any) {
								return formatCurrency(context.parsed.y);
							}
						}
					}
				},
				scales: {
					x: {
						display: false,
						grid: {
							display: false
						}
					},
					y: {
						display: false,
						grid: {
							display: false
						}
					}
				},
				interaction: {
					mode: 'nearest',
					axis: 'x',
					intersect: false
				}
			}
		});
	}
</script>

{#if loading}
	<div class="flex justify-center py-20">
		<div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary"></div>
	</div>
{:else if coin}
	<div class="max-w-5xl mx-auto space-y-8">
		<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
			<!-- Main Info & Chart -->
			<div class="lg:col-span-2 space-y-6">
				<div class="bg-dark-lighter p-6 rounded-2xl border border-gray-700 shadow-xl">
					<div
						class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6"
					>
						<div class="flex items-center space-x-4">
							<img src={coin.image.large} alt={coin.name} class="w-16 h-16 rounded-full" />
							<div>
								<h1 class="text-3xl font-bold">{coin.name}</h1>
								<span class="text-gray-400 uppercase">{coin.symbol}</span>
							</div>
						</div>
						<div class="text-left sm:text-right">
							<div class="text-3xl font-bold">
								${coin.market_data.current_price.usd.toLocaleString()}
							</div>
							<div
								class={`text-lg ${coin.market_data.price_change_percentage_24h >= 0 ? 'text-green-400' : 'text-red-400'}`}
							>
								{coin.market_data.price_change_percentage_24h.toFixed(2)}% (24h)
							</div>
						</div>
					</div>

					<!-- Chart Controls -->
					<div class="flex space-x-2 mb-4">
						{#each intervals as interval}
							<button
								class={`px-3 py-1 rounded-lg text-sm font-medium transition-colors ${
									selectedInterval === interval.value
										? 'bg-primary text-white'
										: 'bg-gray-700 text-gray-300 hover:bg-gray-600'
								}`}
								on:click={() => loadChartData(interval.value)}
							>
								{interval.label}
							</button>
						{/each}
					</div>

					<!-- Chart Container -->
					<div class="h-80 w-full">
						<canvas bind:this={chartCanvas}></canvas>
					</div>
				</div>

				<!-- Description -->
				<div class="bg-dark-lighter p-6 rounded-2xl border border-gray-700 shadow-xl">
					<h2 class="text-xl font-bold mb-4">About {coin.name}</h2>
					<div class="prose prose-invert max-w-none text-gray-300">
						{@html coin.description.en || 'No description available.'}
					</div>
				</div>
			</div>
			<!-- Sidebar (Market Data & Watchlist) -->
			<div>
				<div class="card p-5 mb-4">
					<p class="text-gray-400 text-sm">Price</p>
					<p class="text-3xl font-mono">{formatCurrency(coin.market_data.current_price.usd)}</p>
					<p
						class={`text-sm mt-2 ${
							coin.market_data.price_change_percentage_24h >= 0
								? 'text-emerald-400'
								: 'text-rose-400'
						}`}
					>
						{formatPercent(coin.market_data.price_change_percentage_24h)} (24h)
					</p>
				</div>
				<div class="card p-5 mb-4">
					<p class="text-gray-400 text-sm">Market Cap</p>
					<p class="text-2xl font-semibold">{formatNumber(coin.market_data.market_cap.usd)}</p>
				</div>
				<div class="card p-5 mb-4">
					<p class="text-gray-400 text-sm">24h High</p>
					<p class="text-2xl font-semibold">{formatCurrency(coin.market_data.high_24h.usd)}</p>
				</div>
				<div class="card p-5 mb-4">
					<p class="text-gray-400 text-sm">ATH</p>
					<p class="text-2xl font-semibold">{formatCurrency(coin.market_data.ath.usd)}</p>
				</div>
				<div class="card p-5">
					{#if error}
						<span class="text-sm text-rose-400 block mb-2">{error}</span>
					{/if}
					<button
						on:click={toggleWatchlist}
						class={`w-full px-6 py-3 rounded-xl border text-sm font-semibold transition ${
							inWatchlist
								? 'bg-rose-500/10 border-rose-500 text-rose-300'
								: 'bg-primary/10 border-primary text-primary'
						}`}
					>
						{inWatchlist ? 'Remove from watchlist' : 'Add to watchlist'}
					</button>
				</div>
			</div>
		</div>
	</div>
{:else}
	<div class="text-center py-20 text-rose-400">We could not find this coin.</div>
{/if}
