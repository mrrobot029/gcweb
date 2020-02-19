import { Component, OnInit } from '@angular/core';
import { DataService } from '../data-service.service';
import {Router} from '@angular/router';

@Component({
  selector: 'app-notavailable2',
  templateUrl: './notavailable2.component.html',
  styleUrls: ['./notavailable2.component.css']
})
export class Notavailable2Component implements OnInit {
  currentDate = new Date();
  enlistmentStart;
  enlistmentEnd;
  constructor(private ds: DataService, private router: Router) { }

  ngOnInit() {
    this.currentDate.setHours(0,0,0,0)
    this.ds.sendRequest('getSettings', '').subscribe((settings)=>{
      this.enlistmentStart = new Date(settings.data[0].en_gcatstart)
      this.enlistmentEnd = new Date(settings.data[0].en_gcatend)
      if(this.currentDate<this.enlistmentStart||this.currentDate>this.enlistmentEnd){
        
      } else{
        this.router.navigate(['gcatregistration']);
      }
    });
  }

}
