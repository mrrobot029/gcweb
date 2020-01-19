import { Component, OnInit } from '@angular/core';
import { DataService } from '../../../services/data.service'

@Component({
  selector: 'app-student-prospectus',
  templateUrl: './student-prospectus.component.html',
  styleUrls: ['./student-prospectus.component.scss']
})
export class StudentProspectusComponent implements OnInit {

  credStud: any = {}
  classStud: any = {}
  studInfo: any = {}
  studProspectus: any = {}
  studGrades: any = {}

  constructor(private ds: DataService) { }

  ngOnInit() {
    this.credStud = JSON.parse(localStorage.getItem('gcweb_student'));
    this.studInfo['si_idnumber'] = this.credStud.data[0].si_idnumber
    this.studInfo['si_course'] = this.credStud.data[0].si_course
    this.getProspectusYrSem(1, 1) 
    this.ds.sendRequest('getGrades', this.studInfo).subscribe((res) => {
      this.studGrades = res;
    });
  }

  getProspectusYrSem(yr, sem){
    
    if(yr != 0){
      this.studInfo['year'] = yr
    }
      this.studInfo['sem'] = sem
      console.log(this.studInfo)
      this.ds.sendRequest('getProspectusCopyF', this.studInfo).subscribe((res) => {
        this.studProspectus = res;
      });
    
  }

}
