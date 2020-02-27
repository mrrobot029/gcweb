import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { NavbarPage } from './navbar.page';

const routes: Routes = [
  {
    path: '',
    component: NavbarPage,
    children: [
      {
        path: 'sched',
        loadChildren: () => import('../sched/sched.module').then( m => m.SchedPageModule)
    },
    {
        path: 'prospectus',
        loadChildren: () => import('../prospectus/prospectus.module').then( m => m.ProspectusPageModule)
    },
    {
        path: 'profile',
        loadChildren: () => import('../profile/profile.module').then( m => m.ProfilePageModule)
    }
    ]
  },
  {
    path: '',
    redirectTo: '/menu/prospectus'
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class NavbarPageRoutingModule {}
