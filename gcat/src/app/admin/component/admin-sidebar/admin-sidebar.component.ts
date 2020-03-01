import { DataService } from "src/app/services/data.service";
import { Component, OnInit } from '@angular/core';
import { EventEmitterService } from '../../event-emitter.service';

@Component({
  selector: 'app-admin-sidebar',
  templateUrl: './admin-sidebar.component.html',
  styleUrls: ['./admin-sidebar.component.scss']
})
export class AdminSidebarComponent implements OnInit {
  credentials: any = {};
  credType: any = "";
  unconfirmedCount = 0;
  confirmedCount = 0;
  scheduledCount = 0;
  constructor(private ds: DataService, private es: EventEmitterService) { }

  ngOnInit() {
    this.credentials = JSON.parse(localStorage.getItem('gcweb_GCAT'));

    if (this.credentials !== null) {
      this.credType = this.credentials.data[0].fa_department;
    }
    this.getCount()

    setInterval(() => {
      this.getCount()
    }, 60000);
  }

  getCount(){
    this.ds.sendRequest('getUnconfirmedCount', null).subscribe(res => {
      this.unconfirmedCount = res.data[0].unconfirmedCount
    })
    this.ds.sendRequest('getConfirmedCount', null).subscribe(res => {
      this.confirmedCount = res.data[0].confirmedCount
    })
    this.ds.sendRequest('getScheduledCount', null).subscribe(res => {
      this.scheduledCount= res.data[0].scheduledCount
    })
  }
}
