import { Component, OnInit } from '@angular/core';
import { DataService } from '../services/data.service';
import { Storage } from '@ionic/storage';

@Component({
  selector: 'app-sched',
  templateUrl: './sched.page.html',
  styleUrls: ['./sched.page.scss'],
})
export class SchedPage implements OnInit {

  studentInfo: any = {};
  scheds: any = {};

  constructor(private ds: DataService, private storage: Storage) { }

  ngOnInit() {
    this.storage.get('studentInfo').then((res) => {
      console.log(res.si_idnumber);

      this.studentInfo.si_idnumber  = res.si_idnumber;

      this.ds.sendrequest('getStudentSchedule', this.studentInfo).subscribe((res1) => {
        this.scheds = res1;
        console.log(this.scheds);
      });
    });
  }

}
