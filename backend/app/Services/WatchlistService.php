<?php

namespace App\Services;

use App\DataTransferObjects\MarketFilterData;
use App\Models\User;
use App\Services\Contracts\CoinMarketDataProvider;

class WatchlistService
{
    public function __construct(private readonly CoinMarketDataProvider $marketDataProvider)
    {
    }

    public function list(User $user)
    {
        return $user->watchlist()->orderByDesc('created_at')->get(['id', 'coin_id', 'created_at']);
    }

    public function add(User $user, string $coinId)
    {
        return $user->watchlist()->firstOrCreate(['coin_id' => $coinId]);
    }

    public function remove(User $user, string $coinId): bool
    {
        return (bool) $user->watchlist()->where('coin_id', $coinId)->delete();
    }

    /**
     * Resolve market data for the current user watchlist using CoinGecko in one request when possible.
     *
     * @return array<int, mixed>
     */
    public function marketData(User $user): array
    {
        $ids = $user->watchlist()->pluck('coin_id')->all();

        if (empty($ids)) {
            return [];
        }

        $filter = new MarketFilterData(
            ids: $ids,
            perPage: count($ids),
            page: 1,
        );

        return $this->marketDataProvider->listMarkets($filter);
    }
}
