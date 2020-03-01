import { Component, OnInit } from '@angular/core';
import { DataService } from '../services/data.service';
import { Router } from '@angular/router'
import { Storage } from '@ionic/storage';

@Component({
  selector: 'app-prospectus',
  templateUrl: './prospectus.page.html',
  styleUrls: ['./prospectus.page.scss'],
})
export class ProspectusPage implements OnInit {

 
  constructor(private storage: Storage,
    private ds: DataService) { }

    studentInfo: any = {};
    studGrades: any = {};
    prospectus: any = {};
  

  ngOnInit() {
    this.yearlvl(1);
  }
  

  yearlvl(e:number){
    this.ds.year = e;
    console.log(this.ds.year)
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
