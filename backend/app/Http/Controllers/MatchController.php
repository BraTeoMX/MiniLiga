<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MatchController extends Controller
{
    /**
     * Display a listing of pending matches.
     */
    public function index(Request $request): JsonResponse
    {
        $query = FootballMatch::with(['homeTeam', 'awayTeam']);

        // Filter by played status if specified
        if ($request->has('played')) {
            $played = $request->boolean('played');
            if ($played) {
                $query->whereNotNull('home_score')->whereNotNull('away_score');
            } else {
                $query->whereNull('home_score')->whereNull('away_score');
            }
        }

        return response()->json($query->get());
    }

    /**
     * Store result for a specific match.
     */
    public function storeResult(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0'
        ]);

        $match = FootballMatch::findOrFail($id);

        if ($match->isPlayed()) {
            return response()->json(['error' => 'Match already has a result'], 400);
        }

        $match->recordResult($request->home_score, $request->away_score);

        return response()->json($match->load(['homeTeam', 'awayTeam']));
    }
}
