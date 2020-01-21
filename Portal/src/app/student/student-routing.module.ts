import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { StudentSchedComponent } from './component/student-sched/student-sched.component';
import { StudentProspectusComponent } from './component/student-prospectus/student-prospectus.component';
import { StudentProfileComponent } from './component/student-profile/student-profile.component';


const routes: Routes = [
  { path : '' , redirectTo: 'myprofile', pathMatch: 'full'},
  { path : 'myclasses', component: StudentSchedComponent },
  { path : 'myprospectus', component: StudentProspectusComponent },
  { path : 'myprofile', component: StudentProfileComponent },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class StudentRoutingModule { }
