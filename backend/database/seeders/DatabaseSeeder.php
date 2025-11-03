<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\FootballMatch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $teams = collect(['Dragons', 'Sharks', 'Tigers', 'Wolves'])
            ->map(fn($name) => Team::create(['name' => $name]));

        // Create 2 matches without results
        FootballMatch::create([
            'home_team_id' => $teams[0]->id,
            'away_team_id' => $teams[1]->id
        ]);
        FootballMatch::create([
            'home_team_id' => $teams[2]->id,
            'away_team_id' => $teams[3]->id
        ]);
    }
}
