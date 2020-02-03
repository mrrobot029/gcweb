import { Component, OnInit } from '@angular/core';

import { DataService } from '../services/data.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  loginCredentials: any = {};

  constructor(private ds: DataService, private router: Router) { }

  ngOnInit() {
  }
}


