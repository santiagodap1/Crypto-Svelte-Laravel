<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ListMarketsRequest;
use App\DataTransferObjects\MarketFilterData;
use App\Services\Contracts\CoinMarketDataProvider;
use App\Services\CoinGeckoService;

class CryptoController extends Controller
{
    public function __construct(
        private readonly CoinMarketDataProvider $marketDataProvider,
        private readonly CoinGeckoService $coinGeckoService
    ) {
    }

    public function index(ListMarketsRequest $request)
    {
        $coins = $this->marketDataProvider->listMarkets(
            MarketFilterData::fromArray($request->validated())
        );

        return response()->json($coins);
    }

    public function show($id)
    {
        $coin = $this->coinGeckoService->getCoinDetails($id);

        if (!$coin) {
            return response()->json(['message' => 'Coin not found'], 404);
        }

        return response()->json($coin);
    }

    public function global()
    {
        return response()->json($this->coinGeckoService->getGlobalData());
    }

    public function trending()
    {
        return response()->json($this->coinGeckoService->getTrending());
    }

    public function chart(Request $request, $id)
    {
        $days = $request->input('days', '7');
        return response()->json($this->coinGeckoService->getMarketChart($id, $days));
    }

    public function exchanges()
    {
        return response()->json($this->coinGeckoService->getExchanges());
    }
}
