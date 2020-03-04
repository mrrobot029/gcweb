import { Component, Inject } from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import { NgxSpinnerService } from 'ngx-spinner';
import { DataService } from 'src/app/services/data.service';
import Swal from 'sweetalert2';
import { formatDate } from '@angular/common';

@Component({
  selector: 'app-editemail',
  templateUrl: './editemail.component.html',
  styleUrls: ['./editemail.component.scss']
})
export class EditemailComponent{
  log:any = {}
  now = new Date();
  credentials: any = {};
  credType
  student:any
  emailOld
  emailNew
  emailConfirm = null
  matched = false;
  constructor(@Inject(MAT_DIALOG_DATA) public data: any, private spinner: NgxSpinnerService, private ds : DataService, public dialogRef: MatDialogRef<EditemailComponent>) { }
  
  ngOnInit() {
    this.credentials = JSON.parse(localStorage.getItem('gcweb_GCAT'));
    if (this.credentials !== null) {
      this.credType = this.credentials.data[0].fa_department;
    }

    this.student = this.data
    this.emailOld = this.data.si_email
    console.log(this.data)
  }

  validateEmail(){
    this.spinner.show()
    let checkStudent:any = {}
    checkStudent.idNumber = this.student.gc_idnumber
    let promise1= this.ds.sendRequest('getStudent', checkStudent).toPromise()
    promise1.then(res=>{
      checkStudent = res.data[0]
      this.spinner.hide()
      if(checkStudent.si_email == this.emailNew ){
        return;
      } else{
        this.spinner.show()
        let email: any = {}
        email.email = this.emailNew
        let promise = this.ds.sendRequest('validateEmail', email).toPromise()
        promise.then((res)=>{
          this.spinner.hide()
          if(res.status.remarks){      
            Swal.fire({
            icon: 'error',
            title:'<h2>This email is<br><strong><u>already registered!</u></strong></h2>',
            text: `This email is already registered to another applicant. Please enter another email or if you own this email, check your inbox if you already recieved a reply from us.`
          }).then(() => {
            this.emailNew = ''
          })}
        })
      }
   
    })
   
  }

  confirmEmail(){
    if(this.emailNew != this.emailConfirm){
      this.matched = false
      return "Email confirmation did not match!"
    } else{
      this.matched = true
      return "Email confirmation matched!"
    }
  }

  updateEmail(student){
    this.spinner.show()
    if(this.matched){
      this.student.si_email = this.emailNew
      let promise = this.ds.sendRequest('updateEmail', this.student).toPromise()
      promise.then(res=>{
        this.spinner.hide()
        if(res[0]=='success'){
          Swal.fire({
            icon: 'success',
            title:'Email Changed!',
            html: `Email changed from <strong>${this.emailOld}</strong> to <strong>${this.emailNew}</strong> for Student: <strong>${this.student.gc_idnumber}</strong>.`
          }).then(() => {
            this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
            this.log.activity = `Changed email from ${this.emailOld} to ${this.emailNew} for ${this.student.si_fullname} ID Number: ${this.student.gc_idnumber}.`
            this.log.idnumber = this.credentials.data[0].fa_empnumber
            this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
            this.log.department = this.credentials.data[0].fa_department
            this.ds.sendLog(this.log)
            this.dialogRef.close()
          })}
      })
    } else{
      Swal.fire({
        icon: 'error',
        title:'Confirmation Email did not match!'
      })
    }
  }
}
