import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-faculty-sidebar',
  templateUrl: './faculty-sidebar.component.html',
  styleUrls: ['./faculty-sidebar.component.scss']
})
export class FacultySidebarComponent implements OnInit {

  FacultyInfo: any = {};
  prog: any;

  constructor(private router: Router) { }

  ngOnInit() {
    this.FacultyInfo = JSON.parse(localStorage.getItem('gcweb_faculty'));
    this.prog = this.FacultyInfo.data[0].fa_accounttype;
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
        localStorage.removeItem('gcweb_faculty');
        this.router.navigate(['/login']);
      }
    });
  }
}
