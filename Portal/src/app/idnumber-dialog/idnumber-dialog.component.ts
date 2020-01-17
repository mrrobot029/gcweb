import { Component, Inject } from '@angular/core';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';
import { DataService } from '../services/data.service'
import { Data } from '@angular/router';
import { FormBuilder, FormGroup, FormGroupDirective, NgForm, FormControl, Validators } from '@angular/forms';
import { ErrorStateMatcher } from '@angular/material/core';
import { studentClass } from '../data-schema'
import {Router} from '@angular/router';
import Cleave from 'cleave.js'
import Swal from 'sweetalert2'

export interface DialogData {
  id: any;
  animal: string;
  name: string;
}

export class NgLpErrorStateMatcher implements ErrorStateMatcher {
  isErrorState(control: FormControl | null, form: FormGroupDirective | NgForm | null): boolean {
    const isSubmitted = form && form.submitted;
    return !!(control && control.invalid && (control.dirty || control.touched || isSubmitted));
  }
}

@Component({
  selector: 'app-idnumber-dialog',
  templateUrl: './idnumber-dialog.component.html',
  styleUrls: ['./idnumber-dialog.component.scss']
})
export class IdnumberDialogComponent{
  firstFormGroup: FormGroup;
  idnum: any = {};
  constructor(private ds: DataService,
    private _formBuilder: FormBuilder,
    public dialogRef: MatDialogRef<IdnumberDialogComponent>,
    @Inject(MAT_DIALOG_DATA) public data: DialogData) { }

  ngOnInit() {
    this.idnum.idNumber = this.data.id
    this.firstFormGroup = this._formBuilder.group({
      idnumberold: ['', Validators.required],
      idnumbernew: ['', Validators.required],
    });

    this.firstFormGroup.controls.idnumberold.setValue(this.data.id)
  }

  submit(){
    this.idnum.idNumber = this.data.id
    this.idnum.newId = this.firstFormGroup.controls.idnumbernew.value
  this.ds.sendRequest('updateId', this.idnum).subscribe((res)=>{   
    Swal.fire({
      icon: res[0],
      title: res[1],
      text: res[2]
    }).then(() => {
      this.dialogRef.close()
    });
  });
  }

}
