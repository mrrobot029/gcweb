import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { StudentRoutingModule } from './student-routing.module';
import { StudentProfileComponent } from './component/student-profile/student-profile.component';
import { StudentSchedComponent } from './component/student-sched/student-sched.component';
import { StudentProspectusComponent } from './component/student-prospectus/student-prospectus.component';

import { 
  MatButtonModule,
  MatInputModule,
  MatRippleModule,
  MatFormFieldModule,
  MatTooltipModule,
  MatSelectModule,
  MatTable,
  MatTableDataSource,
  MatPaginator,
  MatTableModule,
  MatPaginatorModule,
  MatSortModule
 } from '@angular/material';

@NgModule({
  declarations: [StudentProfileComponent, StudentSchedComponent, StudentProspectusComponent],
  imports: [
    CommonModule,
    StudentRoutingModule,
    MatButtonModule,
    MatInputModule,
    MatFormFieldModule,
    MatTooltipModule,
    MatSelectModule,
    MatTableModule,
    MatPaginatorModule,
    MatSortModule,
    FormsModule
  ]
})
export class StudentModule { }
