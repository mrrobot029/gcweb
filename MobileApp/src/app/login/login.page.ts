import { Component, OnInit } from '@angular/core';
import { DataService } from '../services/data.service';
import { Storage } from '@ionic/storage';
import Swal from 'sweetalert2';
import { AuthService } from '../services/auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {

  credInfo: any = {};

  constructor(private ds: DataService, private storage: Storage, private authservice: AuthService, private router: Router) { }

  ngOnInit() {
    this.storage.get('studentToken').then((val) => {
      if (val) {
        this.credInfo.payload = val;
        this.ds.sendrequest('checkStudent', this.credInfo).subscribe(res => {
          this.router.navigate(['/menu']);
        });
      }
    });


  }

  login(e) {
    e.preventDefault();

    this.credInfo.username = e.target[0].value;
    this.credInfo.password = e.target[1].value;

    this.ds.sendrequest('loginStudent', this.credInfo).subscribe(res => {
      if (res.status.remarks) {
        Swal.fire({ title: 'Success', text: 'Student Verified', icon: 'success'}).then(() => {
          this.storage.set('studentInfo', res.data[0]);
          this.authservice.login(res.payload);
          this.router.navigate(['/menu']);
        });
      } else {
        Swal.fire({ title: 'Login Failed!', text: 'Invalid user credentials.', icon: 'error'});
      }
    });

  }

}
