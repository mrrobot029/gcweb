import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AdminRoutingModule } from './admin-routing.module';
import { DataService } from '../services/data.service';
import { FormsModule } from '@angular/forms';
import { AdminPage1Component } from './component/admin-page1/admin-page1.component';
import { AdminPage2Component } from './component/admin-page2/admin-page2.component';
import { AdminPage3Component } from './component/admin-page3/admin-page3.component';

@NgModule({
  declarations: [
    AdminPage1Component,
    AdminPage2Component,
    AdminPage3Component,
  ],
  imports: [
    CommonModule,
    AdminRoutingModule,
    FormsModule
  ],
  providers: [
    DataService
  ]
})
export class AdminModule { }
