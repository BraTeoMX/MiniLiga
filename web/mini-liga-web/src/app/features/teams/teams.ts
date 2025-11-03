import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Api, Team } from '../../services/api';

@Component({
  selector: 'app-teams',
  imports: [ReactiveFormsModule, CommonModule],
  templateUrl: './teams.html',
  styleUrl: './teams.css',
})
export class Teams implements OnInit {
  teams: Team[] = [];
  teamForm: FormGroup;
  loading = false;
  error: string | null = null;

  constructor(private api: Api, private fb: FormBuilder) {
    this.teamForm = this.fb.group({
      name: ['', [Validators.required, Validators.minLength(2)]]
    });
  }

  ngOnInit() {
    this.loadTeams();
  }

  loadTeams() {
    this.loading = true;
    this.api.getTeams().subscribe({
      next: (teams) => {
        this.teams = teams;
        this.loading = false;
      },
      error: (err) => {
        this.error = 'Error loading teams';
        this.loading = false;
        console.error('Error loading teams:', err);
      }
    });
  }

  onSubmit() {
    if (this.teamForm.valid) {
      this.loading = true;
      this.error = null;
      this.api.createTeam(this.teamForm.value).subscribe({
        next: (newTeam) => {
          this.teams.push(newTeam);
          this.teamForm.reset();
          this.loading = false;
        },
        error: (err) => {
          this.error = 'Error creating team';
          this.loading = false;
          console.error('Error creating team:', err);
        }
      });
    }
  }
}
