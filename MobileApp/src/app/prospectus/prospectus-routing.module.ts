import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ProspectusPage } from './prospectus.page';

const routes: Routes = [
  {
    path: '',
    component: ProspectusPage
    // children: [
    //   {
    //     path: 'year',
    //     loadChildren: () => import('../year/year.module').then( m => m.YearPageModule)
    //   }
    // ]
  }
  // {
  //   path: '',
  //   redirectTo: 'prospectus/year'
  // }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ProspectusPageRoutingModule {}
