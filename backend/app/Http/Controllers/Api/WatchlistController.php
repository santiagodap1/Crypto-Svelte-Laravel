<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreWatchlistRequest;
use App\Services\WatchlistService;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function __construct(private readonly WatchlistService $watchlistService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $items = $this->watchlistService->list($user);

        if ($request->boolean('with_markets')) {
            $markets = $this->watchlistService->marketData($user);

            return response()->json([
                'items' => $items,
                'markets' => $markets,
            ]);
        }

        return response()->json($items);
    }

    public function store(StoreWatchlistRequest $request)
    {
        $watchlist = $this->watchlistService->add($request->user(), $request->input('coin_id'));

        return response()->json($watchlist, 201);
    }

    public function destroy(Request $request, $coin_id)
    {
        $deleted = $this->watchlistService->remove($request->user(), $coin_id);

        return $deleted
            ? response()->json(['message' => 'Removed from watchlist'])
            : response()->json(['message' => 'Item not found'], 404);
    }
}
