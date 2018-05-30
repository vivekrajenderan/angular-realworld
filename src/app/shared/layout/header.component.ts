import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { User } from '../models';
import { UserService } from '../services';

@Component({
  selector: 'layout-header',
  templateUrl: './header.component.html'
})
export class HeaderComponent implements OnInit {
  constructor(
    private router: Router,
    private userService: UserService
  ) {}

  currentUser: User;

  ngOnInit() {  
    
    this.userService.currentUser.subscribe(
      (userData) => {
        this.currentUser = userData;        
        if(this.isEmpty(this.currentUser))
        {
          this.router.navigateByUrl('/');
        }
      }
    )
  }
  logout() {
    this.userService.purgeAuth();
    this.router.navigateByUrl('/login');
  }

  isEmpty(obj) {
    if((obj && (Object.keys(obj).length === 0)))
    {
     return false;
    }
    return true;
 }
}
