import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-faculty-profile',
  templateUrl: './faculty-profile.component.html',
  styleUrls: ['./faculty-profile.component.scss']
})
export class FacultyProfileComponent implements OnInit {

  credAdmin: any = {};

  constructor() { }

  ngOnInit() {
    this.credAdmin = JSON.parse(localStorage.getItem('gcweb_faculty'));
  }

}
