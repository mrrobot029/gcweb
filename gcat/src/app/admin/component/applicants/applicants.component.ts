<<<<<<< HEAD
import { Component, OnInit } from '@angular/core';
import { DataService } from 'src/app/services/data.service';
import { NgxSpinnerService } from "ngx-spinner";
import Swal from 'sweetalert2'
=======
import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/services/data.service";
>>>>>>> b010396e1c6455d296cd213569cd7d35631bb77d

@Component({
  selector: "app-applicants",
  templateUrl: "./applicants.component.html",
  styleUrls: ["./applicants.component.scss"]
})
export class ApplicantsComponent implements OnInit {
<<<<<<< HEAD
p = 1
  constructor(private ds: DataService, private spinner: NgxSpinnerService) { }

  applicants:any = {};
=======
  constructor(private ds: DataService) {}
  schedDate: any;
  schedTime: any;
  p = 1;
  applicants: any;
  search: any = {};
  schedule: any = {};
>>>>>>> b010396e1c6455d296cd213569cd7d35631bb77d

  ngOnInit() {
    this.getUnscheduledApplicants();
  }

  getUnscheduledApplicants() {
    this.ds.sendRequest("getUnscheduledApplicants", null).subscribe(res => {
      this.applicants = res.data;
    });
  }

<<<<<<< HEAD
  sendMail(){
    let x = 0
    this.spinner.show()
    this.ds.sendRequest('getMail', null).subscribe(res => {
      console.log(res.data.length)
      while(x<res.data.length){
        console.log(res.data[x])
        let sendPromise = this.ds.sendRequest('sendMail', res.data[x]).toPromise()
        sendPromise.then(res=>{
          console.log(res)
          this.spinner.hide()
          Swal.fire({
            icon: 'success',
            title: `Emails sent successfully!`,
          })
        })
        x++
      }
    });
  }

=======
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
>>>>>>> b010396e1c6455d296cd213569cd7d35631bb77d
}
