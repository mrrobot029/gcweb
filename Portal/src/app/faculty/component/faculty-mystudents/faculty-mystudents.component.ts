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
  AY = '';

  displayedColumns: string[] = ['si_idnumber', 'si_fullname', 'si_yrlevel', 'si_sem', 'si_course', 'si_block', 'si_department'];
  dataSource: any;

  @ViewChild(MatPaginator, {static: true}) paginator: MatPaginator;
  @ViewChild(MatSort, {static: true}) sort: MatSort;

  constructor(private ds: DataService) { }

  ngOnInit() {
    this.credFaculty = JSON.parse(localStorage.getItem('gcweb_faculty'));
    this.ds.sendRequest('getSettings', this.FacultyInfo).subscribe((res) => {
      this.settings = res;
      this.AY = this.settings.data[0].en_schoolyear;
    });
    this.checktype();
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  checktype() {

    if (this.credFaculty.data[0].fa_accounttype === '0') {
        this.FacultyInfo.facId = this.credFaculty.data[0].fa_empnumber;
        this.ds.sendRequest('getFacStudents', this.FacultyInfo).subscribe((res) => {
            this.students = res;
            this.dataSource = new MatTableDataSource(this.students.data);
            this.dataSource.paginator = this.paginator;
            this.dataSource.sort = this.sort;
        });
    } else if (this.credFaculty.data[0].fa_accounttype ===  '1') {
        this.FacultyInfo.department = this.credFaculty.data[0].fa_department;
        this.ds.sendRequest('getAdminStudents', this.FacultyInfo).subscribe((res) => {
            this.students = res;
            this.dataSource = new MatTableDataSource(this.students.data);
            this.dataSource.paginator = this.paginator;
            this.dataSource.sort = this.sort;
        });
    } else if (this.credFaculty.data[0].fa_accounttype === '2') {
      this.FacultyInfo.program = this.credFaculty.data[0].fa_program;
      this.ds.sendRequest('getCoordinatorStudents', this.FacultyInfo).subscribe((res) => {
          this.students = res;
          this.dataSource = new MatTableDataSource(this.students.data);
          this.dataSource.paginator = this.paginator;
          this.dataSource.sort = this.sort;
      });
    }
  }

}
