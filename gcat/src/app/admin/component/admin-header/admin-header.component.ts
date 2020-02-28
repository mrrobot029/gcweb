import { Component, OnInit, ElementRef } from '@angular/core';
import Swal from 'sweetalert2';
import { Router } from '@angular/router';
import { DataService } from "src/app/services/data.service";
import { AuthService } from 'src/app/services/auth.service';
import { formatDate } from '@angular/common';
import { NgxSpinnerService } from 'ngx-spinner';

@Component({
  selector: 'app-admin-header',
  templateUrl: './admin-header.component.html',
  styleUrls: ['./admin-header.component.scss']
})
export class AdminHeaderComponent implements OnInit {
  log:any = {}
  now = new Date();
  credentials: any = {};
  credType: any = "";
  settings
  queryVar: any = {};

  date = new Date()
  applicantCount
  credAdmin: any = {};
  mobile_menu_visible: any = 0;
  private toggleButton: any;
  private sidebarVisible: boolean;

  constructor(private router: Router, private element: ElementRef, private ds: DataService, private auth: AuthService, private spinner: NgxSpinnerService) {
    this.sidebarVisible = false;
  }

  ngOnInit() {
    // const [unconfirmedCount, confirmedCount, scheduledCount] = this.ds.getCount()
    this.ds.sendRequest('getApplicantCount', null).subscribe(res=>{
      this.applicantCount = res.data[0].applicantcount;
    })

    this.ds.sendRequest('getSettings', null).subscribe(res=>{
      this.settings = res.data[0];
    })

    this.credentials = JSON.parse(localStorage.getItem('gcweb_GCAT'));
    if (this.credentials !== null) {
      this.credType = this.credentials.data[0].fa_department;
    }
    setInterval(() => {
      this.date = new Date;
    }, 1000);
    setInterval(() => {
      this.ds.sendRequest('getApplicantCount', null).subscribe(res=>{
        this.applicantCount = res.data[0].applicantcount;
      })
    }, 60000);
    this.credAdmin = JSON.parse(localStorage.getItem('gcweb_GCAT'));
    const navbar: HTMLElement = this.element.nativeElement;
    this.toggleButton = navbar.getElementsByClassName('navbar-toggler')[0];
  }

  getDuplicates() {
    let promise = this.ds.sendRequest('getDuplicateApplications', null).toPromise()
    promise.then(res => {
    })
  }

  logout() {
    Swal.fire({
      title: 'Log Out',
      icon: 'warning',
      text:
        'You are about to leave this portal?',
      showConfirmButton: true,
      showCancelButton: true,
      confirmButtonText:
        'Proceed',
      cancelButtonText:
        'Cancel'
    }).then((res) => {
      if (res.value) {
        this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
        this.log.activity = "User logged out."
        this.log.idnumber = this.credentials.data[0].fa_empnumber
        this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
        this.log.department = this.credentials.data[0].fa_department
        this.ds.sendLog(this.log)
        localStorage.removeItem('gcweb_GCAT');
        this.auth.setUserLoggedIn(false);
        this.router.navigate(['/login']);
      }
    });
  }


  updatePassword(e) {
    e.preventDefault();
    if (e.target.elements[1].value === e.target.elements[2].value) {
      this.queryVar.fa_recno = this.credentials.data[0].fa_recno;
      this.queryVar.oldPass = e.target.elements[0].value
      this.queryVar.newPass = e.target.elements[1].value
      this.queryVar.accType = 0;
      this.ds.sendRequest("updatePassword", this.queryVar).subscribe(res => {
        if (res.status.remarks) {
          this.ds.callSwal("Update success.", res.status.message, "success").then(()=>this.ngOnInit());
        } else {
          this.ds.callSwal("Update failed.", res.status.message, "error").then(()=>this.ngOnInit());
        }
      });
    } else {
      this.ds.callSwal("Update failed.", "Confirm password does not match.", "error").then(()=>this.ngOnInit());
    }


  }


  sidebarOpen() {
    const toggleButton = this.toggleButton;
    const body = document.getElementsByTagName('body')[0];
    setTimeout(() => {
      toggleButton.classList.add('toggled');
    }, 500);

    body.classList.add('nav-open');
    this.sidebarVisible = true;
  }


  sidebarClose() {
    const body = document.getElementsByTagName('body')[0];
    this.toggleButton.classList.remove('toggled');
    this.sidebarVisible = false;
    body.classList.remove('nav-open');
  }

  sidebarToggle() {
    var $toggle = document.getElementsByClassName('navbar-toggler')[0];

    if (this.sidebarVisible === false) {
      this.sidebarOpen();
    } else {
      this.sidebarClose();
    }

    const body = document.getElementsByTagName('body')[0];

    if (this.mobile_menu_visible === 1) {

      body.classList.remove('nav-open');
      if ($layer) {
        $layer.remove();
      }
      setTimeout(() => {
        $toggle.classList.remove('toggled');
      }, 400);
      this.mobile_menu_visible = 0;
    } else {
      setTimeout(() => {
        $toggle.classList.add('toggled');
      }, 430);

      var $layer = document.createElement('div');
      $layer.setAttribute('class', 'close-layer');

      if (body.querySelectorAll('.main-panel')) {
        document.getElementsByClassName('main-panel')[0].appendChild($layer);
      } else if (body.classList.contains('off-canvas-sidebar')) {
        document.getElementsByClassName('wrapper-full-page')[0].appendChild($layer);
      }

      setTimeout(() => {
        $layer.classList.add('visible');
      }, 100);

      $layer.onclick = function () {

        body.classList.remove('nav-open');
        this.mobile_menu_visible = 0;
        $layer.classList.remove('visible');
        setTimeout(() => {
          $layer.remove();
          $toggle.classList.remove('toggled');
        }, 400);

      }.bind(this);

      body.classList.add('nav-open');
      this.mobile_menu_visible = 1;
    }
  }

  update(){
    this.spinner.show()
    this.settings.en_schoolyear = new Date(this.settings.en_cystart).getFullYear().toString() + '-' + new Date(this.settings.en_cyend).getFullYear().toString()
    let promise = this.ds.sendRequest('updateSettings', this.settings).toPromise()
    promise.then((res)=>{
      if (res.status.remarks) {
        this.spinner.hide()
        Swal.fire({
          icon: 'success',
          title: 'Update Success!',
          text: 'Settings have been updated.'
        }).then(() => {
          this.log.date = formatDate(this.now, 'MMMM dd, y hh:mm:ss a', 'en-US' );
          this.log.activity = `Updated GCAT Registration settings.`
          this.log.idnumber = this.credentials.data[0].fa_empnumber
          this.log.name = `${this.credentials.data[0].fa_lname}, ${this.credentials.data[0].fa_fname}`
          this.log.department = this.credentials.data[0].fa_department
          this.ds.sendLog(this.log)
          this.ngOnInit()
        });
      } else {
        }
    });
  }

}
