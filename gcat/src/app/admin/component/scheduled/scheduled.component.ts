import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/services/data.service";

@Component({
  selector: "app-scheduled",
  templateUrl: "./scheduled.component.html",
  styleUrls: ["./scheduled.component.scss"]
})
export class ScheduledComponent implements OnInit {
  constructor(private ds: DataService) {}
  schedDate: any;
  schedTime: any;
  p = 1;
  applicants: any;
  search: any = {};
  schedule: any = {};

  ngOnInit() {
    this.getScheduledApplicants();
  }

  getScheduledApplicants() {
    this.ds.sendRequest("getScheduledApplicants", null).subscribe(res => {
      this.applicants = res.data;
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
}
