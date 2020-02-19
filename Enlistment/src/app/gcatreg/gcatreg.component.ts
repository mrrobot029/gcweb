import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, FormGroupDirective, NgForm, FormControl, Validators } from '@angular/forms';
import { ErrorStateMatcher } from '@angular/material/core';
import { DataService } from '../data-service.service';
import { studentClass } from '../data-schema'
import {Router} from '@angular/router';
import Cleave from 'cleave.js'
import Swal from 'sweetalert2'
import { NgxSpinnerService } from "ngx-spinner";

export class NgLpErrorStateMatcher implements ErrorStateMatcher {
  isErrorState(control: FormControl | null, form: FormGroupDirective | NgForm | null): boolean {
    const isSubmitted = form && form.submitted;
    return !!(control && control.invalid && (control.dirty || control.touched || isSubmitted));
  }
}


@Component({
  selector: 'app-gcatreg',
  templateUrl: './gcatreg.component.html',
  styleUrls: ['./gcatreg.component.css']
})
export class GcatregComponent implements OnInit {
  gcatCont = false;
  idnum: any = {};
  dept = '';
  student = new studentClass;
  isLinear = true;
  method;
  acadyear;
  sem;
  cy;
  sup;
  department: any = {};
  departmentall: any = {};
  courses = {};
  coursesall = {};
  coursesall2 = {};
  coursesall3 = {};
  isdisabled = true;
  isOldStudent=false;
  citizenshipisother=true;
  studentTypeFormGroup: FormGroup;
  firstFormGroup: FormGroup;
  secondFormGroup: FormGroup;
  thirdFormGroup: FormGroup;
  fourthFormGroup: FormGroup;
  fifthFormGroup: FormGroup;
  asd=false
  reasonisother=true;
  reasonstudyisother=true;
  notScholar = true;
  notTransferee = true;
  showErrors1 = false;
  showErrors2 = false;
  showErrors3 = false;
  showErrors4 = false;
  interestisother = true;
  talentisother = true;
  sportisother = true;
  current_selected: string;
  specialInterests = ['Science & Math', 'Games', 'Tech Hobbies', 'Sports'];
  talents = ['Dancing', 'Music/Singing', 'Drawing', 'Hosting'];
  sports = ['Basketball', 'Volleyball', 'Swimming', 'Arnis'];
  extensions = ['JR','SR','I','II','III','IV']
  strands = ['Accountancy, Business and Management(ABM - Academic Track)', 'General Academic Strand(GAS - Academic Track)', 'Humanities and Social Sciences(HUMSS - Academic Track)', 'Science Technology Engineering and Mathematics(STEM - Academic Track)', 'Agri-Fishery Arts(Tech Voc Livelihood Track)', 'Home Economics(Tech Voc Livelihood Track)', 'Industrial Arts(Tech Voc Livelihood Track)', 'Information and Communications Technology(Tech Voc Livelihood Track)']
  interests: any = {}
  talents1: any = {}
  sports1: any = {}
  govprojs: any = {}
  provinces: any = {}
  cities: any = {}
  citySearch: any = {}
  int: any = {}
  tal: any = {}
  spo: any = {}
  gov: any = {}
  govprojisother=true;
  listahanchecked=true;
  ipschecked=true;

  cleave6
  age;
  x = 0;
  continue = false
  currentDate = new Date();
  enlistmentStart;
  enlistmentEnd;
  enlistment = true;
  dep = "gc"
  deptlogo = "./assets/logo/logo_gc.png"
  selectedgc = true
  constructor(private _formBuilder: FormBuilder, private ds: DataService, private router: Router, private spinner: NgxSpinnerService) { 
  }

