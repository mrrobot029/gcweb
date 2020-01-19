import { Component, OnInit } from '@angular/core';
import { DataService } from '../../../services/data.service';

@Component({
  selector: 'app-student-sched',
  templateUrl: './student-sched.component.html',
  styleUrls: ['./student-sched.component.scss']
})
export class StudentSchedComponent implements OnInit {

  credStud: any = {}
  classStud: any = {}
  classId: any = {}

  constructor(private ds: DataService) { }

  ngOnInit() {
    this.credStud = JSON.parse(localStorage.getItem('gcweb_student'));
    this.classId['si_idnumber'] = this.credStud.data[0].si_idnumber
    this.ds.sendRequest('getStudentSchedule', this.classId).subscribe((res) => {
      this.classStud = res;
      console.log(this.classStud);
    });
  }

}
