import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

export interface Team {
  id: number;
  name: string;
  goals_for: number;
  goals_against: number;
  created_at: string;
  updated_at: string;
}

export interface Standing {
  team: string;
  played: number;
  goals_for: number;
  goals_against: number;
  goal_diff: number;
  points: number;
}

export interface Match {
  id: number;
  home_team_id: number;
  away_team_id: number;
  home_score: number | null;
  away_score: number | null;
  played_at: string | null;
  created_at: string;
  updated_at: string;
  home_team?: Team;
  away_team?: Team;
}

@Injectable({
  providedIn: 'root',
})
export class Api {
  private baseUrl = environment.API_URL;

  constructor(private http: HttpClient) {}

  getTeams(): Observable<Team[]> {
    return this.http.get<Team[]>(`${this.baseUrl}/teams`);
  }

  createTeam(payload: { name: string }): Observable<Team> {
    return this.http.post<Team>(`${this.baseUrl}/teams`, payload);
  }

  getStandings(): Observable<Standing[]> {
    return this.http.get<Standing[]>(`${this.baseUrl}/standings`);
  }

  getPendingMatches(): Observable<Match[]> {
    return this.http.get<Match[]>(`${this.baseUrl}/matches?played=false`);
  }

  reportResult(id: number, payload: { home_score: number; away_score: number }): Observable<Match> {
    return this.http.post<Match>(`${this.baseUrl}/matches/${id}/result`, payload);
  }
}
