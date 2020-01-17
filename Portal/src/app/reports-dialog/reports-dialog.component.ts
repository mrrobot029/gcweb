import { Component, Inject } from '@angular/core';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';
import { DataService } from '../services/data.service'
import { Data } from '@angular/router';
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


@Component({
  selector: 'app-reports-dialog',
  templateUrl: './reports-dialog.component.html',
  styleUrls: ['./reports-dialog.component.scss']
})
export class ReportsDialogComponent{
  searchInfo: any={}
  departments: any={}
  blocks: any={}
  deptactive = false
  blockactive = false
  selectedDept
  selectedBlock
  department = 'CCS'
  block

  constructor(private ds: DataService,
    public dialogRef: MatDialogRef<ReportsDialogComponent>,
    @Inject(MAT_DIALOG_DATA) public data: DialogData) { }


  ngOnInit() {
    this.ds.sendRequest('getDept', this.searchInfo).subscribe((department)=>{ 
      console.log(department.data)
      this.departments = department.data
    })

  }

  selectDept(){
    this.searchInfo.si_department = this.department
    // this.ds.sendRequest('getCourseBlocks', this.searchInfo).subscribe((res) => {
    //   console.log(res)
    //   this.blocks = res;
    // });
    this.selectedDept = this.department
    console.log(this.selectedDept)
    if(this.department==''){
      this.deptactive = true
    } else{
      this.deptactive = false
    }
  }

  selectBlock(){
    this.selectedBlock = this.block
    if(this.block==''){
      this.blockactive = true
    } else{
      this.blockactive = false
    }
  }

}
