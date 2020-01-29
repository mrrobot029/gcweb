import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-faculty-sidebar',
  templateUrl: './faculty-sidebar.component.html',
  styleUrls: ['./faculty-sidebar.component.scss']
})
export class FacultySidebarComponent implements OnInit {

  FacultyInfo: any = {};
  prog: any;

  constructor() { }

  ngOnInit() {
    this.FacultyInfo = JSON.parse(localStorage.getItem('gcweb_faculty'));
    this.prog = this.FacultyInfo.data[0].fa_accounttype;
  }

}
