import { Component, Inject } from '@angular/core';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';
import { DataService } from '../services/data.service'
import Swal from 'sweetalert2';

export interface DialogData {
  animal: string;
  name: string;
  id;
  recno;
}


@Component({
  selector: 'app-settings-dialog',
  templateUrl: './settings-dialog.component.html',
  styleUrls: ['./settings-dialog.component.scss']
})
export class SettingsDialogComponent{

  settings:any = {};
  enlistmentStart;
  enlistmentEnd;
  cyStart;
  cyEnd;
  curriculumYear;
  sem;
  isActive;

  constructor( private ds: DataService,
    public dialog: MatDialog,
    public dialogRef: MatDialogRef<SettingsDialogComponent>,
    @Inject(MAT_DIALOG_DATA) public data: DialogData) { }

  ngOnInit() {
    this.ds.sendRequest('getSettings', '').subscribe((settings)=>{
      this.settings = settings.data[0]
    });
  }

  update(){

    this.settings.en_schoolyear = new Date(this.settings.en_cystart).getFullYear().toString() + '-' + new Date(this.settings.en_cyend).getFullYear().toString()
    console.log(this.settings)
    this.ds.sendRequest('updateSettings', this.settings).subscribe((res)=>{
      console.log(res)
      if (res.status.remarks) {
        Swal.fire({
          icon: 'success',
          title: 'Update Success!',
          text: 'Settings have been updated.'
        }).then(() => {
          this.dialogRef.close()
        });
      } else {
        }
    });
  }
  }
