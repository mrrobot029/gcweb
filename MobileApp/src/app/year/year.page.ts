import { Component, OnInit } from '@angular/core';
import { DataService } from '../services/data.service';
import { Storage } from '@ionic/storage';

@Component({
  selector: 'app-year',
  templateUrl: './year.page.html',
  styleUrls: ['./year.page.scss'],
})
export class YearPage implements OnInit {

  studentInfo: any = {};
  studGrades: any = {};
  prospectus: any = {};

  constructor(
    private storage: Storage,
    private ds: DataService
    ) { }

  ngOnInit() {
    this.storage.get('studentInfo').then((val) => {
      console.log(val.si_idnumber);

      this.studentInfo.si_idnumber  = val.si_idnumber;
      this.studentInfo.si_course = val.si_course;
      this.studentInfo.si_cy = val.si_cy;
      this.studentInfo.year = this.ds.year;

      this.ds.sendrequest('getProspectusByYr', this.studentInfo).subscribe((res) => {
        this.prospectus = res;
        console.log(this.prospectus);
      });
      this.ds.sendrequest('getGrades', this.studentInfo).subscribe((res) => {
        this.studGrades = res;
        console.log(this.studGrades);
      });
    });
  }
  

}
