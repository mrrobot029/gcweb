import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-faculty-profile',
  templateUrl: './faculty-profile.component.html',
  styleUrls: ['./faculty-profile.component.scss']
})
export class FacultyProfileComponent implements OnInit {

  accInfo: any = {};
  accounttype = '';

  constructor() { }

  ngOnInit() {
    this.accInfo = JSON.parse(localStorage.getItem('gcweb_faculty'));
    if (this.accInfo.data[0].fa_accounttype === '0') {
      this.accounttype = 'Faculty Member';
    } else if (this.accInfo.data[0].fa_accounttype === '1') {
      this.accounttype = 'Admin';
    } else if (this.accInfo.data[0].fa_accounttype === '2') {
      this.accounttype = 'Coordinator';
    }
  }

}
