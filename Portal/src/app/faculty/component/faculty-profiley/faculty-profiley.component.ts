import { Component, OnInit } from '@angular/core';
import { DataService } from 'src/app/services/data.service';
import Swal from 'sweetalert2';
import { StudentDialogComponent } from '../../../student-dialog/student-dialog.component';
import { ReportsDialogComponent } from '../../../reports-dialog/reports-dialog.component';
import { EditDialogComponent } from '../../../edit-dialog/edit-dialog.component';
import { SettingsDialogComponent } from '../../../settings-dialog/settings-dialog.component';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';
@Component({
  selector: 'app-faculty-profiley',
  templateUrl: './faculty-profiley.component.html',
  styleUrls: ['./faculty-profiley.component.scss']
})
export class FacultyProfileyComponent implements OnInit {

  studInfo: any = {};
  searchInfo: any = {};
  actClasses: any = {};
  enClasses: any = {};
  courseBlocks: any = {};
  searchicon = 'search';


  constructor(private ds: DataService, public dialog: MatDialog) { }

  ngOnInit() {
    this.searchInfo.searchClass = '';
    this.getSettings();
  }

  studentList(){
    const dialogRef = this.dialog.open(StudentDialogComponent, {
      width: '80vw',
      height: '90vh'
    });
  }

 reports(){
    const dialogRef = this.dialog.open(ReportsDialogComponent, {
      width: '80vw',
      height: '90vh'
    });
  }

  settings(){
    const dialogRef = this.dialog.open(SettingsDialogComponent, {
      width: '80vw',
      height: '90vh'
    });
  }

  getSettings() {
    this.ds.sendRequest('getSettings', this.searchInfo).subscribe((res) => {
      this.searchInfo.actSem = res.data[0].en_sem;
      this.searchInfo.actSY = res.data[0].en_schoolyear;
      this.getActiveClasses();

    });
  }

  searchStudent(e) {
    e.preventDefault();
    this.searchInfo.idNumber = e.target[0].value;
    this.ds.sendRequest('getStudent', this.searchInfo).subscribe((res) => {
      if (res.status.remarks) {
        this.studInfo = res.data[0];
        this.getEnrolledClasses();
        this.getCourseBlocks();
        this.searchicon = 'edit'
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Not Found',
          text: 'Invalid student number.'
        }).then(() => {
          this.searchicon = 'search'
      });
      }
    });
  }

  edit(){
    const dialogRef = this.dialog.open(EditDialogComponent, {
      width: '100vw',
      height: '95vh',
      data: { id: this.studInfo.si_idnumber,
              recno: this.studInfo.si_recno
            },
    });
    dialogRef.afterClosed().subscribe(() => {
      // Do stuff after the dialog has closed
      this.searchInfo.idNumber = this.studInfo.si_idnumber;
    this.ds.sendRequest('getStudent', this.searchInfo).subscribe((res) => {
      if (res.status.remarks) {
        this.studInfo = res.data[0];
        this.getEnrolledClasses();
        this.getCourseBlocks();
        this.searchicon = 'edit'
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Not Found',
          text: 'Invalid student number.'
        }).then(() => {
          this.searchicon = 'search'
      });
      }
    });
  });
  }

  searchActiveClasses(e) {
    this.searchInfo.searchClass = e.target.value;
    this.getActiveClasses();
  }

  getActiveClasses() {
    this.ds.sendRequest('getActiveClasses', this.searchInfo).subscribe((res) => {
      this.actClasses = res;
    });
  }

  getEnrolledClasses() {
    this.ds.sendRequest('getEnrolledClasses', this.searchInfo).subscribe((res) => {
      this.enClasses = res;
    });
  }


  enrollSingleClass(i) {
    if (this.studInfo.si_idnumber == null || this.studInfo.si_idnumber === '') {
      Swal.fire({
        icon: 'error',
        title: 'Adding Failed',
        text: 'Undefined student information.'
      });
    } else {
      this.searchInfo.classCode = this.actClasses.data[i].cl_code;
      this.searchInfo.subjectCode = this.actClasses.data[i].cl_sucode;
      this.searchInfo.block = this.actClasses.data[i].cl_block;
      this.ds.sendRequest('enrollSingleClass', this.searchInfo).subscribe((res) => {
        if (res.status.remarks) {
          this.getEnrolledClasses();
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Adding failed',
            text: res.status.message
          });
        }
      });
    }
  }

  removeEnrolledSubject(i) {
    this.searchInfo.recno = this.enClasses.data[i].es_recno;
    this.ds.sendRequest('removeEnrolledSubject', this.searchInfo).subscribe((res) => {
      if (res.status.remarks) {
        this.getEnrolledClasses();
      }
    });
  }

  getCourseBlocks() {
    this.searchInfo.si_course = this.studInfo.si_course
    this.ds.sendRequest('getCourseBlocks', this.searchInfo).subscribe((res) => {
      this.courseBlocks = res;
      console.log(res)
    });
  }

  enrollByBlock(e) {
    console.log(e.target.value);
    this.studInfo.blockSelected = e.target.value;
    this.ds.sendRequest('enrollByBlock', this.studInfo).subscribe((res) => {
      this.getEnrolledClasses();
    });

  }

}
