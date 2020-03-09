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
    yearlvn: number;
    yearlv: string;
  

  ngOnInit() {
    this.yearlvl(1);
    
  }
  

  yearlvl(e){
    this.ds.year = e;
    this.yearlvn = e;
    
    this.storage.get('studentInfo').then((val) => {
      this.studentInfo.si_idnumber  = val.si_idnumber;
      this.studentInfo.si_course = val.si_course;
      this.studentInfo.si_cy = val.si_cy;
      this.studentInfo.year = this.ds.year;

      this.ds.sendrequest('getProspectusByYr', this.studentInfo).subscribe((res) => {
        this.prospectus = res;
      });
      this.ds.sendrequest('getGrades', this.studentInfo).subscribe((res) => {
        this.studGrades = res;
      });
    });

    this.findyear(this.yearlvn);
  }

  findyear(e){
    if (e == 1) {
      this.yearlv = "First Year";
    }
    else if (e == 2){
      this.yearlv = "Second Year";
    }
    else if (e == 3){
      this.yearlv = "Third Year";
    }
    else if (e == 4){
      this.yearlv = "Fourth Year";
    }
  }
}
