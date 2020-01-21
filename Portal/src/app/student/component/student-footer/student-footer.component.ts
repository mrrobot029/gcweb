import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-student-footer',
  templateUrl: './student-footer.component.html',
  styleUrls: ['./student-footer.component.scss']
})
export class StudentFooterComponent implements OnInit {

  test: Date = new Date();

  constructor() { }

  ngOnInit() {
  }

}
