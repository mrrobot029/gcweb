import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-student-header',
  templateUrl: './student-header.component.html',
  styleUrls: ['./student-header.component.scss']
})
export class StudentHeaderComponent implements OnInit {

  credStud: any = {}

  constructor(private router: Router) { }

  ngOnInit() {
    this.credStud = JSON.parse(localStorage.getItem('gcweb_student'));
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
        localStorage.removeItem('gcweb_student');
        this.router.navigate(['/login']);
      }
    });
  }

}
