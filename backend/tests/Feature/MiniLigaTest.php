<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\FootballMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiniLigaTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_teams_and_match_with_results(): void
    {
        // Crear dos equipos
        $team1 = Team::create(['name' => 'Dragons']);
        $team2 = Team::create(['name' => 'Sharks']);

        // Crear un partido
        $match = FootballMatch::create([
            'home_team_id' => $team1->id,
            'away_team_id' => $team2->id,
        ]);

        // Registrar resultado: victoria para Dragons (3-1)
        $this->postJson("/api/matches/{$match->id}/result", [
            'home_score' => 3,
            'away_score' => 1,
        ])->assertStatus(200);

        // Verificar que los puntos se calculan correctamente
        $team1->refresh();
        $team2->refresh();

        $this->assertEquals(3, $team1->points); // Victoria
        $this->assertEquals(0, $team2->points); // Derrota

        // Crear otro partido para empate
        $match2 = FootballMatch::create([
            'home_team_id' => $team2->id,
            'away_team_id' => $team1->id,
        ]);

        // Registrar empate (1-1)
        $this->postJson("/api/matches/{$match2->id}/result", [
            'home_score' => 1,
            'away_score' => 1,
        ])->assertStatus(200);

        // Verificar puntos después del empate
        $team1->refresh();
        $team2->refresh();

        $this->assertEquals(4, $team1->points); // 3 + 1 = 4
        $this->assertEquals(1, $team2->points); // 0 + 1 = 1
    }
}
