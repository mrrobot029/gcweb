import { Component, OnInit, ɵConsole } from "@angular/core";
import { NgxSpinnerService } from 'ngx-spinner'
import { DataService } from "src/app/services/data.service";
import Swal from 'sweetalert2';
import { formatDate } from '@angular/common';

@Component({
  selector: "app-scheduled",
  templateUrl: "./scheduled.component.html",
  styleUrls: ["./scheduled.component.scss"]
})
export class ScheduledComponent implements OnInit {
  constructor(private ds: DataService, private spinner: NgxSpinnerService) { }
  log:any = {}
  now = new Date();

  credentials = JSON.parse(localStorage.getItem('gcweb_GCAT'));
  schedDate: any;
  schedTime = 'AM';
  p = 1;
  applicants: any = {};
  search: any = {};
  schedule: any = {};
  noapplicants = true;
  applicantCount = 0;
  applicantCountOnDate = 0
  scheds: any;
  dropDownSched = "";

  async ngOnInit() {
    await this.getScheduledApplicants();
    await this.getAllSchedules();
    await this.getScheduledApplicantsCount()
    if (this.applicants.length == null) {
      this.noapplicants = true;
      this.applicantCount = 0;
    }
  }

  getScheduledApplicantsCount(){
    this.ds.sendRequest('getAllScheduledApplicantsCount', null).subscribe(res=>{
      this.applicantCount = res.data[0].scheduledCount
    })
  }

  getScheduledApplicants() {
    this.search.dropDownSched = this.dropDownSched;
    this.ds.sendRequest("getScheduledApplicants", this.search).subscribe(res => {
      if (res.data) {

        this.applicants = res.data;
        this.applicantCountOnDate = res.data.length
        this.noapplicants = false;
      } else {
        this.applicants = [];
        this.applicantCountOnDate = 0
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
          this.applicantCountOnDate = res.data.length
          this.noapplicants = false;
        } else {
          this.applicants = [];
          this.applicantCountOnDate = 0
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
    let promise = this.ds.sendRequest("addGCATSchedule", this.schedule).toPromise()
    promise.then(res => {
      if (res.status.remarks) {
        this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
        this.log.activity = `Added exam schedule ${formatDate(e.target.elements[0].value, 'MMMM dd, y', 'en-US' )} ${this.schedTime}.`
        this.log.idnumber = this.credentials.data[0].fa_empnumber
        this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
        this.log.department = this.credentials.data[0].fa_department
        this.ds.sendLog(this.log)
        this.getAllSchedules();
      } else {
        this.ds.callSwal("Adding Failed.", "Check the sched info.", "error");
      }
      this.spinner.hide()
    });

    this.schedule = {};
  }

  delGCATSchedule(e) {
    let oldDate:any = {}
    oldDate = this.scheds.filter(s => {
      return s.sched_recno == e
    })
    this.schedule.recNo = e;
    this.ds.sendRequest("delGCATSchedule", this.schedule).subscribe(res => {
      if (res.status.remarks) {
        this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
        this.log.activity = `Removed exam schedule ${formatDate(oldDate[0].sched_date, 'MMMM dd, y', 'en-US' )} ${oldDate[0].sched_time}.`
        this.log.idnumber = this.credentials.data[0].fa_empnumber
        this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
        this.log.department = this.credentials.data[0].fa_department
        this.ds.sendLog(this.log)
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

  unschedule(a){
    this.spinner.show()
    let promise = this.ds.sendRequest('unscheduleApplicant', a).toPromise()
    promise.then(res => {
      if(res.status.remarks){
        Swal.fire({
          icon: "success",
          title: "Success!",
          html: `Applicant<br><strong>${a.si_fullname}</strong><br>has been unscheduled.`
        }).then(()=>{
          this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
          this.log.activity = `Uscheduled ${a.si_fullname} - ID Number: ${a.gc_idnumber} for examination.`
          this.log.idnumber = this.credentials.data[0].fa_empnumber
          this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
          this.log.department = this.credentials.data[0].fa_department
          this.ds.sendLog(this.log)
          this.getScheduledApplicants()
          this.getScheduledApplicantsCount()
          this.getAllSchedules()
        })
      } else{
        Swal.fire({
          icon: "error",
          title: "Error!",
          html: `Unscheduling failed!`
        }).then(()=>{
          this.getAllSchedules()
          this.getScheduledApplicants()
          this.getScheduledApplicantsCount()
        })
      }
      this.spinner.hide()
    })
  }

  moveSchedule(d){
    let oldDate:any = {}
    oldDate = this.scheds.filter(s => {
      return s.sched_recno == this.dropDownSched
    })
    this.spinner.show()
    let date:any = {}
    date.currentDate = this.dropDownSched
    date.moveDate = d.sched_recno
    let promise = this.ds.sendRequest('moveSchedule', date).toPromise()
    promise.then(res=>{
      if(res.status.remarks){
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          html: `Exam schedule has been moved from <strong>${formatDate(oldDate[0].sched_date, 'MMMM dd, y', 'en-US' )} ${oldDate[0].sched_time}</strong> to <strong>${formatDate(d.sched_date, 'MMMM dd, y', 'en-US' )} ${d.sched_time}</strong>`
        }).then(()=>{
          this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
          this.log.activity = `Moved exam schedule from ${formatDate(oldDate[0].sched_date, 'MMMM dd, y', 'en-US' )} ${oldDate[0].sched_time} to ${formatDate(d.sched_date, 'MMMM dd, y', 'en-US' )} ${d.sched_time}.`
          this.log.idnumber = this.credentials.data[0].fa_empnumber
          this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
          this.log.department = this.credentials.data[0].fa_department
          this.ds.sendLog(this.log)
          setTimeout(()=>{
            this.spinner.hide()
            this.dropDownSched = null
            this.ngOnInit()
          }, 500)
        })
      }
    })
  }
}
