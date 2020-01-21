import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-student-profile',
  templateUrl: './student-profile.component.html',
  styleUrls: ['./student-profile.component.scss']
})
export class StudentProfileComponent implements OnInit {

  credStud: any = {}
  age: number

  constructor() { }

  ngOnInit() {
    this.credStud = JSON.parse(localStorage.getItem('gcweb_student'));
    this.computeAge();
  }

  computeAge(){
    var timeDiff = Math.abs(Date.now() - new Date(this.credStud.data[0].si_bday).getTime());
    this.age = Math.floor(timeDiff / (1000 * 3600 * 24) / 365.25);
    if(this.age<5||this.age>100){
      alert('Invalid Birthdate!');
      this.age = 0;
    } else{
      this.age = this.age;
    }
  }

}
