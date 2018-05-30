import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { UserService } from './shared';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html'
})
export class AppComponent implements OnInit {
  constructor (
    private router: Router,
    private userService: UserService
  ) {}

  ngOnInit() {
    this.userService.populate();    
  }
}
