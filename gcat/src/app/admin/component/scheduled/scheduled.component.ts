import { Component, OnInit, ɵConsole } from "@angular/core";
import { NgxSpinnerService } from 'ngx-spinner'
import { DataService } from "src/app/services/data.service";

@Component({
  selector: "app-scheduled",
  templateUrl: "./scheduled.component.html",
  styleUrls: ["./scheduled.component.scss"]
})
export class ScheduledComponent implements OnInit {
  constructor(private ds: DataService, private spinner: NgxSpinnerService) { }
  schedDate: any;
  schedTime = 'AM';
  p = 1;
  applicants: any = {};
  search: any = {};
  schedule: any = {};
  noapplicants = true;
  applicantCount = 0;
  scheds: any;
  dropDownSched = "";

  async ngOnInit() {
    await this.getScheduledApplicants();
    await this.getAllSchedules();
    if (this.applicants.length == null) {
      this.noapplicants = true;
      this.applicantCount = 0;
    }
  }

  getScheduledApplicants() {
    this.search.dropDownSched = this.dropDownSched;
    this.ds.sendRequest("getScheduledApplicants", this.search).subscribe(res => {
      if (res.data) {

        this.applicants = res.data;
        this.applicantCount = res.data.length;
        this.noapplicants = false;
      } else {
        this.applicants = [];
        this.applicantCount = 0
        this.noapplicants = true;
      }


    });
  }

  searchScheduledApplicants(e) {
    e.preventDefault();
    this.search.value = e.target.value;
    this.search.dropDownSched = this.dropDownSched;
    this.ds
      .sendRequest("searchScheduledApplicants", this.search)
      .subscribe(res => {
        if (res.status.remarks) {
          this.applicants = res.data;
          this.applicantCount = res.data.length;
          this.noapplicants = false;
        } else {
          this.applicants = [];
          this.applicantCount = 0
          this.noapplicants = true;
        }
      });
  }

  getAllSchedules() {
    this.ds.sendRequest("getAllSchedules", null).subscribe(res => {
      if (res.status.remarks) {
        this.scheds = res.data;
      } else {
        this.scheds = [];
      }
    });
  }

  addGCATSchedule(e) {
    this.spinner.show()
    e.preventDefault();
    this.schedule.date = e.target.elements[0].value;
    this.schedule.time = this.schedTime
    console.log(this.schedule)
    let promise = this.ds.sendRequest("addGCATSchedule", this.schedule).toPromise()
    promise.then(res => {
      if (res.status.remarks) {
        this.getAllSchedules();
      } else {
        this.ds.callSwal("Adding Failed.", "Check the sched info.", "error");
      }
      this.spinner.hide()
    });

    this.schedule = {};
  }

  delGCATSchedule(e) {
    this.schedule.recNo = e;
    this.ds.sendRequest("delGCATSchedule", this.schedule).subscribe(res => {
      if (res.status.remarks) {
        this.getAllSchedules();
      } else {
        this.ds.callSwal(
          "Deleting Failed.",
          "Sched was already assign to an applicant",
          "error"
        );
      }
    });
    this.schedule = {};
  }
}
