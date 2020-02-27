import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AdminRoutingModule } from './admin-routing.module';
import { DataService } from '../services/data.service';
import { FormsModule } from '@angular/forms';
import { UsersComponent } from './component/users/users.component';
import { ApplicantsComponent } from './component/applicants/applicants.component';
import { NgxPaginationModule } from 'ngx-pagination';
import { ScheduledComponent } from './component/scheduled/scheduled.component';
import { NgxSpinnerModule } from "ngx-spinner";
import { MatTooltipModule } from '@angular/material/tooltip';
import { ConfirmedapplicantsComponent } from './component/confirmedapplicants/confirmedapplicants.component';
import { EventEmitterService } from './event-emitter.service';


@NgModule({
  declarations: [
    UsersComponent,
    ApplicantsComponent,
    ScheduledComponent,
    ConfirmedapplicantsComponent,
  ],
  imports: [
    CommonModule,
    AdminRoutingModule,
    FormsModule,
    NgxPaginationModule,
    NgxSpinnerModule,
    MatTooltipModule
  ],
  providers: [
    DataService,
    EventEmitterService
  ]
})
export class AdminModule { }
