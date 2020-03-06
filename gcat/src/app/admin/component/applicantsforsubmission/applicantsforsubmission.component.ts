import { Component, OnInit } from "@angular/core";
import { DataService } from "src/app/services/data.service";
import { NgxSpinnerService } from "ngx-spinner";
import Swal from "sweetalert2";
import { formatDate } from '@angular/common';


@Component({
  selector: 'app-applicantsforsubmission',
  templateUrl: './applicantsforsubmission.component.html',
  styleUrls: ['./applicantsforsubmission.component.scss']
})
export class ApplicantsforsubmissionComponent implements OnInit {
  log:any = {}
  now = new Date();
  credentials = JSON.parse(localStorage.getItem('gcweb_GCAT'));
  constructor(private ds: DataService, private spinner: NgxSpinnerService) { }
  schedDate: any;
  schedTime: any = "";
  p = 1;
  applicantsConst:any = {}
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
  schedules: any;
  async ngOnInit() {
    this.search.sort = this.sort;
    if (this.applicants.length == null) {
      this.noapplicants = true;
      this.applicantCount = 0;
    }
    await this.getUnscheduledApplicants();
    await this.getAvailableSchedules();
    await this.getAllSchedules();
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
    this.fullscreen = false
    this.spinner.show();
    let promise = this.ds
      .sendRequest("getUnscheduledSubApplicants", this.search)
      .toPromise();
    promise.then(res => {
      if (res.status.remarks) {
        this.applicantsConst = res.data;
        this.applicants = this.applicantsConst
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
    this.applicants = this.applicantsConst.filter(a =>{
      return a.gc_idnumber.includes(e.target.value) || a.si_lastname.toUpperCase().includes(e.target.value.toUpperCase()) || a.si_firstname.toUpperCase().includes(e.target.value.toUpperCase()) || `${a.si_lastname.toUpperCase()} ${a.si_firstname}`.includes(e.target.value.toUpperCase()) || a.si_email.toUpperCase().includes(e.target.value.toUpperCase())
    })
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
              this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
              this.log.activity = `Undid application confirmation for ${a.si_fullname} - ID Number: ${a.gc_idnumber}.`
              this.log.idnumber = this.credentials.data[0].fa_empnumber
              this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
              this.log.department = this.credentials.data[0].fa_department
              this.ds.sendLog(this.log)
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
    this.fullscreen = true;
    this.spinner.show()
    if (this.schedTime !== "") {
      this.schedule.time = this.schedTime;
      let promise = this.ds.sendRequest("getSubScheduleCount", this.schedule).toPromise()
      promise.then(res =>{
        if(res.data[0].sub_count>=300){
          Swal.fire({
            title: `ERROR`, 
            html: `<strong>Applicant count cannot exceed 40!</strong>`, 
            icon: "error"})
            .then(() => {
              this.getUnscheduledApplicants();
              this.getAvailableSchedules();
            })
        } else{
          this.schedule.idNumber = applicant.gc_idnumber;
          let selectedsched = this.scheds.filter(s => {
            return s.sub_recno == this.schedTime
          })
          let promise2 = this.ds.sendRequest("addSubScheduleForApplicant", this.schedule).toPromise()
            promise2.then(async res => {
              if (res.status.remarks) {
                await this.getUnscheduledApplicants()
                await this.getAvailableSchedules();
                this.spinner
                  Swal.fire({
                    title: `${applicant.si_fullname}`, 
                    html: `has been scheduled for <br><br><strong>${formatDate(selectedsched[0].sub_date, 'MMMM dd, y', 'en-US' )}<br></strong>Slots Remaining: <strong>${40 - selectedsched[0].sub_count-1}</strong>`, 
                    icon: "success"})
                  .then(() => {
                    this.fullscreen = false;
                    this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
                    this.log.activity = `Scheduled applicant ${applicant.si_fullname} - ID Number: ${applicant.gc_idnumber} for examination on ${formatDate(selectedsched[0].sub_date, 'MMMM dd, y', 'en-US' )}.`
                    this.log.idnumber = this.credentials.data[0].fa_empnumber
                    this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
                    this.log.department = this.credentials.data[0].fa_department
                    this.ds.sendLog(this.log)
                  });
              }
            });
        }
      })
    } else {
      this.spinner.hide()
      this.fullscreen = false;
      this.ds.callSwal(
        "Incomplete Info",
        "Please select schedule time.",
        "error"
      );
    }
  }

  addGCATSchedule(e) {
    this.spinner.show()
    e.preventDefault();
    this.schedule.date = e.target.elements[0].value;
    let promise = this.ds.sendRequest("addGCATSubSchedule", this.schedule).toPromise()
    promise.then(res => {
      if (res.status.remarks) {
        this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
        this.log.activity = `Added requirements submission schedule ${formatDate(e.target.elements[0].value, 'MMMM dd, y', 'en-US' )}.`
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

  getAllSchedules() {
    this.ds.sendRequest("getAllSubSchedules", null).subscribe(res => {
      if (res.status.remarks) {
        this.schedules = res.data;
      } else {
        this.schedules = [];
      }
    });
  }

  delGCATSchedule(e) {
    let oldDate:any = {}
    oldDate = this.schedules.filter(s => {
      return s.sub_recno == e
    })
    this.schedule.recNo = e;
    this.ds.sendRequest("delGCATSubSchedule", this.schedule).subscribe(res => {
      if (res.status.remarks) {
        this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
        this.log.activity = `Removed exam schedule ${formatDate(oldDate[0].sub_date, 'MMMM dd, y', 'en-US' )}.`
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

  getAvailableSchedules() {
    this.ds.sendRequest("getAvailableSubSchedules", null).subscribe(res => {
      if (res.status.remarks) {
        this.scheds = res.data;
        this.schedTime = ""
        this.schedule.time = ""
        if(this.schedTime == ""){
          this.schedTime = res.data[0].sub_recno
        }
      } else {
        this.scheds = [];
        this.schedTime = ""
        this.schedule.time = ""
      }
    });
  }
}
