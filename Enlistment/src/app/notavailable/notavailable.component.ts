import { Component, OnInit } from '@angular/core';
import { DataService } from '../data-service.service';
import {Router} from '@angular/router';

@Component({
  selector: 'app-notavailable',
  templateUrl: './notavailable.component.html',
  styleUrls: ['./notavailable.component.css']
})
export class NotavailableComponent implements OnInit {
  currentDate = new Date();
  enlistmentStart;
  enlistmentEnd;
  constructor(private ds: DataService, private router: Router) { }

  ngOnInit() {
    this.currentDate.setHours(0,0,0,0)
    this.ds.sendRequest('getSettings', '').subscribe((settings)=>{
      this.enlistmentStart = new Date(settings.data[0].en_enstart)
      this.enlistmentEnd = new Date(settings.data[0].en_enend)
      if(this.currentDate<this.enlistmentStart||this.currentDate>this.enlistmentEnd){
        
      } else{
        this.router.navigate(['enlistment']);
      }
    });
  }

}
