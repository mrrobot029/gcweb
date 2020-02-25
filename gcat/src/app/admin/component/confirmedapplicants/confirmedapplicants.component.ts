import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/services/data.service";
import { NgxSpinnerService } from "ngx-spinner";
import Swal from "sweetalert2";

@Component({
  selector: "app-confirmedapplicants",
  templateUrl: "./confirmedapplicants.component.html",
  styleUrls: ["./confirmedapplicants.component.scss"]
})
export class ConfirmedapplicantsComponent implements OnInit {
  constructor(private ds: DataService, private spinner: NgxSpinnerService) { }
  schedDate: any;
  schedTime: any = "";
  p = 1;
  applicants: any = {};
  noapplicants = true;
  search: any = {};
  schedule: any = {};
  fullscreen = false;
  applicantCount = 0;
  searchValue = "";
  sort = "gc.gc_idnumber";

  scheds: any;
  async ngOnInit() {
    this.search.sort = this.sort;
    if (this.applicants.length == null) {
      this.noapplicants = true;
      this.applicantCount = 0;
    }
    await this.getUnscheduledApplicants();
    await this.getAvailableSchedules();
  }

  setSort(e){
    switch(e.target.selectedOptions[0].value){
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

  getUnscheduledApplicants() {
    this.spinner.show();
    let promise = this.ds
      .sendRequest("getUnscheduledApplicants", this.search)
      .toPromise();
    promise.then(res => {
      if (res.status.remarks) {
        this.applicants = res.data;
        this.applicantCount = this.applicants.length;
        this.noapplicants = false;
      } else {
        this.noapplicants = true;
      }
      this.spinner.hide();
    });
  }

  searchUnscheduledApplicants(e) {
    e.preventDefault();
    this.search.value = e.target.value;
    this.ds
      .sendRequest("searchUnscheduledApplicants", this.search)
      .subscribe(res => {
        if (res.status.remarks) {
          this.applicants = res.data;
        } else {
          this.applicants = [];
        }
      });
    this.p = 1;
  }

  unconfirmApplicant(a) {
    Swal.fire({
      title: `Undo confirmation for <br>${a.si_fullname}<br>`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes",
      cancelButtonText: "No",
      reverseButtons: false
    }).then(result => {
      if (result.value) {
        this.fullscreen = true;
        this.spinner.show();
        let promise = this.ds
          .sendRequest("unconfirmApplication", a)
          .toPromise();
        promise.then(res => {
          this.spinner.hide();
          if (res.status.remarks) {
            Swal.fire(
              "Success!",
              "The application is now unconfirmed.",
              "success"
            ).then(() => {
              this.ngOnInit();
            });
          } else {
            Swal.fire("Error!", "Something went wrong.", "error").then(() => {
              this.ngOnInit();
            });
          }
        });
      }
    });
    this.fullscreen = false;
  }

  addScheduleForApplicant(idnumber) {
    if (this.schedTime !== "") {
      this.schedule.time = this.schedTime;
      this.schedule.idNumber = idnumber;
      this.ds
        .sendRequest("addScheduleForApplicant", this.schedule)
        .subscribe(res => {
          if (res.status.remarks) {
            this.ds
              .callSwal("Success", "Record updated successfully.", "success")
              .then(() => {
                this.getUnscheduledApplicants();
                this.getAvailableSchedules();
              });
          }
        });
    } else {
      this.ds.callSwal(
        "Incomplete Info",
        "Please select schedule time.",
        "error"
      );
    }
  }

  getAvailableSchedules() {
    this.ds.sendRequest("getAvailableSchedules", null).subscribe(res => {
      if (res.status.remarks) {
        this.scheds = res.data;
      } else {
        this.scheds = [];
      }
    });
  }
}
