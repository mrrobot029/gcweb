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
  constructor(private ds: DataService, private spinner: NgxSpinnerService) {}
  schedDate: any;
  schedTime: any;
  p = 1;
  applicants: any = {};
  noapplicants = true;
  search: any = {};
  schedule: any = {};
  fullscreen = false
  applicantCount = 0;
  ngOnInit() {
    if(this.applicants.length==null){
      this.noapplicants = true
      this.applicantCount = 0;
    }
    this.getUnconfirmedApplicants()
  }

  getUnconfirmedApplicants() {
    this.spinner.show()
    let promise = this.ds.sendRequest("getUnconfirmedApplicants", null).toPromise()
    promise.then(res => {
      if(res.status.remarks){
        this.applicants = res.data;
        this.noapplicants = false;
        this.applicantCount = res.data.length
        } else{
          this.noapplicants = true
        }
      this.spinner.hide()
    });
  }

  searchUnconfirmedApplicants(e) {
    e.preventDefault();
    console.log(e);
    this.search.value = e.target.value;
    this.ds
      .sendRequest("searchUnconfirmedApplicants", this.search)
      .subscribe(res => {
        if (res.status.remarks) {
          console.log(res.data)
          this.applicants = res.data;
        } else {
          this.applicants = [];
        }
      });
      this.p = 1
  }

  sendMail(a){
    console.log(a)
    this.spinner.show()
    let promise = this.ds.sendRequest('sendMail', a).toPromise()
    promise.then(res=>{
      this.spinner.hide()
      Swal.fire({
        icon: 'success',
        title: 'Confirmation Email Sent!',
      })
    })
  }

  delete(a){
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
        let promise = this.ds.sendRequest('deleteApplicant', a).toPromise()
        promise.then(res=>{
          this.spinner.hide()
          if(res.status.remarks){  
            Swal.fire(
            'Deleted!',
            'The application has been removed.',
            'success'
          ).then(()=>{
            this.ngOnInit()
          })
          } else{
            Swal.fire(
            'Error!',
            'Something went wrong.',
            'error'
          ).then(()=>{
            this.ngOnInit()
          })
          }

        })
      }
    })
    this.fullscreen = false
  }

  confirmApplication(a){
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
        promise.then(res=>{
          this.spinner.hide()
          if(res.status.remarks){  
            Swal.fire(
            'Confirmed!',
            'The application is now confirmed.',
            'success'
          ).then(()=>{
            this.ngOnInit()
          })
          } else{
            Swal.fire(
            'Error!',
            'Something went wrong.',
            'error'
          ).then(()=>{
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
