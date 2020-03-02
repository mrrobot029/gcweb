import { Component, OnInit } from '@angular/core';
import { DataService } from 'src/app/services/data.service';
import { formatDate } from '@angular/common';

@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.scss']
})
export class UsersComponent implements OnInit {
  log:any = {}
  now = new Date();
  credentials = JSON.parse(localStorage.getItem('gcweb_GCAT'));
  p = 1;
  userlogs
  requestdata: any = {};
  gcatmembers: any[];

  constructor(private ds: DataService) { }

  ngOnInit() {
    this.getGCATmembers();
    this.getUserLogs()
  }

  getGCATmembers() {
    this.ds.sendRequest('getGCATmembers', null).subscribe(res => {
      this.gcatmembers = res.data;
    });
  }

  getUserLogs() {
    this.ds.sendRequest('getUserLogs', null).subscribe(res => {
      this.userlogs = res.data;
    });
  }

  addGCATmember(e) {
    e.preventDefault();
    this.requestdata.empNo = e.target.elements[0].value;
    this.requestdata.empFname = e.target.elements[1].value;
    this.requestdata.empMname = e.target.elements[2].value;
    this.requestdata.empLname = e.target.elements[3].value;
    this.requestdata.empEname = e.target.elements[4].value;
    this.requestdata.empType = e.target.elements[5].value;
    this.requestdata.empAccType = 0
    if(e.target.elements[5].value === 'GCAT-R1'){
      this.requestdata.empType = 'GCAT-R'
      this.requestdata.empAccType = 1
    }

    this.ds.sendRequest('addGCATmember', this.requestdata).subscribe(res => {
      if (res.status.remarks) {
        this.ds.callSwal('Success', res.status.message, 'success');
        this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
        this.log.activity = `Added account for ${this.requestdata.empLname}, ${this.requestdata.empFname} - ID Number: ${this.requestdata.empNo} Department: ${this.requestdata.empType}.`
        this.log.idnumber = this.credentials.data[0].fa_empnumber
        this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
        this.log.department = this.credentials.data[0].fa_department
        this.ds.sendLog(this.log)
        this.getGCATmembers();
        this.getUserLogs()
      } else {
        this.ds.callSwal('Failed', res.status.message, 'error');
      }

      this.requestdata = {};
    });
  }

  deleteGCATmember(i) {
    this.requestdata.empRecno = this.gcatmembers[i].fa_recno;
    this.ds.sendRequest('deleteGCATmember', this.requestdata).subscribe(res => {
      this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
      this.log.activity = `Removed account for ${this.gcatmembers[i].fa_lname}, ${this.gcatmembers[i].fa_fname} - ID Number: ${this.gcatmembers[i].fa_empnumber}.`
      this.log.idnumber = this.credentials.data[0].fa_empnumber
      this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
      this.log.department = this.credentials.data[0].fa_department
      this.ds.sendLog(this.log)
      this.getGCATmembers();
      this.getUserLogs()
    });
  }
}
