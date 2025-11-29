import api from './api';
import type { WatchlistItem, CoinMarket } from '../types';

type WatchlistResponse =
	| WatchlistItem[]
	| {
			items: WatchlistItem[];
			markets: CoinMarket[];
	  };

export async function fetchWatchlist(withMarkets = false): Promise<WatchlistResponse> {
	const { data } = await api.get<WatchlistResponse>('/watchlist', {
		params: { with_markets: withMarkets }
	});
	return data;
}

export async function addToWatchlist(coinId: string): Promise<WatchlistItem> {
	const { data } = await api.post<WatchlistItem>('/watchlist', { coin_id: coinId });
	return data;
}

export async function removeFromWatchlist(coinId: string): Promise<void> {
	await api.delete(`/watchlist/${coinId}`);
}
