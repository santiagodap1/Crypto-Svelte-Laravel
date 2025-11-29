import api from './api';
import type { CoinDetail, CoinMarket } from '../types';

type MarketQuery = {
	vs_currency?: string;
	per_page?: number;
	page?: number;
	ids?: string;
};

export async function getMarkets(params?: MarketQuery): Promise<CoinMarket[]> {
	const { data } = await api.get<CoinMarket[]>('/coins/markets', {
		params: {
			per_page: 30,
			...params
		}
	});

	return data;
}

export async function getCoin(id: string): Promise<CoinDetail> {
	const { data } = await api.get<CoinDetail>(`/coins/${id}`);
	return data;
}

export async function getTrending() {
	const { data } = await api.get('/trending');
	return data.coins.map((c: any) => c.item);
}

export async function getMarketChart(id: string, days = '7'): Promise<{ prices: [number, number][] }> {
	const { data } = await api.get<{ prices: [number, number][] }>(`/coins/${id}/chart`, {
		params: { days }
	});
	return data;
}