  ngOnInit() {
    this.spinner.show()
    this.currentDate.setHours(0,0,0,0)
    this.ds.sendRequest('getProvinces', '').subscribe((provinces)=>{
      this.provinces = provinces.data
    });

    let promise = this.ds.sendRequest('getSettings', '').toPromise()
    promise.then((settings) => {
      this.spinner.hide()
      this.enlistmentStart = new Date(settings.data[0].en_enstart)
      this.enlistmentEnd = new Date(settings.data[0].en_enend)
      if(this.currentDate<this.enlistmentStart||this.currentDate>this.enlistmentEnd){
        this.enlistment = false;
      }
      this.acadyear = settings.data[0].en_schoolyear;
      this.sem = settings.data[0].en_sem;
      this.cy = settings.data[0].en_cy;
      switch (this.sem) {
        case '1':
          this.sup = 'st'
          break;
        case '2':
          this.sup = 'nd'
          break;
        case 'Mid Year':
          this.sup = '';
          break;
      
        default:
          break;
      }})

    Swal.fire({
      icon: 'info',
      title:'<h2>Please make sure to input a<br><strong><u>valid email address.</u></strong></h2>',
      text: 'We will send a confirmation message to your email address upon completing this form, so it is very important that you can access the email address that you enter.'
    })

    this.departmentall.dept='';



    this.ds.sendRequest('getCourses',this.departmentall).subscribe((courseres)=>{
      this.coursesall=courseres.data;
    });
    this.studentTypeFormGroup = this._formBuilder.group({
      studentType: ['', Validators.required],
    });

    this.firstFormGroup = this._formBuilder.group({
      idnumber: [''],
      lname: ['', Validators.required],
      fname: ['', Validators.required],
      mname: [''],
      nameext: [''],
      fulladdress: [''],
      houseno: [''],
      street: ['', Validators.required],
      city1: [''],
      province1: [''],
      city: ['', Validators.required],
      province: ['', Validators.required],
      zipcode: ['', Validators.required],
      gender: ['', Validators.required],
      civilstatus:['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      mobile: ['', Validators.required],
      dob: ['', Validators.required],
      age: ['', Validators.required],
      birthplace: ['', Validators.required],
      citizenship: ['', Validators.required],
      citizenshipother: ['']
    });
    this.secondFormGroup = this._formBuilder.group({
      course: ['', Validators.required],
      course2: [''],
      course3: [''],
      sem: ['', Validators.required],
      transfercourselevel: [''],
      acadyear: [''],
    });
    this.thirdFormGroup = this._formBuilder.group({
      strand: [''],
      lrn: [''],
      honors: [''],
      elem: ['', Validators.required],
      elemyear: [''],
      highschool:['', Validators.required],
      isshs: ['', Validators.required],
      hsclass: ['', Validators.required],
      highschoolyear:[''],
      highschoolgpa:['', [Validators.max(100), Validators.min(0)]],
      english: ['', [Validators.max(100), Validators.min(0)]],
      math: ['', [Validators.max(100), Validators.min(0)]],
      science:['', [Validators.max(100), Validators.min(0)]],
      tertiary: [''],
      tertiaryyear: [''],
      tertiarycourse: [''],
      vocational: [''],
      vocationalyear: [''],
      vocationalcourse: [''],
      nc: [''],
      nclvl: [''],

    });
    this.fourthFormGroup = this._formBuilder.group({
      brothers: [''],
      sisters: [''],
      motherdead: ['', Validators.required],
      mother: ['', Validators.required],
      motheroccupation: ['', Validators.required],
      mothercontact: [''],
      mothereducation: [''],
      fatherdead: ['', Validators.required],
      father: ['', Validators.required],
      fatheroccupation: ['', Validators.required],
      fathercontact: [''],
      fathereducation: [''],
      guardian: ['', Validators.required],
      relationship: ['', Validators.required],
      guardianadd: ['', Validators.required],
      emergencynumber: ['', Validators.required],
    });
    this.fifthFormGroup = this._formBuilder.group({
      govproj: [''],
      household: [''],
      ipgroup: [''],
      govprojother: [''],
      disabled: ['', Validators.required],
      famincome: [''],
      disability: [''],
      tos: ['', Validators.required]
    });

    this.cleave6 = new Cleave(this.fifthFormGroup.controls.famincome,{
      numeral: true,
      numeralThousandsGroupStyle: 'thousand',
      prefix: 'Php ',
      delimiter: ','
  });
  }

  setMomDead(){
    if(this.fourthFormGroup.controls.motherdead.value == '0'){
      this.fourthFormGroup.controls.motheroccupation.setValue('N/A')
      this.fourthFormGroup.controls.mothercontact.setValue('0')
    } else{
      this.fourthFormGroup.controls.motheroccupation.setValue('')
      this.fourthFormGroup.controls.mothercontact.setValue('')
    }
  }

  setDadDead(){
    if(this.fourthFormGroup.controls.fatherdead.value == '0'){
      this.fourthFormGroup.controls.fatheroccupation.setValue('N/A')
      this.fourthFormGroup.controls.fathercontact.setValue('0')
    } else{
      this.fourthFormGroup.controls.fatheroccupation.setValue('')
      this.fourthFormGroup.controls.fathercontact.setValue('')
    }
  }

  onChange(dep){
    this.deptlogo = "./assets/logo/logo_"+dep+".png"
    if(dep!='gc'){
      this.selectedgc = false;
    } else{
      this.selectedgc = true;
    }
  }

  filterCourse(){
    this.ds.sendRequest('getCourses',this.departmentall).subscribe((courseres)=>{
      this.coursesall2 = courseres.data.filter(c=>c.co_name != this.secondFormGroup.value.course)
      console.log(this.coursesall2)
    });
  }

  filterCourse2(){
    this.ds.sendRequest('getCourses',this.departmentall).subscribe((courseres)=>{
      this.coursesall3 = courseres.data.filter(c=>c.co_name != this.secondFormGroup.value.course).filter(c=>c.co_name != this.secondFormGroup.value.course2)
      console.log(this.coursesall2)
    });
  }

  validateEmail(){
    this.spinner.show()
    let email: any = {}
    email.email = this.firstFormGroup.value.email
    let promise = this.ds.sendRequest('validateEmail', email).toPromise()
    promise.then((res)=>{
      this.spinner.hide()
      console.log(res)
      if(res.status.remarks){      
        Swal.fire({
        icon: 'error',
        title:'<h2>This email is<br><strong><u>already registered!</u></strong></h2>',
        text: `This email is already registered to another applicant. Please enter another email or if you own this email, check your inbox if you already recieved a reply from us.`
      }).then(() => {
        this.firstFormGroup.controls.email.setValue('')
      })}
    })
  }
  

  selectProvince(event){
    let target = event.source.selected._element.nativeElement;
    this.firstFormGroup.controls.province1.setValue(target.innerText.trim())
    this.citySearch.provinceId = this.firstFormGroup.controls.province.value
    this.ds.sendRequest('getCities', this.citySearch).subscribe((cities)=>{
      this.cities = cities.data
    });

  }

  selectCity(event){
    let target = event.source.selected._element.nativeElement;
    this.firstFormGroup.controls.city1.setValue(target.innerText.trim())
    this.citySearch.cityName = this.firstFormGroup.controls.city.value
    this.ds.sendRequest('getCity', this.citySearch).subscribe((cities)=>{
      this.firstFormGroup.controls.zipcode.setValue(cities.data[0].zipcode)
    });
    
  }


computeAge(){
  this.spinner.show()
    let student: any = {}
    student.firstname = this.firstFormGroup.value.fname
    student.lastname = this.firstFormGroup.value.lname
    student.nameext = this.firstFormGroup.value.nameext
    student.midname = this.firstFormGroup.value.mname
    student.bday = this.firstFormGroup.value.dob
    let promise = this.ds.sendRequest('validateStudent', student).toPromise()
    promise.then((res)=>{
      this.spinner.hide()
      if(res.status.remarks){  
        student.email = res.data[0].si_email
        let str = res.data[0].si_email
        let n = str.indexOf("@");
        let substr = str.substring(1, n)
        let asterisks = ''
        let x = 0
        while(x<substr.length+1){
          asterisks = asterisks+'*'
          x++
        }
        let email = str.replace(substr, asterisks)
        student.id = res.data[0].si_idnumber
        student.idNumber = res.data[0].si_idnumber
        this.spinner.show()    
        promise = this.ds.sendRequest('validateUnenrolled', student).toPromise()
        promise.then((res)=>{
          if(!res.status.remarks){
            Swal.fire({
              icon: "error",
              title: "This person is already/has previously enrolled to Gordon College.",
              text: "If you have previously/are currently enrolled to Gordon College, it is not necessary to take the GCAT examination."
            }).then(() => {
              this.router.navigate(['application']);
            });
          }
        })
        promise = this.ds.sendRequest('reSendMail', student).toPromise()
        promise.then((res)=>{
          this.spinner.hide()
          console.log(res)
        })
        Swal.fire({
        icon: 'warning',
        title:'<h2>This person is<br><strong><u>already registered!</u></strong></h2>',
        html:
              `We have already sent a message to your email: <b>${email}.</b><br>` +
              `If you have not recieved a message, or you entered an incorrect email address, please click the <b>"Change Email"</b> button to change your email address.`,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText:
          'Change Email',
        confirmButtonAriaLabel: 'Change Email',
        cancelButtonText:
          'Close',
        cancelButtonAriaLabel: 'Close'
      }).then((result) => {
        if (result.value) {
          Swal.fire({
            title: `Enter your new email:`,
            input: 'email',
            inputAttributes: {
              autocapitalize: 'on'
            },
            showCancelButton: false,
            confirmButtonText: 'Submit',
            showLoaderOnConfirm: true,
            preConfirm: (data) => {
            },
            allowOutsideClick: () => !Swal.isLoading()
          }).then((result) => {
            let q:any = {}
            q.idNumber = res.data[0].si_idnumber
            q.email = result.value
            q.fname = res.data[0].si_firstname
            q.lastname = res.data[0].si_lastname
            this.spinner.show()
            promise = this.ds.sendRequest('validateEmail', q).toPromise()
            promise.then((res)=>{
              this.spinner.hide()
              if(res.status.remarks){
                Swal.fire({
                  icon: "error",
                  title: "Email already registered to another applicant!",
                  text: "Please enter another email."
                }).then(() => {
                  this.router.navigate(['application']);
                });
              } else{
                this.spinner.show()
                promise = this.ds.sendRequest('updateEmail', q).toPromise()
                promise.then((res)=>{
                  this.spinner.hide()
                  console.log(res)
                  if(res[0]=='success'){
                    Swal.fire({
                      icon: "success",
                      html: `<h2>Your email address has been changed to <br><b>${q.email}</b>!</h2><br>`+
                             "Please check your inbox for our email containing a link to your printable <b>Form SR01</b>."
                    }).then(() => {
                      this.router.navigate(['application']);
                    });
                  } else{
                    Swal.fire({
                      icon: "error",
                      title: "We have encountered an unknown error!",
                      text: "Please try again."
                    }).then(() => {
                      this.router.navigate(['application']);
                    });
                  }
                })
              }
            })
          })
        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          this.router.navigate(['application']);
        }
      })
    }
    })
  var timeDiff = Math.abs(Date.now() - new Date(this.firstFormGroup.controls.dob.value).getTime());
  this.age = Math.floor(timeDiff / (1000 * 3600 * 24) / 365.25);
  if(this.age<5||this.age>100){
    alert('Invalid Birthdate!');
    this.firstFormGroup.controls.age.setValue('')
    this.firstFormGroup.controls.dob.setValue('')
  } else{
    this.firstFormGroup.controls.age.setValue(this.age)
  }
 
}

changeEmail(e){
  console.log(e)
}

onSelection(e){
  console.log(e)
  switch(e){
    case 'citizen':
      console.log(this.firstFormGroup.value.citizenship)
      if(this.firstFormGroup.value.citizenship == 'Others'){
        this.citizenshipisother = false;
      } else{
        this.citizenshipisother = true;
        this.firstFormGroup.controls.citizenshipother.setValue('')
      }
      console.log(this.citizenshipisother)
      break;

    case 'govproj':
          this.x = 0;
          let y = 0;
          this.govprojs = this.fifthFormGroup.controls.govproj.value;
          if(this.govprojs[this.govprojs.length-1]=="Others"){
            this.govprojisother=false
          } else{
            this.govprojisother=true
          }
          while(this.x<this.govprojs.length){
            if(this.govprojs[this.x]=="listahan"){
              this.listahanchecked=false
              break;
            } else{
              this.listahanchecked=true
            }
            this.x++
          }
          while(y<this.govprojs.length){
            if(this.govprojs[y]=="ips"){
              this.ipschecked=false
              break;
            } else{
              this.ipschecked=true
            }
            y++
          }
          if(this.govprojisother==true){
            this.fifthFormGroup.controls.govprojother.setValue('')
          }
          if(this.listahanchecked==true){
            this.fifthFormGroup.controls.household.setValue('')
          }
          if(this.ipschecked==true){
            this.fifthFormGroup.controls.ipgroup.setValue('')
          }
        break;
  }
 }

getErrorMessage(){
  return 'This field is required'
}

getErrorEmail(email){
    return this.firstFormGroup.controls.email.hasError('required') ? 'This field is required' :
    this.firstFormGroup.controls.email.hasError('email') ? 'Not a valid email' :'';
}

setDisabled(){
  if(this.fifthFormGroup.value.disabled == '1'){
    this.isdisabled=false
    this.fifthFormGroup.controls.disability.setValue('')

    
  }else{
    this.isdisabled=true
    this.fifthFormGroup.controls.disability.setValue('NONE')
  }
}


next(){

  this.showErrors1=true;
  this.secondFormGroup.controls.sem.setValue(this.sem)
  this.secondFormGroup.controls.yrlevel.setValue('1')
  }

next2(){
  this.showErrors2=true;
  }  

next3(){

    this.showErrors3=true;
}

submit(){
  this.showErrors4=true;
  if(this.fifthFormGroup.controls.tos.invalid){
    alert('Please check the "Terms of Service" agreement button.')
  } else{
    if(this.studentTypeFormGroup.controls.studentType.value == ''){
      alert('Please select student type at the top of the form.')
    } else{
    this.student.lname = this.firstFormGroup.value.lname;
    this.student.fname = this.firstFormGroup.value.fname;
    this.student.mname = this.firstFormGroup.value.mname;
    this.student.nameext = this.firstFormGroup.value.nameext;
    this.student.fulladdress = this.firstFormGroup.value.houseno+' '+ this.firstFormGroup.value.street+', '+this.firstFormGroup.value.city1+', '+this.firstFormGroup.value.province1;
    this.student.addressnum = this.firstFormGroup.value.houseno;
    this.student.addressst = this.firstFormGroup.value.street;
    this.student.addresscity = this.firstFormGroup.value.city1;
    this.student.addressprovince = this.firstFormGroup.value.province1;
    this.student.addresszip = this.firstFormGroup.value.zipcode;
    this.student.gender = this.firstFormGroup.value.gender;
    this.student.dob = this.firstFormGroup.value.dob;
    this.student.email = this.firstFormGroup.value.email;
    this.student.mobile = this.firstFormGroup.value.mobile;
    this.student.course = this.secondFormGroup.value.course
    this.student.course2 = this.secondFormGroup.value.course2;
    this.student.course3 = this.secondFormGroup.value.course3;
    this.student.elem = this.thirdFormGroup.value.elem
    this.student.elemyear = this.thirdFormGroup.value.elemyear
    this.student.highschool = this.thirdFormGroup.value.highschool
    this.student.highschoolyear = this.thirdFormGroup.value.highschoolyear
    this.student.highschoolgpa = this.thirdFormGroup.value.highschoolgpa
    this.student.english = this.thirdFormGroup.value.english
    this.student.math = this.thirdFormGroup.value.math
    this.student.science = this.thirdFormGroup.value.science;
    this.student.tertiary = this.thirdFormGroup.value.tertiary;
    this.student.tertiaryyear = this.thirdFormGroup.value.tertiaryyear;
    this.student.tertiarycourse = this.thirdFormGroup.value.tertiarycourse;
    this.student.vocational = this.thirdFormGroup.value.vocational;
    this.student.vocationalyear = this.thirdFormGroup.value.vocationalyear;
    this.student.vocationalcourse = this.thirdFormGroup.value.vocationalcourse;
    this.student.nc = this.thirdFormGroup.value.nc;
    this.student.nclvl = this.thirdFormGroup.value.nclvl;
    this.student.honors = this.thirdFormGroup.value.honors
    this.student.strand = this.thirdFormGroup.value.strand
    this.student.lrn = this.thirdFormGroup.value.lrn
    this.student.brothers = this.fourthFormGroup.value.brothers
    this.student.sisters = this.fourthFormGroup.value.sisters
    this.student.siblings = this.fourthFormGroup.value.brothers + this.fourthFormGroup.value.sisters
    this.student.motherdead = this.fourthFormGroup.value.motherdead
    this.student.mother = this.fourthFormGroup.value.mother
    this.student.motheroccupation = this.fourthFormGroup.value.motheroccupation
    this.student.mothereducation = this.fourthFormGroup.value.mothereducation
    this.student.mothercontact = this.fourthFormGroup.value.mothercontact
    this.student.fatherdead = this.fourthFormGroup.value.fatherdead
    this.student.father = this.fourthFormGroup.value.father
    this.student.fatheroccupation = this.fourthFormGroup.value.fatheroccupation
    this.student.fathereducation = this.fourthFormGroup.value.fathereducation
    this.student.fathercontact = this.fourthFormGroup.value.fathercontact
    this.student.guardian = this.fourthFormGroup.value.guardian
    this.student.relationship = this.fourthFormGroup.value.relationship
    this.student.guardianadd = this.fourthFormGroup.value.guardianadd
    this.student.emergencynumber = this.fourthFormGroup.value.emergencynumber
    this.student.govproj = this.fifthFormGroup.value.govproj
    this.student.household = this.fifthFormGroup.value.household
    this.student.govprojother = this.fifthFormGroup.value.govprojother
    this.student.disabled = this.fifthFormGroup.value.disabled
    this.student.famincome = this.fifthFormGroup.value.famincome
    this.student.disability = this.fifthFormGroup.value.disability
    this.student.pob = this.firstFormGroup.value.birthplace
    this.student.civilstatus = this.firstFormGroup.value.civilstatus
    this.student.citizenship = this.firstFormGroup.value.citizenship
    if(this.firstFormGroup.value.citizenship == 'Others'){
      this.student.citizenship = this.firstFormGroup.value.citizenshipother
    }
    this.student.age = this.firstFormGroup.value.age
    this.student.type = this.studentTypeFormGroup.controls.studentType.value
    this.student.cy = this.cy
    this.student.department = this.dep.toUpperCase();
    this.student.sem = this.sem;
    this.student.yearenrolled = this.acadyear
    this.student.schoolyear = this.acadyear
    this.student.ipgroup = this.fifthFormGroup.value.ipgroup
    this.student.isshs = this.thirdFormGroup.value.isshs
    this.student.hsclass = this.thirdFormGroup.value.hsclass
    this.spinner.show()
    let promise = this.ds.sendRequest('insertNewStudent', this.student).toPromise()
    promise.then((res)=>{
      this.spinner.hide()
      Swal.fire({
        icon: res[0],
        title: res[1],
        text: res[2]
      }).then(() => {
        this.router.navigate(['application']);
      });
    })
  }
}
}

}