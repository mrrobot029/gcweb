import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-student-profile',
  templateUrl: './student-profile.component.html',
  styleUrls: ['./student-profile.component.scss']
})
export class StudentProfileComponent implements OnInit {

  credStud: any = {}

  constructor() { }

  ngOnInit() {
    this.credStud = JSON.parse(localStorage.getItem('gcweb_student'));
  }

}
