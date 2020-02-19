import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/services/data.service";

@Component({
  selector: "app-applicants",
  templateUrl: "./applicants.component.html",
  styleUrls: ["./applicants.component.scss"]
})
export class ApplicantsComponent implements OnInit {
  constructor(private ds: DataService) {}
  schedDate: any;
  schedTime: any;
  p = 1;
  applicants: any;
  search: any = {};
  schedule: any = {};

  ngOnInit() {
    this.getUnscheduledApplicants();
  }

  getUnscheduledApplicants() {
    this.ds.sendRequest("getUnscheduledApplicants", null).subscribe(res => {
      this.applicants = res.data;
    });
  }

  searchUnscheduledApplicants(e) {
    e.preventDefault();
    console.log(e);
    this.search.value = e.target.elements[0].value;
    this.ds
      .sendRequest("searchUnscheduledApplicants", this.search)
      .subscribe(res => {
        if (res.status.remarks) {
          this.applicants = res.data;
        } else {
          this.applicants = [];
        }
      });
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
