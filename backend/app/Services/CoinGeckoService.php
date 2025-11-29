<?php

namespace App\Services;

use App\DataTransferObjects\MarketFilterData;
use App\Services\Contracts\CoinMarketDataProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CoinGeckoService implements CoinMarketDataProvider
{
    protected $baseUrl = 'https://api.coingecko.com/api/v3';

    /**
     * Get top coins (markets)
     *
     * @param string $vsCurrency
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function getCoinsMarkets($vsCurrency = 'usd', $perPage = 10, $page = 1)
    {
        $filter = new MarketFilterData(
            vsCurrency: $vsCurrency,
            perPage: $perPage,
            page: $page,
        );

        return $this->listMarkets($filter);
    }

    public function listMarkets(MarketFilterData $filter): array
    {
        $cacheKey = "coins_markets_{$filter->cacheKey()}";

        return Cache::remember($cacheKey, 60, function () use ($filter) {
            $response = Http::get("{$this->baseUrl}/coins/markets", $filter->toQueryParams());

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        });
    }

    /**
     * Get coin details by ID
     *
     * @param string $id
     * @return array
     */
    public function getCoinDetails($id)
    {
        return $this->getCoin($id);
    }

    public function getCoin(string $id): ?array
    {
        $cacheKey = "coin_details_{$id}";

        return Cache::remember($cacheKey, 300, function () use ($id) {
            $response = Http::get("{$this->baseUrl}/coins/{$id}", [
                'localization' => 'false',
                'tickers' => 'false',
                'market_data' => 'true',
                'community_data' => 'false',
                'developer_data' => 'false',
                'sparkline' => 'true'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        });
    }
    /**
     * Get global crypto data
     *
     * @return array
     */
    public function getGlobalData()
    {
        return Cache::remember('global_data', 300, function () {
            $response = Http::get("{$this->baseUrl}/global");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        });
    }

    /**
     * Get trending coins
     *
     * @return array
     */
    public function getTrending()
    {
        return Cache::remember('trending_coins', 300, function () {
            $response = Http::get("{$this->baseUrl}/search/trending");

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        });
    }

    /**
     * Get market chart data
     *
     * @param string $id
     * @param string $days
     * @return array
     */
    public function getMarketChart($id, $days = '7')
    {
        $cacheKey = "market_chart_{$id}_{$days}";

        return Cache::remember($cacheKey, 300, function () use ($id, $days) {
            $response = Http::get("{$this->baseUrl}/coins/{$id}/market_chart", [
                'vs_currency' => 'usd',
                'days' => $days,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        });
    }

    /**
     * Get top exchanges
     *
     * @return array
     */
    public function getExchanges()
    {
        return Cache::remember('exchanges', 300, function () {
            $response = Http::get("{$this->baseUrl}/exchanges", [
                'per_page' => 10,
                'page' => 1,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        });
    }
}
