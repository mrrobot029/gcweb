import { Component, OnInit } from '@angular/core';
import { DataService } from '../data-service.service';
import {Router} from '@angular/router';
import { NgxSpinnerService } from "ngx-spinner";

@Component({
  selector: 'app-application',
  templateUrl: './application.component.html',
  styleUrls: ['./application.component.css']
})
export class ApplicationComponent implements OnInit {
  div1Blur = false;
  div2Blur = false;
  constructor(private ds: DataService, private router: Router, private spinner: NgxSpinnerService) { }
  currentDate = new Date();
  enlistmentStart;
  enlistmentEnd;
  ngOnInit() {
  }

  blur(e){
    if(e == 'div1'){
      this.div1Blur = true;
      console.log('blur 1')
    } else{
      this.div2Blur = true;
      console.log('blur 2')
    }
  }

  blurOff(e){
    if(e == 'div1'){
      this.div1Blur = false;
    } else{
      this.div2Blur = false;
    }
  }

  nav(e){
    switch(e){
      case 'gcatregistration': 
        this.router.navigate([e])
        // let promise = this.ds.sendRequest('getSettings', '').toPromise()
        // promise.then((settings) => {
        //   this.spinner.hide()
        //   this.enlistmentStart = new Date(settings.data[0].en_gcatstart)
        //   this.enlistmentEnd = new Date(settings.data[0].en_gcatend)
        //   if(this.currentDate<this.enlistmentStart||this.currentDate>this.enlistmentEnd){
        //     this.router.navigate(['registrationerror'])
        //   } else{
        //     this.router.navigate([e])
        //   }
        // })
        break;
      case 'enlistment':
        this.router.navigate([e]);
        break;
    }   
  }
}
