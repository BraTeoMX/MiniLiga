<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StandingsController extends Controller
{
    /**
     * Display the standings.
     */
    public function index(): JsonResponse
    {
        $standings = Team::all()->map(function ($team) {
            return [
                'team' => $team->name,
                'played' => $team->played,
                'goals_for' => $team->goals_for,
                'goals_against' => $team->goals_against,
                'goal_diff' => $team->goal_diff,
                'points' => $team->points,
            ];
        })->sortByDesc('points')
          ->sortByDesc('goal_diff')
          ->sortByDesc('goals_for')
          ->values();

        return response()->json($standings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
