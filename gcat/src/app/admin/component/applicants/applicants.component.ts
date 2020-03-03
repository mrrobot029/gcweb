import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/services/data.service";
import { NgxSpinnerService } from 'ngx-spinner'
import Swal from 'sweetalert2';
import { formatDate } from '@angular/common';
@Component({
  selector: "app-applicants",
  templateUrl: "./applicants.component.html",
  styleUrls: ["./applicants.component.scss"]
})
export class ApplicantsComponent implements OnInit {
  log:any = {}
  now = new Date();

  credentials: any = {};
  credType: any = "";
  constructor(private ds: DataService, private spinner: NgxSpinnerService) { }
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
  order = 'DESC';
  sort = 'gc.gc_idnumber ' + this.order;
  sortValue = 'id';
  ngOnInit() {
    this.search.sort = this.sort;
    if (this.applicants.length == null) {
      this.noapplicants = true
      this.applicantCount = 0;
    }
    this.getUnconfirmedApplicants()


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

  getUnconfirmedApplicants() {
    this.spinner.show()
    let promise = this.ds.sendRequest("getUnconfirmedApplicants", this.search).toPromise()
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

  searchUnconfirmedApplicants(e) {
    e.preventDefault();
    this.search.value = e.target.value;
    this.applicants = this.applicantsConst.filter(a =>{
      return a.gc_idnumber.includes(e.target.value) || a.si_lastname.toUpperCase().includes(e.target.value.toUpperCase()) || a.si_firstname.toUpperCase().includes(e.target.value.toUpperCase()) || `${a.si_lastname.toUpperCase()} ${a.si_firstname}`.includes(e.target.value.toUpperCase()) || a.si_email.toUpperCase().includes(e.target.value.toUpperCase())
    })
    this.p = 1;
  }

  sendMail(a) {
    this.spinner.show()
    let promise = this.ds.sendRequest('sendMail', a).toPromise()
    promise.then(res => {
      this.spinner.hide()
      Swal.fire({
        icon: 'success',
        title: 'Confirmation Email Sent!',
      }).then(() => {
        this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
        this.log.activity = `Sent confirmation email to ${a.si_fullname} - ID Number: ${a.gc_idnumber}.`
        this.log.idnumber = this.credentials.data[0].fa_empnumber
        this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
        this.log.department = this.credentials.data[0].fa_department
        this.ds.sendLog(this.log)
        this.ngOnInit()
      })
    })
  }

  delete(a) {
    Swal.fire({
      title: `Delete applicant <br>${a.si_fullname}<br>`,
      icon: 'error',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      cancelButtonText: 'No',
      confirmButtonColor: '#d33',
      reverseButtons: false
    }).then((result) => {
      if (result.value) {
        this.fullscreen = true
        this.spinner.show()
        let promise = this.ds.sendRequest('deleteApplication', a).toPromise()
        promise.then(res => {
          this.spinner.hide()
          if (res.status.remarks) {
            Swal.fire(
              'Deleted!',
              'The application has been removed.',
              'success'
            ).then(() => {
              this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
              this.log.activity = `Deleted applicant ${a.si_fullname} - ID Number: ${a.gc_idnumber}.`
              this.log.idnumber = this.credentials.data[0].fa_empnumber
              this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
              this.log.department = this.credentials.data[0].fa_department
              this.ds.sendLog(this.log)
              this.ngOnInit()
            })
          } else {
            Swal.fire(
              'Error!',
              'Something went wrong.',
              'error'
            ).then(() => {
              this.ngOnInit()
            })
          }

        })
      }
    })
    this.fullscreen = false
  }

  confirmApplication(a) {
    Swal.fire({
      title: `Confirm application for <br>${a.si_fullname}<br>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      cancelButtonText: 'No',
      reverseButtons: false
    }).then((result) => {
      if (result.value) {
        this.fullscreen = true
        this.spinner.show()
        let promise = this.ds.sendRequest('confirmApplication', a).toPromise()
        promise.then(res => {
          this.spinner.hide()
          if (res.status.remarks) {
            Swal.fire(
              'Confirmed!',
              'The application is now confirmed.',
              'success'
            ).then(() => {
              this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
              this.log.activity = `Confirmed application for ${a.si_fullname} - ID Number: ${a.gc_idnumber}.`
              this.log.idnumber = this.credentials.data[0].fa_empnumber
              this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
              this.log.department = this.credentials.data[0].fa_department
              this.ds.sendLog(this.log)
              this.ngOnInit()
            })
          } else {
            Swal.fire(
              'Error!',
              'Something went wrong.',
              'error'
            ).then(() => {
              this.ngOnInit()
            })
          }

        })
      }
    })
    this.fullscreen = false
  }

}
