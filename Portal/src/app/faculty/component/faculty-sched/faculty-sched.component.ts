import { Component, OnInit } from '@angular/core';
import { DataService } from 'src/app/services/data.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-faculty-sched',
  templateUrl: './faculty-sched.component.html',
  styleUrls: ['./faculty-sched.component.scss']
})
export class FacultySchedComponent implements OnInit {

  userinfo: any = JSON.parse(localStorage.getItem('gcweb_faculty'));
  classinfo: any = {};
  classes: any = {};
  students: any = {};
  settings: any = {};
  classIdModal = '';
  fileModal = '';
  sy = '';


  constructor(private ds: DataService) { }

  ngOnInit() {
    this.ds.sendRequest('getSettings', this.userinfo).subscribe((res) => {
      this.settings = res;
      this.sy = this.settings.data[0].en_schoolyear;
      this.getMyClass();
    });
  }

  getMyClass() {
    this.userinfo.clSY = this.settings.data[0].en_schoolyear;
    this.userinfo.clSem = this.settings.data[0].en_sem;
    console.log(this.userinfo);
    this.ds.sendRequest('getMyClass', this.userinfo).subscribe((res) => {
      this.classes = res;
    });
  }

  updateIsNormal(a, b) {
    this.userinfo.a = a;
    this.userinfo.b = b;
    this.ds.sendRequest('updateIsNormal', this.userinfo ).subscribe((res) => {
      this.getMyClass();
    });
  }

  getClassStudents(classId) {
    this.classIdModal = classId;
    this.classinfo.classId = classId;
    this.ds.sendRequest('getClassStudents', this.classinfo).subscribe((res) => {
      this.students = res;
    });
  }

  uploadGrades(e) {
    const fd = new FormData();
    e.preventDefault();
    console.log(e);

    fd.append('classId', e.target[0].value);
    fd.append('file', e.target[1].files[0], e.target[1].files[0].name);

    this.ds.sendRequestWithFile('uploadGrade', fd).subscribe((res) => {
      if (res.status.remarks) {
        Swal.fire({
          text: res.status.message,
          icon: 'success'
        }).then(() => {
          this.getClassStudents(e.target[0].value);
          this.fileModal = '';
        });
      } else {
        Swal.fire({
          text: res.status.message,
          icon: 'error'
        });
      }
    });
  }
}
