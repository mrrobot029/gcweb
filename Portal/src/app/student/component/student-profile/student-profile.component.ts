import { Component, OnInit } from '@angular/core';
import * as _ from 'lodash';
import { DataService } from 'src/app/services/data.service';
import Swal from 'sweetalert2';


@Component({
  selector: 'app-student-profile',
  templateUrl: './student-profile.component.html',
  styleUrls: ['./student-profile.component.scss']
})
export class StudentProfileComponent implements OnInit {

  credStud: any = {};
  age: number;
  imageError: string;
  isImageSaved: boolean;
  cardImageBase64: string;
  userInfo: any = {};

  constructor(private ds: DataService) { }

  ngOnInit() {
    this.credStud = JSON.parse(localStorage.getItem('gcweb_student'));
    this.computeAge();
  }

  computeAge(){
    var timeDiff = Math.abs(Date.now() - new Date(this.credStud.data[0].si_bday).getTime());
    this.age = Math.floor(timeDiff / (1000 * 3600 * 24) / 365.25);
    if(this.age<5||this.age>100){
      alert('Invalid Birthdate!');
      this.age = 0;
    } else{
      this.age = this.age;
    }
  }

  updatePassword(e) {
    e.preventDefault();
    this.userInfo.accType = 1;
    this.userInfo.si_recno = this.credStud.data[0].si_recno;

    this.ds.sendRequest('updatePassword', this.userInfo).subscribe((res) => {
      if (res.status.remarks) {
        Swal.fire({ title: 'Success!' , text: res.status.message , icon: 'success' });
      } else {
        Swal.fire({ title: 'Failed!' , text: res.status.message , icon: 'error' });
      }
    });

  }

  updateDP(e) {
    e.preventDefault();
    this.credStud.recno = this.credStud.data[0].si_recno;
    this.credStud.image = this.cardImageBase64;
    this.ds.sendRequest('updateImage', this.credStud).subscribe((res) => {
      if (res.status.remarks) {
        Swal.fire({ title: 'Success!' , text: 'Update DP success.' , icon: 'success' });
        this.credStud.data[0].fa_picture = this.cardImageBase64;
      }
    });
  }

  fileChangeEvent(fileInput: any) {
    this.imageError = null;
    if (fileInput.target.files && fileInput.target.files[0]) {
        // Size Filter Bytes
        const max_size = 20971520;
        const allowed_types = ['image/png', 'image/jpeg'];
        const max_height = 15200;
        const max_width = 25600;

        if (fileInput.target.files[0].size > max_size) {
            this.imageError =
                'Maximum size allowed is ' + max_size / 1000 + 'Mb';

            return false;
        }

        if (!_.includes(allowed_types, fileInput.target.files[0].type)) {
            this.imageError = 'Only Images are allowed ( JPG | PNG )';
            return false;
        }
        const reader = new FileReader();
        reader.onload = (e: any) => {
            const image = new Image();
            image.src = e.target.result;
            image.onload = rs => {
                const img_height = rs.currentTarget['height'];
                const img_width = rs.currentTarget['width'];

                console.log(img_height, img_width);


                if (img_height > max_height && img_width > max_width) {
                    this.imageError =
                        'Maximum dimentions allowed ' +
                        max_height +
                        '*' +
                        max_width +
                        'px';
                    return false;
                } else {
                    const imgBase64Path = e.target.result;
                    this.cardImageBase64 = imgBase64Path;
                    this.isImageSaved = true;
                }
            };
        };

        reader.readAsDataURL(fileInput.target.files[0]);
    }
}

}
