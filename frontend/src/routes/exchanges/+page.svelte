<script lang="ts">
	import { onMount } from 'svelte';
	import api from '$lib/services/api';

	let exchanges: any[] = [];
	let loading = true;

	onMount(async () => {
		try {
			const response = await api.get('/exchanges');
			exchanges = response.data;
		} catch (error) {
			console.error('Failed to fetch exchanges', error);
		} finally {
			loading = false;
		}
	});
</script>

<div class="space-y-6">
	<div class="text-center py-10">
		<h1
			class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary mb-4"
		>
			Top Exchanges
		</h1>
		<p class="text-gray-400 text-lg">Trusted crypto exchanges by volume</p>
	</div>

	{#if loading}
		<div class="flex justify-center py-20">
			<div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary"></div>
		</div>
	{:else}
		<div class="overflow-x-auto bg-dark-lighter rounded-2xl shadow-xl border border-gray-700">
			<table class="w-full text-left">
				<thead class="bg-dark/50 text-gray-400 uppercase text-sm">
					<tr>
						<th class="p-6">Rank</th>
						<th class="p-6">Exchange</th>
						<th class="p-6">Trust Score</th>
						<th class="p-6 text-right">24h Volume (BTC)</th>
						<th class="p-6 text-right">Est. Year</th>
					</tr>
				</thead>
				<tbody class="divide-y divide-gray-700">
					{#each exchanges as exchange}
						<tr class="hover:bg-white/5 transition">
							<td class="p-6 font-mono text-gray-500">#{exchange.trust_score_rank}</td>
							<td class="p-6">
								<div class="flex items-center space-x-4">
									<img src={exchange.image} alt={exchange.name} class="w-8 h-8 rounded-full" />
									<a
										href={exchange.url}
										target="_blank"
										rel="noopener noreferrer"
										class="font-bold hover:text-primary transition"
									>
										{exchange.name}
									</a>
								</div>
							</td>
							<td class="p-6">
								<div class="flex items-center space-x-2">
									<div class="w-24 h-2 bg-gray-700 rounded-full overflow-hidden">
										<div
											class="h-full rounded-full transition-all duration-500"
											style="width: {exchange.trust_score *
												10}%; background-color: {exchange.trust_score >= 8
												? '#10b981'
												: exchange.trust_score >= 5
													? '#f59e0b'
													: '#ef4444'}"
										></div>
									</div>
									<span class="font-mono text-sm">{exchange.trust_score}/10</span>
								</div>
							</td>
							<td class="p-6 text-right font-mono">
								{exchange.trade_volume_24h_btc.toFixed(2)} BTC
							</td>
							<td class="p-6 text-right text-gray-400">
								{exchange.year_established || 'N/A'}
							</td>
						</tr>
					{/each}
				</tbody>
			</table>
		</div>
	{/if}
</div>
