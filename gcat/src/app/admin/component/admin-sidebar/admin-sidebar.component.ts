import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-admin-sidebar',
  templateUrl: './admin-sidebar.component.html',
  styleUrls: ['./admin-sidebar.component.scss']
})
export class AdminSidebarComponent implements OnInit {
  credentials: any = {};
  credType: any = "";
  constructor() { }

  ngOnInit() {
    this.credentials = JSON.parse(localStorage.getItem('gcweb_GCAT'));

    if (this.credentials !== null) {
      this.credType = this.credentials.data[0].fa_department;
      console.log(this.credType);
    }
  }

}
