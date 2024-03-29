import { Component, OnInit } from '@angular/core';
import { Router,ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, FormGroupDirective, NgForm, FormControl, Validators } from '@angular/forms';
import { ErrorStateMatcher } from '@angular/material/core';
import { DataService } from '../data-service.service';
import { studentClass } from '../data-schema'
import Cleave from 'cleave.js'
import Swal from 'sweetalert2'
import { NgxSpinnerService } from "ngx-spinner";
import { ThrowStmt } from '@angular/compiler';


export class NgLpErrorStateMatcher implements ErrorStateMatcher {
  isErrorState(control: FormControl | null, form: FormGroupDirective | NgForm | null): boolean {
    const isSubmitted = form && form.submitted;
    return !!(control && control.invalid && (control.dirty || control.touched || isSubmitted));
  }
}

@Component({
  selector: 'app-edit',
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.css']
})
export class EditComponent implements OnInit {
  gcatCont = false;
  idnum: any = {};
  dept = '';
  student = new studentClass;
  student1:any = {};
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
  parameters:any = {}
  constructor(private _formBuilder: FormBuilder, private ds: DataService, private router: Router, private spinner: NgxSpinnerService, private Activatedroute: ActivatedRoute) { 
  }

  ngOnInit() {
    this.spinner.show()
    this.spinner.show()
    this.parameters=this.Activatedroute.paramMap.subscribe(params => {
      this.parameters.id = params.get('id')
      this.parameters.key = params.get('key')
      let promise= this.ds.sendRequest('validateEdit', this.parameters).toPromise()
      promise.then((res)=>{
        if(res==='error'||res.status.remarks==false){
          Swal.fire({
            icon: 'error',
            title:'<h2>ID and Key did not match!</h2>',
            text: 'Please check your email if you have copied the correct link.'
          }).then(()=>{
            this.router.navigate(['application']);
          })
        } else{
          // this.student1 = res.data[0]
          Swal.fire({
            icon: 'success',
            title: 'WELCOME '+res.data[0].si_firstname+'!',
            text: ''
          }).then(()=>{
            Swal.fire({
              icon: 'info',
              title:'<h2>Please make sure to input a<br><strong><u>valid email address.</u></strong></h2>',
              text: 'We will send a confirmation message to your email address upon completing this form, so it is very important that you can access the email address that you enter.'
            })
            this.firstFormGroup.controls.lname.setValue(res.data[0].si_lastname.toUpperCase())
            this.firstFormGroup.controls.fname.setValue(res.data[0].si_firstname.toUpperCase())
            this.firstFormGroup.controls.mname.setValue(res.data[0].si_midname.toUpperCase())
            this.firstFormGroup.controls.nameext.setValue(res.data[0].si_extname.toUpperCase())
            this.firstFormGroup.controls.email.setValue(res.data[0].si_email)
            this.firstFormGroup.controls.mobile.setValue(res.data[0].si_mobile)
            this.firstFormGroup.controls.houseno.setValue(res.data[0].si_houseno)
            this.firstFormGroup.controls.street.setValue(res.data[0].si_brgy)
            this.firstFormGroup.controls.city1.setValue(res.data[0].si_city)
            this.firstFormGroup.controls.province1.setValue(res.data[0].si_province)
            this.ds.sendRequest('getProvinces', '').subscribe((provinces)=>{
             let provincenames = provinces.data.map(p=>{
                return p.name.toUpperCase()
              })
              let indexprovince = provincenames.indexOf(res.data[0].si_province)+1
              this.firstFormGroup.controls.province.setValue(indexprovince.toString())
              this.citySearch.provinceId = indexprovince
              this.ds.sendRequest('getCities', this.citySearch).subscribe((cities)=>{
                this.cities = cities.data
                let citynames = cities.data.filter(p=> p.name.toUpperCase()==res.data[0].si_city)
                 this.firstFormGroup.controls.city.setValue(citynames[0].id.toString())
               });
            });
            
            this.firstFormGroup.controls.zipcode.setValue(res.data[0].si_zipcode)
            this.firstFormGroup.controls.gender.setValue(res.data[0].si_gender)
            this.firstFormGroup.controls.dob.setValue(res.data[0].si_bday)
            this.firstFormGroup.controls.age.setValue(res.data[0].si_age)
            this.firstFormGroup.controls.civilstatus.setValue(res.data[0].si_civilstatus.toUpperCase())
            this.firstFormGroup.controls.birthplace.setValue(res.data[0].si_pob.toUpperCase())
            this.secondFormGroup.controls.course.setValue(res.data[0].si_course)
            this.secondFormGroup.controls.course2.setValue(res.data[0].si_coursechoice)
            this.secondFormGroup.controls.course3.setValue(res.data[0].si_coursechoice2)
            this.firstFormGroup.controls.citizenship.setValue(res.data[0].si_nationality)
            if(res.data[0].citizenship!='Filipino'){
              this.firstFormGroup.controls.citizenship.setValue('Others')
              this.citizenshipisother = false;
              this.firstFormGroup.controls.citizenshipother.setValue(res.data[0].si_nationality)
            }
            this.thirdFormGroup.controls.strand.setValue(res.data[0].si_strand)
            this.thirdFormGroup.controls.lrn.setValue(res.data[0].si_lrn)
            this.thirdFormGroup.controls.honors.setValue(res.data[0].si_specialaward)
            this.thirdFormGroup.controls.elem.setValue(res.data[0].si_elem)
            this.thirdFormGroup.controls.elemyear.setValue(res.data[0].si_elemyear)
            this.thirdFormGroup.controls.highschool.setValue(res.data[0].si_lastschool)
            this.thirdFormGroup.controls.isshs.setValue(res.data[0].si_isshs)
            this.thirdFormGroup.controls.hsclass.setValue(res.data[0].si_hsclass)
            this.thirdFormGroup.controls.highschoolyear.setValue(res.data[0].si_highschoolyear)
            this.thirdFormGroup.controls.highschoolgpa.setValue(res.data[0].si_average)
            this.thirdFormGroup.controls.english.setValue(res.data[0].si_english)
            this.thirdFormGroup.controls.math.setValue(res.data[0].si_math)
            this.thirdFormGroup.controls.science.setValue(res.data[0].si_science)
            this.thirdFormGroup.controls.tertiary.setValue(res.data[0].si_tertiary)
            this.thirdFormGroup.controls.tertiaryyear.setValue(res.data[0].si_tertiaryyear)
            this.thirdFormGroup.controls.tertiarycourse.setValue(res.data[0].si_tertiarycourse)
            this.thirdFormGroup.controls.vocational.setValue(res.data[0].si_vocational)
            this.thirdFormGroup.controls.vocationalyear.setValue(res.data[0].si_vocationalyear)
            this.thirdFormGroup.controls.vocationalcourse.setValue(res.data[0].si_vocationalcourse)
            this.thirdFormGroup.controls.nc.setValue(res.data[0].si_nc)
            this.thirdFormGroup.controls.nclvl.setValue(res.data[0].si_nclvl)

            this.fourthFormGroup.controls.brothers.setValue(res.data[0].si_brothers)
            this.fourthFormGroup.controls.sisters.setValue(res.data[0].si_sisters)
            this.fourthFormGroup.controls.motherdead.setValue(res.data[0].si_momdeceased)
            this.fourthFormGroup.controls.mother.setValue(res.data[0].si_momname)
            this.fourthFormGroup.controls.motheroccupation.setValue(res.data[0].si_momoccupation)
            this.fourthFormGroup.controls.mothercontact.setValue(res.data[0].si_momcontact)
            this.fourthFormGroup.controls.mothereducation.setValue(res.data[0].si_educationmom)
            this.fourthFormGroup.controls.fatherdead.setValue(res.data[0].si_daddeceased)
            this.fourthFormGroup.controls.father.setValue(res.data[0].si_dadname)
            this.fourthFormGroup.controls.fathercontact.setValue(res.data[0].si_dadcontact)
            this.fourthFormGroup.controls.fatheroccupation.setValue(res.data[0].si_dadoccupation)
            this.fourthFormGroup.controls.fathereducation.setValue(res.data[0].si_educationdad)
            this.fourthFormGroup.controls.guardian.setValue(res.data[0].si_guardname)
            this.fourthFormGroup.controls.relationship.setValue(res.data[0].si_guardrel)
            this.fourthFormGroup.controls.emergencynumber.setValue(res.data[0].si_emergencycontact)
            this.fourthFormGroup.controls.guardianadd.setValue(res.data[0].si_guardadd)
            
            this.fifthFormGroup.controls.govproj.setValue(res.data[0].si_govproj.split(', '))
            this.fifthFormGroup.controls.govprojother.setValue(res.data[0].si_govprojothers)
            this.fifthFormGroup.controls.household.setValue(res.data[0].si_householdno)
            this.fifthFormGroup.controls.disabled.setValue(res.data[0].si_isdisabled)
            this.fifthFormGroup.controls.famincome.setValue(res.data[0].si_famincome)
            this.fifthFormGroup.controls.disability.setValue(res.data[0].si_disability.toUpperCase())
            this.fifthFormGroup.controls.ipgroup.setValue(res.data[0].si_ipgroup)
            this.studentTypeFormGroup.controls.studentType.setValue(res.data[0].si_studenttype)
            this.student.id = res.data[0].si_idnumber
          })
        }
      });
   });
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
      this.secondFormGroup.controls.sem.setValue(settings.data[0].en_sem.toString())
      this.secondFormGroup.controls.acadyear.setValue(settings.data[0].en_schoolyear.toString())
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

    this.departmentall.dept='';



    this.ds.sendRequest('getCourses',this.departmentall).subscribe((courseres)=>{
      this.coursesall=courseres.data;
      this.coursesall2=courseres.data;
      this.coursesall3=courseres.data;
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
    });
  }

