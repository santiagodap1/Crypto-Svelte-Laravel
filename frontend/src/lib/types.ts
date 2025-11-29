export type CoinMarket = {
	id: string;
	name: string;
	symbol: string;
	image: string;
	current_price: number;
	price_change_percentage_24h: number;
	market_cap: number;
};

export type CoinDetail = {
	id: string;
	name: string;
	symbol: string;
	description: { en: string };
	image: { large: string; small: string; thumb: string };
	market_data: {
		current_price: { usd: number };
		high_24h: { usd: number };
		low_24h: { usd: number };
		ath: { usd: number };
		market_cap: { usd: number };
		price_change_percentage_24h: number;
	};
};

export type WatchlistItem = {
	id: number;
	coin_id: string;
	created_at: string;
};

export type MarketChart = {
	prices: [number, number][];
	market_caps: [number, number][];
	total_volumes: [number, number][];
};
