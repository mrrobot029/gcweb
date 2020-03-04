import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/services/data.service";
import { NgxSpinnerService } from 'ngx-spinner'
import Swal from 'sweetalert2';
import { formatDate } from '@angular/common';
import { MatDialog } from '@angular/material';
import { EditemailComponent } from '../../dialogs/editemail/editemail.component';

@Component({
  selector: 'app-allapplicants',
  templateUrl: './allapplicants.component.html',
  styleUrls: ['./allapplicants.component.scss']
})
export class AllapplicantsComponent implements OnInit {
  log:any = {}
  now = new Date();

  credentials: any = {};
  credType: any = "";
  constructor(private ds: DataService, private spinner: NgxSpinnerService, private dialog: MatDialog) { }
  schedDate: any;
  schedTime: any;
  p = 1;
  applicantsConst: any = {};
  applicants: any = {};
  noapplicants = true;
  search: any = {};
  schedule: any = {};
  fullscreen = false
  applicantCount = 0;
  searchValue = ''
  searchValueStatus = ''
  order = 'DESC';
  sort = 'gc.gc_idnumber ' + this.order;
  sortValue = 'id';
  ngOnInit() {
    this.search.sort = this.sort;
    if (this.applicants.length == null) {
      this.noapplicants = true
      this.applicantCount = 0;
    }
    this.getAllApplicants()


    this.credentials = JSON.parse(localStorage.getItem('gcweb_GCAT'));

    if (this.credentials !== null) {
      this.credType = this.credentials.data[0].fa_department;
    }
  }

  setSort(e) {
    switch (e) {
      case 'id':
        this.sort = `gc.gc_idnumber ${this.order}`
        this.ngOnInit()
        break
      case 'name':
        this.sort = `si.si_lastname ${this.order},si.si_firstname ASC,si.si_midname,si.si_extname `
        this.ngOnInit()
        break
      case 'email':
        this.sort = `si.si_email ${this.order}`
        this.ngOnInit()
        break
      case 'program':
        this.sort = `gc.gc_course ${this.order},si.si_lastname ASC`
        this.ngOnInit()
        break
      default:
        this.sort = `gc.gc_idnumber ${this.order}`
    }
    this.searchValue = ''
  }

  setOrder(e){
    switch(e.target.selectedOptions[0].value){
      case 'ASC':
        this.order = 'ASC'
        this.setSort(this.sortValue)
        break;
      case 'DESC':
        this.order = 'DESC'
        this.setSort(this.sortValue)
        break;
      default:
        this.order = 'DESC'
    }
  }

  getAllApplicants() {
    this.spinner.show()
    let promise = this.ds.sendRequest("getApplicants", this.search).toPromise()
    promise.then(res => {
      if (res.status.remarks) {
        this.applicantsConst = res.data;
        this.applicants = this.applicantsConst
        this.noapplicants = false;
        this.applicantCount = res.data.length
      } else {
        this.noapplicants = true
      }
      this.spinner.hide()
    });
  }

  searchApplicants(e) {
    this.searchValueStatus = ''
    e.preventDefault();
    this.applicants = this.applicantsConst.filter(a =>{
      return a.gc_idnumber.includes(e.target.value) || a.si_lastname.toUpperCase().includes(e.target.value.toUpperCase()) || a.si_firstname.toUpperCase().includes(e.target.value.toUpperCase()) || `${a.si_lastname.toUpperCase()} ${a.si_firstname}`.includes(e.target.value.toUpperCase()) || a.si_email.toUpperCase().includes(e.target.value.toUpperCase())
    })
    this.p = 1;
  }

  searchApplicantStatus(e) {
    console.log(e)
    e.preventDefault();
    this.applicants = this.applicantsConst.filter(a =>{
      return a.gc_status.includes(e.target.value)
    })
    this.p = 1;
  }

  editEmail(a){
    const dialogRef = this.dialog.open(EditemailComponent, {
      width: '80vw',
      height: '90vh',
      data : a
    });
  }

}