  filterCourse2(){
    this.ds.sendRequest('getCourses',this.departmentall).subscribe((courseres)=>{
      this.coursesall3 = courseres.data.filter(c=>c.co_name != this.secondFormGroup.value.course).filter(c=>c.co_name != this.secondFormGroup.value.course2)
    });
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
  let checkStudent:any = {}
  checkStudent.idNumber = this.student.id
  let promise= this.ds.sendRequest('getStudent', checkStudent).toPromise()
  promise.then(res=>{
    this.spinner.hide()
    checkStudent = res.data[0]
    if(checkStudent.si_firstname == this.firstFormGroup.value.fname && checkStudent.si_lastname == this.firstFormGroup.value.lname && checkStudent.si_midname == this.firstFormGroup.value.mname){
        var timeDiff = Math.abs(Date.now() - new Date(this.firstFormGroup.controls.dob.value).getTime());
        this.age = Math.floor(timeDiff / (1000 * 3600 * 24) / 365.25);
        if(this.age<5||this.age>100){
          alert('Invalid Birthdate!');
          this.firstFormGroup.controls.age.setValue('')
          this.firstFormGroup.controls.dob.setValue('')
        } else{
          this.firstFormGroup.controls.age.setValue(this.age)
        }
        this.spinner.hide()
    } else{
      let student: any = {}
      student.firstname = this.firstFormGroup.value.fname
      student.lastname = this.firstFormGroup.value.lname
      student.nameext = this.firstFormGroup.value.nameext
      student.midname = this.firstFormGroup.value.mname
      student.bday = this.firstFormGroup.value.dob
      promise = this.ds.sendRequest('validateStudent', student).toPromise()
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
              q.gc_idnumber = res.data[0].si_idnumber
              q.si_email = result.value
              q.si_firstname = res.data[0].si_firstname
              q.si_lastname = res.data[0].si_lastname
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
    }
  })
}

validateEmail(){
  this.spinner.show()
  let checkStudent:any = {}
  checkStudent.idNumber = this.student.id
  let promise1= this.ds.sendRequest('getStudent', checkStudent).toPromise()
  promise1.then(res=>{
    checkStudent = res.data[0]
    this.spinner.hide()
    if(checkStudent.si_email == this.firstFormGroup.value.email ){
      return;
    } else{
      this.spinner.show()
      let email: any = {}
      email.si_email = this.firstFormGroup.value.email
      let promise = this.ds.sendRequest('validateEmail', email).toPromise()
      promise.then((res)=>{
        this.spinner.hide()
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
 
  })
 
}

onSelection(e){
  switch(e){
    case 'citizen':
      if(this.firstFormGroup.value.citizenship == 'Others'){
        this.citizenshipisother = false;
      } else{
        this.citizenshipisother = true;
        this.firstFormGroup.controls.citizenshipother.setValue('')
      }
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
    let promise = this.ds.sendRequest('updateStudent', this.student).toPromise()
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