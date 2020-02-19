import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { RouterModule } from '@angular/router';

// for admin
import { AdminLayoutComponent } from './admin/admin-layout/admin-layout.component';
import { AdminSidebarComponent } from './admin/component/admin-sidebar/admin-sidebar.component';
import { AdminHeaderComponent } from './admin/component/admin-header/admin-header.component';
import { AdminFooterComponent } from './admin/component/admin-footer/admin-footer.component';


// for faculty
import { FacultyLayoutComponent } from './faculty/faculty-layout/faculty-layout.component';
import { FacultyHeaderComponent } from './faculty/component/faculty-header/faculty-header.component';
import { FacultySidebarComponent } from './faculty/component/faculty-sidebar/faculty-sidebar.component';
import { FacultyFooterComponent } from './faculty/component/faculty-footer/faculty-footer.component';


// for student
import { StudentLayoutComponent } from './student/student-layout/student-layout.component';
import { StudentSidebarComponent } from './student/component/student-sidebar/student-sidebar.component';
import { StudentFooterComponent } from './student/component/student-footer/student-footer.component';
import { StudentHeaderComponent } from './student/component/student-header/student-header.component';



import { LoginComponent } from './login/login.component';
import { AdminModule } from './admin/admin.module';
import { FacultyModule } from './faculty/faculty.module';
import { StudentModule } from './student/student.module';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HttpClientModule } from '@angular/common/http';
import { StudentDialogComponent } from './student-dialog/student-dialog.component';
import { MatDialogModule } from '@angular/material';
import { MatTableModule } from '@angular/material/table';
import { MatPaginatorModule } from '@angular/material/paginator';
import { MatSortModule } from '@angular/material/sort';
import {NgxPaginationModule} from 'ngx-pagination';
import { EditDialogComponent } from './edit-dialog/edit-dialog.component';
import {MatStepperModule} from '@angular/material/stepper';
import { MatRadioModule } from '@angular/material/radio';
import {MatCheckboxModule} from '@angular/material/checkbox';
import { MatSelectModule } from '@angular/material/select';
import { NgxCleaveDirectiveModule } from 'ngx-cleave-directive';
import {MatButtonModule} from '@angular/material';
import {MatInputModule} from '@angular/material/input';
import {MatAutocompleteModule} from '@angular/material/autocomplete';
import {MatDatepickerModule} from '@angular/material/datepicker';
import {MatFormFieldModule} from '@angular/material/form-field';
import {MatSliderModule} from '@angular/material/slider';
import {MatSlideToggleModule} from '@angular/material/slide-toggle';
import {MatMenuModule} from '@angular/material/menu';
import {MatSidenavModule} from '@angular/material/sidenav';
import {MatToolbarModule} from '@angular/material/toolbar';
import {MatListModule} from '@angular/material/list';
import {MatGridListModule} from '@angular/material/grid-list';
import {MatCardModule} from '@angular/material/card';
import {MatTabsModule} from '@angular/material/tabs';
import {MatExpansionModule} from '@angular/material/expansion';
import {MatButtonToggleModule} from '@angular/material/button-toggle';
import {MatChipsModule} from '@angular/material/chips';
import {MatIconModule} from '@angular/material/icon';
import {MatProgressSpinnerModule} from '@angular/material/progress-spinner';
import {MatProgressBarModule} from '@angular/material/progress-bar';
import {MatTooltipModule} from '@angular/material/tooltip';
import {MatSnackBarModule} from '@angular/material/snack-bar';
import { IdnumberDialogComponent } from './idnumber-dialog/idnumber-dialog.component';
import { ReportsDialogComponent } from './reports-dialog/reports-dialog.component';
import { SettingsDialogComponent } from './settings-dialog/settings-dialog.component';
import { HashLocationStrategy, LocationStrategy  } from '@angular/common';




@NgModule({
  declarations: [
    AppComponent,
    AdminLayoutComponent,
    AdminSidebarComponent,
    AdminHeaderComponent,
    AdminFooterComponent,
    FacultyLayoutComponent,
    FacultyHeaderComponent,
    FacultySidebarComponent,
    FacultyFooterComponent,
    StudentLayoutComponent,
    StudentSidebarComponent,
    StudentFooterComponent,
    StudentHeaderComponent,
    LoginComponent,
    StudentDialogComponent,
    EditDialogComponent,
    IdnumberDialogComponent,
    ReportsDialogComponent,
    SettingsDialogComponent,
  ],
  imports: [
    BrowserAnimationsModule,
    BrowserModule,
    AppRoutingModule,
    AdminModule,
    FacultyModule,
    StudentModule,
    HttpClientModule,
    RouterModule,
    MatDialogModule,
    MatTableModule,
    MatPaginatorModule,
    MatSortModule,
    NgxPaginationModule,
    FormsModule,
    ReactiveFormsModule,
    MatSelectModule,
    MatStepperModule,
    MatRadioModule,
    MatCheckboxModule,
    NgxCleaveDirectiveModule,
    MatCheckboxModule,
    MatCheckboxModule,
    MatButtonModule,
    MatInputModule,
    MatAutocompleteModule,
    MatDatepickerModule,
    MatFormFieldModule,
    MatRadioModule,
    MatSelectModule,
    MatSliderModule,
    MatSlideToggleModule,
    MatMenuModule,
    MatSidenavModule,
    MatToolbarModule,
    MatListModule,
    MatGridListModule,
    MatCardModule,
    MatStepperModule,
    MatTabsModule,
    MatExpansionModule,
    MatButtonToggleModule,
    MatChipsModule,
    MatIconModule,
    MatProgressSpinnerModule,
    MatProgressBarModule,
    MatDialogModule,
    MatTooltipModule,
    MatSnackBarModule,
    MatTableModule,
    MatSortModule,
    MatPaginatorModule
  ],
  providers: [
    {provide : LocationStrategy , useClass: HashLocationStrategy}
  ],
  entryComponents: [ StudentDialogComponent, EditDialogComponent, IdnumberDialogComponent, ReportsDialogComponent, SettingsDialogComponent ],
  bootstrap: [AppComponent]
})
export class AppModule { }
