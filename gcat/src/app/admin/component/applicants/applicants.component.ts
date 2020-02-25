import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/services/data.service";
import { NgxSpinnerService } from 'ngx-spinner'
import Swal from 'sweetalert2';

@Component({
  selector: "app-applicants",
  templateUrl: "./applicants.component.html",
  styleUrls: ["./applicants.component.scss"]
})
export class ApplicantsComponent implements OnInit {

  credentials: any = {};
  credType: any = "";
  constructor(private ds: DataService, private spinner: NgxSpinnerService) { }
  schedDate: any;
  schedTime: any;
  p = 1;
  applicants: any = {};
  noapplicants = true;
  search: any = {};
  schedule: any = {};
  fullscreen = false
  applicantCount = 0;
  searchValue = ''
  sort = 'gc.gc_idnumber';
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
      console.log(this.credType);
    }
  }

  setSort(e) {
    switch (e.target.selectedOptions[0].value) {
      case 'id':
        this.sort = 'gc.gc_idnumber'
        this.ngOnInit()
        break
      case 'name':
        this.sort = 'si.si_lastname,si.si_firstname,si.si_midname,si.si_extname,gc.gc_idnumber'
        this.ngOnInit()
        break
      case 'email':
        this.sort = 'si.si_email'
        this.ngOnInit()
        break
      case 'program':
        this.sort = 'gc.gc_course,si.si_lastname'
        this.ngOnInit()
        break
      default:
        this.sort = 'gc.gc_idnumber'
    }
    this.searchValue = ''
  }

  getUnconfirmedApplicants() {
    this.spinner.show()
    let promise = this.ds.sendRequest("getUnconfirmedApplicants", this.search).toPromise()
    promise.then(res => {
      if (res.status.remarks) {
        this.applicants = res.data;
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
    this.ds
      .sendRequest("searchUnconfirmedApplicants", this.search)
      .subscribe(res => {
        if (res.status.remarks) {
          this.applicants = res.data;
        } else {
          this.applicants = [];
        }
      });
    this.p = 1
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

  addGCATSchedule(idnumber) {
    let isSchedDateSet: any;
    let isSchedTimeSet: any;

    isSchedDateSet = this.schedDate ? this.schedDate : null;
    isSchedTimeSet = this.schedTime ? this.schedTime : null;

    if (isSchedDateSet !== null && isSchedTimeSet !== null) {
      this.schedule.date = isSchedDateSet;
      this.schedule.time = isSchedTimeSet;
      this.schedule.idNumber = idnumber;
      this.ds.sendRequest("addGCATSchedule", this.schedule).subscribe(res => {
        if (res.status.remarks) {
          this.ds
            .callSwal("Success", "Record updated successfully.", "success")
            .then(() => {
              this.getUnconfirmedApplicants();
            });
        }
      });
    } else {
      this.ds.callSwal(
        "Incomplete Info",
        "Please fill up the schedule date and time.",
        "error"
      );
    }
  }
}
