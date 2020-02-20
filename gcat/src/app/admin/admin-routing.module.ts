import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AdminPage1Component } from './component/admin-page1/admin-page1.component';
import { AdminPage2Component } from './component/admin-page2/admin-page2.component';
import { AdminPage3Component } from './component/admin-page3/admin-page3.component';
import { ApplicantsComponent } from './component/applicants/applicants.component';
import { UsersComponent } from './component/users/users.component';
import { ScheduledComponent } from './component/scheduled/scheduled.component'
import { ConfirmedapplicantsComponent } from './component/confirmedapplicants/confirmedapplicants.component'


const routes: Routes = [
  { path : '' , redirectTo: 'applicants', pathMatch: 'full'},
  { path : 'users', component: UsersComponent },
  { path : 'applicants', component: ApplicantsComponent},
  { path : 'unscheduled', component: ConfirmedapplicantsComponent},
  { path : 'scheduled', component: ScheduledComponent},
  { path : 'page1', component: AdminPage1Component },
  { path : 'page2', component: AdminPage2Component },
  { path : 'page3', component: AdminPage3Component },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AdminRoutingModule { }
