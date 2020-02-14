import { Component, OnInit } from '@angular/core';
import { DataService } from 'src/app/services/data.service';

@Component({
  selector: 'app-applicants',
  templateUrl: './applicants.component.html',
  styleUrls: ['./applicants.component.scss']
})
export class ApplicantsComponent implements OnInit {

  constructor(private ds: DataService) { }

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

}
