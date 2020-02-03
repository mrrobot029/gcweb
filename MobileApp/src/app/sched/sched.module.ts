import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { SchedPageRoutingModule } from './sched-routing.module';

import { SchedPage } from './sched.page';
import { NavbarPage } from '../navbar/navbar.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    SchedPageRoutingModule
  ],
  declarations: [SchedPage, NavbarPage]
})
export class SchedPageModule {}
