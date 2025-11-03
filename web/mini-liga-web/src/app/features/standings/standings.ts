import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Api, Standing } from '../../services/api';

@Component({
  selector: 'app-standings',
  imports: [CommonModule],
  templateUrl: './standings.html',
  styleUrl: './standings.css',
})
export class Standings implements OnInit {
  standings: Standing[] = [];
  loading = false;
  error: string | null = null;

  constructor(private api: Api) {}

  ngOnInit() {
    this.loadStandings();
  }

  loadStandings() {
    this.loading = true;
    this.error = null;
    this.api.getStandings().subscribe({
      next: (standings) => {
        this.standings = standings;
        this.loading = false;
      },
      error: (err) => {
        this.error = 'Error loading standings';
        this.loading = false;
        console.error('Error loading standings:', err);
      }
    });
  }

  refreshStandings() {
    this.loadStandings();
  }
}
