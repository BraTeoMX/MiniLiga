import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Api, Match, Team } from '../../services/api';

@Component({
  selector: 'app-matches',
  imports: [ReactiveFormsModule, CommonModule],
  templateUrl: './matches.html',
  styleUrl: './matches.css',
})
export class Matches implements OnInit {
  matches: Match[] = [];
  teams: Team[] = [];
  selectedMatch: Match | null = null;
  resultForm: FormGroup;
  createMatchForm: FormGroup;
  loading = false;
  error: string | null = null;
  showCreateForm = false;

  constructor(private api: Api, private fb: FormBuilder) {
    this.resultForm = this.fb.group({
      home_score: ['', [Validators.required, Validators.min(0)]],
      away_score: ['', [Validators.required, Validators.min(0)]]
    });

    this.createMatchForm = this.fb.group({
      home_team_id: ['', Validators.required],
      away_team_id: ['', Validators.required]
    });
  }

  ngOnInit() {
    this.loadPendingMatches();
    this.loadTeams();
  }

  loadTeams() {
    this.api.getTeams().subscribe({
      next: (teams) => {
        this.teams = teams;
      },
      error: (err) => {
        console.error('Error loading teams:', err);
      }
    });
  }

  loadPendingMatches() {
    this.loading = true;
    this.api.getPendingMatches().subscribe({
      next: (matches) => {
        this.matches = matches;
        this.loading = false;
      },
      error: (err) => {
        this.error = 'Error loading matches';
        this.loading = false;
        console.error('Error loading matches:', err);
      }
    });
  }

  selectMatch(match: Match) {
    this.selectedMatch = match;
    this.resultForm.reset();
  }

  onSubmitResult() {
    if (this.resultForm.valid && this.selectedMatch) {
      this.loading = true;
      this.error = null;

      this.api.reportResult(this.selectedMatch.id, this.resultForm.value).subscribe({
        next: (updatedMatch) => {
          // Remove the match from the list since it's now played
          this.matches = this.matches.filter(m => m.id !== this.selectedMatch!.id);
          this.selectedMatch = null;
          this.resultForm.reset();
          this.loading = false;
        },
        error: (err) => {
          this.error = 'Error reporting result';
          this.loading = false;
          console.error('Error reporting result:', err);
        }
      });
    }
  }

  cancelResult() {
    this.selectedMatch = null;
    this.resultForm.reset();
  }

  toggleCreateForm() {
    this.showCreateForm = !this.showCreateForm;
    if (!this.showCreateForm) {
      this.createMatchForm.reset();
    }
  }

  onCreateMatch() {
    if (this.createMatchForm.valid) {
      this.loading = true;
      this.error = null;

      this.api.createMatch(this.createMatchForm.value).subscribe({
        next: (newMatch) => {
          // Add the new match to the list
          this.matches.push(newMatch);
          this.showCreateForm = false;
          this.createMatchForm.reset();
          this.loading = false;
        },
        error: (err) => {
          this.error = 'Error creating match';
          this.loading = false;
          console.error('Error creating match:', err);
        }
      });
    }
  }

  cancelCreateMatch() {
    this.showCreateForm = false;
    this.createMatchForm.reset();
  }
}
