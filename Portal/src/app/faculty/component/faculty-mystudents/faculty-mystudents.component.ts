import { Component, OnInit, ViewChild } from '@angular/core';
import { DataService } from '../../../services/data.service';
import { MatTableDataSource} from '@angular/material/table';
import { MatPaginator, MatSort } from '@angular/material';


@Component({
  selector: 'app-faculty-mystudents',
  templateUrl: './faculty-mystudents.component.html',
  styleUrls: ['./faculty-mystudents.component.scss']
})
export class FacultyMystudentsComponent implements OnInit {

  students: any = {};
  FacultyInfo: any = {};
  credFaculty: any = {};
  settings: any = {};

  displayedColumns: string[] = ['si_idnumber', 'si_fullname', 'si_course', 'si_department'];
  dataSource: any;

  @ViewChild(MatPaginator, {static: true}) paginator: MatPaginator;
  @ViewChild(MatSort, {static: true}) sort: MatSort

  constructor(private ds: DataService) { }

  ngOnInit() {
    this.credFaculty = JSON.parse(localStorage.getItem('gcweb_faculty'));
    this.ds.sendRequest('getSettings', this.FacultyInfo).subscribe((res) => {
      this.settings = res;
    });
    this.checktype()
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  checktype(){
    if(this.credFaculty.data[0].fa_accounttype == 2){
      this.getCoordProgram()
    }else{
      this.FacultyInfo['department'] = this.credFaculty.data[0].fa_department;
      this.ds.sendRequest('students', this.FacultyInfo).subscribe((res) => {
      this.students = res;
      this.dataSource = new MatTableDataSource(this.students.data);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });
    }
  }

  getCoordProgram(){
    this.FacultyInfo['program'] = this.credFaculty.data[0].fa_program;
    this.ds.sendRequest('coordstudents', this.FacultyInfo).subscribe((res) => {
      this.students = res;
      this.dataSource = new MatTableDataSource(this.students.data);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });
  }

}
