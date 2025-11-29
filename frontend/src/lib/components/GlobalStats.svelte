<script lang="ts">
	import { onMount } from 'svelte';
	import api from '$lib/services/api';

	let globalData: any = null;

	onMount(async () => {
		try {
			const response = await api.get('/global');
			globalData = response.data.data;
		} catch (error) {
			console.error('Failed to fetch global data', error);
		}
	});
</script>

{#if globalData}
	<div class="bg-primary/10 text-xs py-2 border-b border-primary/20">
		<div
			class="container mx-auto px-4 flex flex-wrap gap-4 justify-center md:justify-start text-gray-300"
		>
			<div>
				<span class="text-gray-500">Coins:</span>
				<span class="text-primary font-bold"
					>{globalData.active_cryptocurrencies.toLocaleString()}</span
				>
			</div>
			<div>
				<span class="text-gray-500">Exchanges:</span>
				<span class="text-primary font-bold">{globalData.markets.toLocaleString()}</span>
			</div>
			<div>
				<span class="text-gray-500">Market Cap:</span>
				<span class="text-primary font-bold"
					>${Math.floor(globalData.total_market_cap.usd).toLocaleString()}</span
				>
			</div>
			<div>
				<span class="text-gray-500">24h Vol:</span>
				<span class="text-primary font-bold"
					>${Math.floor(globalData.total_volume.usd).toLocaleString()}</span
				>
			</div>
			<div>
				<span class="text-gray-500">BTC Dom:</span>
				<span class="text-primary font-bold"
					>{globalData.market_cap_percentage.btc.toFixed(1)}%</span
				>
			</div>
		</div>
	</div>
{/if}
