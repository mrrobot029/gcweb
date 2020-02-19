import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { ProfileComponent } from './profile/profile.component';
import { ProfilyComponent } from './profily/profily.component';
import {  CahsnewComponent } from './cahsnew/cahsnew.component';
import {  CahsoldComponent } from './cahsold/cahsold.component';
import { CbanewComponent  } from './cbanew/cbanew.component';
import { CbaoldComponent  } from './cbaold/cbaold.component';
import { CcsnewComponent  } from './ccsnew/ccsnew.component';
import { CcsoldComponent  } from './ccsold/ccsold.component';
import { CeasnewComponent  } from './ceasnew/ceasnew.component';
import { CeasoldComponent  } from './ceasold/ceasold.component';
import { ChtmnewComponent  } from './chtmnew/chtmnew.component';
import { ChtmoldComponent  } from './chtmold/chtmold.component';
import { CollegeprofileComponent } from './collegeprofile/collegeprofile.component';
import { PortalsComponent } from './portals/portals.component';
import { HymnComponent } from './hymn/hymn.component';
import { UnderconstructionComponent } from './underconstruction/underconstruction.component';
import { ContactComponent } from './contact/contact.component';
import { IgsComponent } from './igs/igs.component';
import { BoardComponent } from './board/board.component';
import { AdminComponent } from './admin/admin.component';
import { OrgComponent } from './org/org.component';
import { CahsComponent } from './cahs/cahs.component';
import { CbaComponent } from './cba/cba.component';
import { CcsComponent } from './ccs/ccs.component';
import { CeasComponent } from './ceas/ceas.component';
import { ChtmComponent } from './chtm/chtm.component';
import { ShsComponent } from './shs/shs.component';
import { AdmissionComponent } from './admission/admission.component';
import { CultureComponent } from './culture/culture.component';
import { DisciplineComponent } from './discipline/discipline.component';
import { GuidanceComponent } from './guidance/guidance.component';
import { HealthComponent } from './health/health.component';
import { SportsComponent } from './sports/sports.component';
import { SchoolorgsComponent } from './schoolorgs/schoolorgs.component';
import { StudentplacementComponent } from './studentplacement/studentplacement.component';
import { ForefrontComponent } from './forefront/forefront.component';
import { NotavailableComponent } from './notavailable/notavailable.component';
import { Notavailable2Component } from './notavailable2/notavailable2.component';
import { ApplicationComponent } from './application/application.component';
import { GcatregComponent } from './gcatreg/gcatreg.component';
import { EditComponent } from './edit/edit.component';
import { from } from 'rxjs';


const routes: Routes = [
  {path: '', redirectTo: 'gc', pathMatch: 'full' },
  {path: 'gc', component: HomeComponent},
  {path: 'seal', component: ProfileComponent},
  {path: 'enlistment', component: ProfilyComponent},
  {path: 'cahsprofiling', component: CahsnewComponent},
  {path: 'cahsenlistment', component: CahsoldComponent},
  {path: 'cbaprofiling', component: CbanewComponent},
  {path: 'cbaenlistment', component: CbaoldComponent},
  {path: 'ccsprofiling', component: CcsnewComponent},
  {path: 'ccsenlistment', component: CcsoldComponent},
  {path: 'ceasprofiling', component: CeasnewComponent},
  {path: 'ceasenlistment', component: CeasoldComponent},
  {path: 'chtmprofiling', component: ChtmnewComponent},
  {path: 'chtmenlistment', component: ChtmoldComponent},
  {path: 'collegeprofile', component: CollegeprofileComponent},
  {path: 'portals', component: PortalsComponent},
  {path: 'hymn', component: HymnComponent},
  {path: 'error', component: UnderconstructionComponent},
  {path: 'contact', component: ContactComponent},
  {path: 'igs', component: IgsComponent},
  {path: 'board', component: BoardComponent},
  {path: 'admin', component: AdminComponent},
  {path: 'org', component: OrgComponent},
  {path: 'cahs', component: CahsComponent},
  {path: 'cba', component: CbaComponent},
  {path: 'ccs', component: CcsComponent},
  {path: 'ceas', component: CeasComponent},
  {path: 'chtm', component: ChtmComponent},
  {path: 'shs', component: ShsComponent},
  {path: 'admission', component: AdmissionComponent},
  {path: 'cultureandarts', component: CultureComponent},
  {path: 'discipline', component: DisciplineComponent},
  {path: 'guidance', component: GuidanceComponent},
  {path: 'healthsvcs', component: HealthComponent},
  {path: 'sports', component: SportsComponent},
  {path: 'schoolorg', component: SchoolorgsComponent},
  {path: 'studentplacement', component: StudentplacementComponent},
  {path: 'forefront', component: ForefrontComponent},
  {path: 'notavailable', component: NotavailableComponent},
  {path: 'registrationerror', component: Notavailable2Component},
  {path: 'application', component: ApplicationComponent},
  {path: 'gcatregistration', component: GcatregComponent},
  {path: 'edit/:id/:key', component: EditComponent}

]
@NgModule({
  imports: [
    CommonModule,
    RouterModule.forRoot(routes)
  ],
  exports:[RouterModule],
  declarations: []
})
export class AppRoutingModule { }
