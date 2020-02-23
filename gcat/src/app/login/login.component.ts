import { Component, OnInit } from '@angular/core';

import { DataService } from '../services/data.service';
import { Router } from '@angular/router';
import { AuthService } from '../services/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  loginData: any = {};
  credGCAT: any = {};


  constructor(private ds: DataService, private router: Router, private auth: AuthService) { }

  ngOnInit() {
    this.credGCAT = JSON.parse(localStorage.getItem('gcweb_GCAT'));
    this.loginData.payload = this.credGCAT.payload ? this.credGCAT.payload : null;
    if (this.credGCAT !== null) {
      this.ds.sendRequest('checkGCATmember', this.loginData).subscribe((res) => {
        if (res.status.remarks) {
          this.auth.setUserLoggedIn(true);
          this.router.navigate(['admin']);
        } else {
          localStorage.removeItem('gcweb_GCAT');
        }
      });
    }
  }

  logIn(e) {
    e.preventDefault();

    this.loginData.username = e.target[0].value;
    this.loginData.password = e.target[1].value;

    this.ds.sendRequest('loginGCATmember', this.loginData).subscribe((res) => {
      if (res.status.remarks) {

        this.ds.callSwal(
          'Login success!',
          'User verification success. Please proceed.',
          'success',
        );

        this.auth.setUserLoggedIn(true);
        localStorage.setItem('gcweb_GCAT', JSON.stringify(res));
        this.router.navigate(['admin']);

      } else {

        this.ds.callSwal(
          'Login failed!',
          'User verification failed. Invalid username or password.',
          'error',
        );

      }
    });
  }

}
