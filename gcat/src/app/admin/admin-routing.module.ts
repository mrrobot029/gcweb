import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { ApplicantsComponent } from "./component/applicants/applicants.component";
import { UsersComponent } from "./component/users/users.component";
import { ScheduledComponent } from "./component/scheduled/scheduled.component";
import { ConfirmedapplicantsComponent } from "./component/confirmedapplicants/confirmedapplicants.component";
import { AuthGuard } from "../services/auth.guard";
import { AllapplicantsComponent } from './component/allapplicants/allapplicants.component';

const routes: Routes = [
  { path: "", redirectTo: "applicants", pathMatch: "full" },
  {
    path: "users",
    component: UsersComponent,
    canActivate: [AuthGuard]
  },
  {
    path: "applicants",
    component: AllapplicantsComponent,
    canActivate: [AuthGuard]
  },
  {
    path: "unconfirmed",
    component: ApplicantsComponent,
    canActivate: [AuthGuard]
  },
  {
    path: "unscheduled",
    component: ConfirmedapplicantsComponent,
    canActivate: [AuthGuard]
  },
  {
    path: "scheduled",
    component: ScheduledComponent,
    canActivate: [AuthGuard]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AdminRoutingModule {}
