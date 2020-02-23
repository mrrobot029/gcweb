import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private loggedInStatus = false;

  constructor() { }

  setUserLoggedIn(value: boolean) {
    this.loggedInStatus = value;
  }

  isUserLoggedIn(): boolean {
    return this.loggedInStatus;
  }

}
