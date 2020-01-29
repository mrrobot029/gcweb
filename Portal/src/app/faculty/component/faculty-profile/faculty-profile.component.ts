import { Component, OnInit } from '@angular/core';
import * as _ from 'lodash';
import { DataService } from 'src/app/services/data.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-faculty-profile',
  templateUrl: './faculty-profile.component.html',
  styleUrls: ['./faculty-profile.component.scss']
})
export class FacultyProfileComponent implements OnInit {

  accInfo: any = {};
  accounttype = '';
  imageError: string;
  isImageSaved: boolean;
  cardImageBase64: string;
  userInfo: any = {};

  constructor(private ds: DataService) { }

  ngOnInit() {
    this.accInfo = JSON.parse(localStorage.getItem('gcweb_faculty'));
    if (this.accInfo.data[0].fa_accounttype === '0') {
      this.accounttype = 'Faculty Member';
    } else if (this.accInfo.data[0].fa_accounttype === '1') {
      this.accounttype = 'Admin';
    } else if (this.accInfo.data[0].fa_accounttype === '2') {
      this.accounttype = 'Coordinator';
    }
  }

  updatePassword(e) {
    e.preventDefault();
    this.userInfo.fa_recno = this.accInfo.data[0].fa_recno;

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
    this.accInfo.recno = this.accInfo.data[0].fa_recno;
    this.accInfo.image = this.cardImageBase64;
    this.ds.sendRequest('updateDP', this.accInfo).subscribe((res) => {
      if (res.status.remarks) {
        Swal.fire({ title: 'Success!' , text: 'Update DP success.' , icon: 'success' });
        this.accInfo.data[0].fa_picture = this.cardImageBase64;
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
