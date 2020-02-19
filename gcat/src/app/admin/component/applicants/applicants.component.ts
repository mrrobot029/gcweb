import { Component, OnInit } from '@angular/core';
import { DataService } from 'src/app/services/data.service';
import { NgxSpinnerService } from "ngx-spinner";
import Swal from 'sweetalert2'

@Component({
  selector: 'app-applicants',
  templateUrl: './applicants.component.html',
  styleUrls: ['./applicants.component.scss']
})
export class ApplicantsComponent implements OnInit {
p = 1
  constructor(private ds: DataService, private spinner: NgxSpinnerService) { }

  applicants:any = {};

  ngOnInit() {
    this.gcatApplicants()
  }

  gcatApplicants(){
    this.ds.sendRequest('getApplicants', null).subscribe(res => {
      console.log(res.data);
      this.applicants = res.data;
    });
  }

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

}
