import { Component, OnInit } from '@angular/core';
import { DataService } from 'src/app/services/data.service';

@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.scss']
})
export class UsersComponent implements OnInit {

  requestdata: any = {};
  gcatmembers: any[];

  constructor(private ds: DataService) { }

  ngOnInit() {
    this.getGCATmembers();
  }

  getGCATmembers() {
    this.ds.sendRequest('getGCATmembers', null).subscribe(res => {
      console.log(res.data);
      this.gcatmembers = res.data;
    });
  }

  addGCATmember(e) {
    e.preventDefault();
    this.requestdata.empNo = e.target.elements[0].value;
    this.requestdata.empFname = e.target.elements[1].value;
    this.requestdata.empMname = e.target.elements[2].value;
    this.requestdata.empLname = e.target.elements[3].value;
    this.requestdata.empEname = e.target.elements[4].value;

    this.ds.sendRequest('addGCATmember', this.requestdata).subscribe(res => {
      if (res.status.remarks) {
        this.ds.callSwal('Success', res.status.message , 'success');
        this.getGCATmembers();
      } else {
        this.ds.callSwal('Failed', res.status.message , 'error');
      }

      this.requestdata = {};
    });
  }

  deleteGCATmember(i) {
    this.requestdata.empRecno = this.gcatmembers[i].fa_recno;
    this.ds.sendRequest('deleteGCATmember', this.requestdata).subscribe(res => {
      console.log(res);
      this.getGCATmembers();
    });
  }
}
