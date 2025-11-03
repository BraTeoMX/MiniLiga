<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = ['name', 'goals_for', 'goals_against'];

    public function homeMatches()
    {
        return $this->hasMany(FootballMatch::class, 'home_team_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(FootballMatch::class, 'away_team_id');
    }

    public function matches()
    {
        return $this->homeMatches()->union($this->awayMatches());
    }

    public function updateGoals($goalsFor, $goalsAgainst)
    {
        $this->increment('goals_for', $goalsFor);
        $this->increment('goals_against', $goalsAgainst);
    }

    public function getPlayedAttribute()
    {
        return $this->homeMatches()->whereNotNull('home_score')->count() +
               $this->awayMatches()->whereNotNull('away_score')->count();
    }

    public function getGoalDiffAttribute()
    {
        return $this->goals_for - $this->goals_against;
    }

    public function getPointsAttribute()
    {
        $points = 0;

        // Home matches
        foreach ($this->homeMatches()->whereNotNull('home_score')->get() as $match) {
            if ($match->home_score > $match->away_score) {
                $points += 3; // Win
            } elseif ($match->home_score == $match->away_score) {
                $points += 1; // Draw
            }
        }

        // Away matches
        foreach ($this->awayMatches()->whereNotNull('away_score')->get() as $match) {
            if ($match->away_score > $match->home_score) {
                $points += 3; // Win
            } elseif ($match->away_score == $match->home_score) {
                $points += 1; // Draw
            }
        }

        return $points;
    }
}
