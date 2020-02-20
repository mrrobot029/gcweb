import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AdminRoutingModule } from './admin-routing.module';
import { DataService } from '../services/data.service';
import { FormsModule } from '@angular/forms';
import { AdminPage1Component } from './component/admin-page1/admin-page1.component';
import { AdminPage2Component } from './component/admin-page2/admin-page2.component';
import { AdminPage3Component } from './component/admin-page3/admin-page3.component';
import { UsersComponent } from './component/users/users.component';
import { ApplicantsComponent } from './component/applicants/applicants.component';
import {NgxPaginationModule} from 'ngx-pagination';
import { ScheduledComponent } from './component/scheduled/scheduled.component';
import { NgxSpinnerModule } from "ngx-spinner";


@NgModule({
  declarations: [
    AdminPage1Component,
    AdminPage2Component,
    AdminPage3Component,
    UsersComponent,
    ApplicantsComponent,
    ScheduledComponent,
  ],
  imports: [
    CommonModule,
    AdminRoutingModule,
    FormsModule,
    NgxPaginationModule,
    NgxSpinnerModule
  ],
  providers: [
    DataService
  ]
})
export class AdminModule { }
