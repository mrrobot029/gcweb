import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { AdminLayoutComponent } from './admin/admin-layout/admin-layout.component';
import { AuthGuard } from "./services/auth.guard"
import { AuthService } from './services/auth.service'


const routes: Routes = [
  { path: '', redirectTo: 'login', pathMatch: 'full' },
  { path: 'login', component: LoginComponent, },
  {
    path: 'admin',
    component: AdminLayoutComponent,
    children: [{
      path: '',
      loadChildren: './admin/admin.module#AdminModule'
    }],
    canActivate: [AuthGuard]
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
