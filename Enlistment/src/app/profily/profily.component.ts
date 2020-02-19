import { Component, OnInit } from '@angular/core';
import { DataService } from '../data-service.service';
import { formatDate } from '@angular/common';
import {Router} from '@angular/router';

@Component({
  selector: 'app-profily',
  templateUrl: './profily.component.html',
  styleUrls: ['./profily.component.css']
})
export class ProfilyComponent implements OnInit {
department = "gc"
deptlogo = "./assets/logo/logo_gc.png"
selectedgc = true
navigateNew = "/home"
navigateOld = "/home"
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
        this.router.navigate(['notavailable']);
      }
    });
  }

  onChange(dep){
    this.deptlogo = "./assets/logo/logo_"+dep+".png"
    if(dep!='gc'){
      this.selectedgc = false;
    } else{
      this.selectedgc = true;
    }
    this.navigateNew = "/"+dep+"profiling";
    this.navigateOld = "/"+dep+"enlistment";
  }
}
