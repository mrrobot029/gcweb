import { Component, OnInit } from '@angular/core';
import { Storage } from '@ionic/storage';
// import { DataService } from '../services/data.service';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.page.html',
  styleUrls: ['./profile.page.scss'],
})
export class ProfilePage implements OnInit {

  credStud: any = {};
  age: Number;

  constructor(private storage: Storage) { }

  ngOnInit() {
    this.storage.get('studentInfo').then((val) => {
      this.credStud = val;
      this.computeAge(val.si_bday);
      console.log(this.age)
    })
    
    
    
  }

  computeAge(e){
    var timeDiff = Math.abs(Date.now() - new Date(e).getTime());
    this.age = Math.floor(timeDiff / (1000 * 3600 * 24) / 365.25);
    if(this.age<5||this.age>100){
      alert('Invalid Birthdate!');
      this.age = 0;
    } else{
      this.age = this.age;
    }
  }

}
