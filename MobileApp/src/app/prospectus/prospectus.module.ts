import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ProspectusPageRoutingModule } from './prospectus-routing.module';

import { ProspectusPage } from './prospectus.page';
import { NavbarPage } from '../navbar/navbar.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ProspectusPageRoutingModule
  ],
  declarations: [ProspectusPage, NavbarPage]
})
export class ProspectusPageModule {}
