<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FootballMatch extends Model
{
    protected $table = 'matches';
    protected $fillable = ['home_team_id', 'away_team_id', 'home_score', 'away_score', 'played_at'];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function isPlayed()
    {
        return !is_null($this->home_score) && !is_null($this->away_score);
    }

    public function getResult()
    {
        if (!$this->isPlayed()) {
            return null;
        }

        if ($this->home_score > $this->away_score) {
            return 'home_win';
        } elseif ($this->home_score < $this->away_score) {
            return 'away_win';
        } else {
            return 'draw';
        }
    }

    public function recordResult($homeScore, $awayScore)
    {
        $this->home_score = $homeScore;
        $this->away_score = $awayScore;
        $this->played_at = now();
        $this->save();

        // Update team statistics
        $this->homeTeam->updateGoals($homeScore, $awayScore);
        $this->awayTeam->updateGoals($awayScore, $homeScore);
    }
}
