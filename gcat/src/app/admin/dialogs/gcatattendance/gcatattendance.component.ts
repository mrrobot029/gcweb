import { Component, OnInit, Inject } from '@angular/core';
import {MAT_DIALOG_DATA} from '@angular/material/dialog';

@Component({
  selector: 'app-gcatattendance',
  templateUrl: './gcatattendance.component.html',
  styleUrls: ['./gcatattendance.component.scss']
})
export class GcatattendanceComponent implements OnInit {
  schedules: any = {}
  p = 1
  constructor(@Inject(MAT_DIALOG_DATA) public data: any) { }

  ngOnInit() {
    this.schedules = this.data.filter(d=>{
      return d.sub_count>0;
    })
  }

}
