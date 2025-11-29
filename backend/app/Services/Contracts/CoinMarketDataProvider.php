<?php

namespace App\Services\Contracts;

use App\DataTransferObjects\MarketFilterData;

interface CoinMarketDataProvider
{
    /**
     * Return market data for a collection of coins.
     *
     * @return array<int, mixed>
     */
    public function listMarkets(MarketFilterData $filter): array;

    /**
     * Return a single coin enriched data set or null if not found.
     */
    public function getCoin(string $id): ?array;
}
