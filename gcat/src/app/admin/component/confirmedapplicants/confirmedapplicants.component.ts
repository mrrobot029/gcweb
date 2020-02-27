import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/services/data.service";
import { NgxSpinnerService } from "ngx-spinner";
import Swal from "sweetalert2";
import { formatDate } from '@angular/common';

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
  order = 'DESC';
  sort = 'gc.gc_idnumber ' + this.order;
  sortValue = 'id';
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

  addScheduleForApplicant(applicant) {
    if (this.schedTime !== "") {
      this.schedule.time = this.schedTime;
      this.schedule.idNumber = applicant.gc_idnumber;
      let selectedsched = this.scheds.filter(s => {
        return s.sched_recno == this.schedTime
      })
      this.ds
        .sendRequest("addScheduleForApplicant", this.schedule)
        .subscribe(res => {
          if (res.status.remarks) {
              Swal.fire({
                title: `${applicant.si_fullname}`, 
                html: `has been scheduled for <br><br><strong>${formatDate(selectedsched[0].sched_date, 'MMMM dd, y', 'en-US' )} ${selectedsched[0].sched_time}<br></strong>Slots Remaining: <strong>${40 - selectedsched[0].sched_count-1}</strong>`, 
                icon: "success"})
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
        this.schedTime = ""
        this.schedule.time = ""
        if(this.schedTime == ""){
          this.schedTime = res.data[0].sched_recno
        }
      } else {
        this.scheds = [];
        this.schedTime = ""
        this.schedule.time = ""
      }
    });
  }
}
