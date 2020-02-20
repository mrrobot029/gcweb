import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/services/data.service";
import { NgxSpinnerService } from 'ngx-spinner'
import Swal from 'sweetalert2';


@Component({
  selector: 'app-confirmedapplicants',
  templateUrl: './confirmedapplicants.component.html',
  styleUrls: ['./confirmedapplicants.component.scss']
})
export class ConfirmedapplicantsComponent implements OnInit {
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
    this.getUnscheduledApplicants()
  }

  getUnscheduledApplicants() {
    this.spinner.show()
    let promise = this.ds.sendRequest("getUnscheduledApplicants", null).toPromise()
    promise.then(res => {
      if(res.status.remarks){
      this.applicants = res.data;
      this.applicantCount = this.applicants.length
      this.noapplicants = false;
      } else{
        this.noapplicants = true
      }
      this.spinner.hide()
    });
  }

  unconfirmApplicant(a){
    Swal.fire({
      title: `Undo confirmation for <br>${a.si_fullname}<br>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      cancelButtonText: 'No',
      reverseButtons: false
    }).then((result) => {
      if (result.value) {
        this.fullscreen = true
        this.spinner.show()
        let promise = this.ds.sendRequest('unconfirmApplication', a).toPromise()
        promise.then(res=>{
          this.spinner.hide()
          if(res.status.remarks){  
            Swal.fire(
            'Success!',
            'The application is now unconfirmed.',
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

  searchUnscheduledApplicants(e) {
    e.preventDefault();
    console.log(e);
    this.search.value = e.target.value;
    this.ds
      .sendRequest("searchUnscheduledApplicants", this.search)
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
              this.getUnscheduledApplicants();
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
