import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/services/data.service";

@Component({
  selector: "app-scheduled",
  templateUrl: "./scheduled.component.html",
  styleUrls: ["./scheduled.component.scss"]
})
export class ScheduledComponent implements OnInit {
  constructor(private ds: DataService) { }
  schedDate: any;
  schedTime: any;
  p = 1;
  applicants: any = {};
  search: any = {};
  schedule: any = {};
  noapplicants = true;
  applicantCount = 0;
  scheds: any;

  async ngOnInit() {
    await this.getScheduledApplicants();
    await this.getAllSchedules();
    if (this.applicants.length == null) {
      this.noapplicants = true;
      this.applicantCount = 0;
    }
  }

  getScheduledApplicants() {
    this.ds.sendRequest("getScheduledApplicants", null).subscribe(res => {
      this.applicants = res.data;
      this.applicantCount = res.data.length;
      this.noapplicants = false;
    });
  }

  searchScheduledApplicants(e) {
    e.preventDefault();
    console.log(e);
    this.search.value = e.target.elements[0].value;
    this.ds
      .sendRequest("searchScheduledApplicants", this.search)
      .subscribe(res => {
        if (res.status.remarks) {
          this.applicants = res.data;
        } else {
          this.applicants = [];
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
    e.preventDefault();
    this.schedule.time = e.target.elements[0].value;

    this.ds.sendRequest("addGCATSchedule", this.schedule).subscribe(res => {
      if (res.status.remarks) {
        this.getAllSchedules();
      } else {
        this.ds.callSwal("Adding Failed.", "Check the sched info.", "error");
      }
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
