import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, FormGroupDirective, NgForm, FormControl, Validators } from '@angular/forms';
import { ErrorStateMatcher } from '@angular/material/core';
import { DataService } from '../data-service.service';
import { studentClass } from '../data-schema'
import {Router} from '@angular/router';
import Cleave from 'cleave.js'
import Swal from 'sweetalert2'

export class NgLpErrorStateMatcher implements ErrorStateMatcher {
  isErrorState(control: FormControl | null, form: FormGroupDirective | NgForm | null): boolean {
    const isSubmitted = form && form.submitted;
    return !!(control && control.invalid && (control.dirty || control.touched || isSubmitted));
  }
}

@Component({
  selector: 'app-ceasnew',
  templateUrl: './ceasnew.component.html',
  styleUrls: ['./ceasnew.component.css']
})
export class CeasnewComponent implements OnInit {
  idnum: any = {};
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
  isdisabled = true;
  isOldStudent=false;
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
  interestisother = true;
  talentisother = true;
  sportisother = true;
  current_selected: string;
  specialInterests = ['Science & Math', 'Games', 'Tech Hobbies', 'Sports'];
  talents = ['Dancing', 'Music', 'Drawing', 'Hosting'];
  sports = ['Basketball', 'Volleyball', 'Swimming', 'Arnis'];
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
  cleave6
  age;
  x = 0;
  currentDate = new Date();
  enlistmentStart;
  enlistmentEnd;
  enlistment = true;
  constructor(private _formBuilder: FormBuilder, private ds: DataService, private router: Router) { }

  ngOnInit() {
    this.currentDate.setHours(0,0,0,0)
    this.ds.sendRequest('getProvinces', '').subscribe((provinces)=>{
      this.provinces = provinces.data
      console.log(provinces)
    });
    this.ds.sendRequest('getSettings', '').subscribe((settings)=>{
      this.enlistmentStart = new Date(settings.data[0].en_enstart)
      this.enlistmentEnd = new Date(settings.data[0].en_enend)
      if(this.currentDate<this.enlistmentStart||this.currentDate>this.enlistmentEnd){
        this.enlistment = false;
        console.log(this.enlistment)
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
      }
    });
    this.department.dept='CEAS';
    this.departmentall.dept='';

    this.ds.sendRequest('getCourses',this.department).subscribe((courseres)=>{
      this.courses=courseres.data;
    });

    this.ds.sendRequest('getCourses',this.departmentall).subscribe((courseres)=>{
      this.coursesall=courseres.data;
      console.log(courseres.data)
    });
    this.studentTypeFormGroup = this._formBuilder.group({
      studentType: ['', Validators.required],
    });

    this.firstFormGroup = this._formBuilder.group({
      idnumber: [''],
      lname: ['', Validators.required],
      fname: ['', Validators.required],
      mname: ['', Validators.required],
      nickname: [''],
      religion: ['', Validators.required],
      languages: [''],
      nameext: [''],
      fulladdress: [''],
      houseno1: [''],
      houseno: [''],
      street1: [''],
      city1: [''],
      province1: [''],
      zipcode1: [''],
      street: ['', Validators.required],
      city: ['', Validators.required],
      province: ['', Validators.required],
      zipcode: ['', Validators.required],
      gender: ['', Validators.required],
      civilstatus:['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      mobile: ['', Validators.required],
      dob: ['', Validators.required],
      age: ['', Validators.required],
      birthplace: ['', Validators.required]
    });
    this.secondFormGroup = this._formBuilder.group({
      course: ['', Validators.required],
      course2: [''],
      course3: [''],
      yrlevel: ['', Validators.required],
      sem: ['', Validators.required],
      regular: ['', Validators.required],
      reason: ['', Validators.required],
      reasonother: [''],
      reasonstudy: ['', Validators.required],
      reasonstudyother: [''],
      scholar: ['', Validators.required],
      scholartype: [''],
      sponsor: ['', Validators.required],
      sponsoroccupation: ['', Validators.required],
      transferee: ['', Validators.required],
      transfercourselevel: [''],
    });
    this.thirdFormGroup = this._formBuilder.group({
      strand: [''],
      lrn: [''],
      honors: [''],
      orgs: [''],
      competitions: [''],
      interest: [''],
      interestother: [''],
      talentother: [''],
      sportother: [''],
      talent: [''],
      sport: [''],
      elem: [''],
      elemyear: [''],
      elemaward: [''],
      junior: [''],
      junioryear: [''],
      junioraward: [''],
      senior: [''],
      senioryear: [''],
      senioraward: [''],
      tertiary: [''],
      tertiaryyear: [''],
      tertiaryaward: [''],
      tertiarycourse: [''],
      vocational: [''],
      vocationalyear: [''],
      vocationalaward: [''],
      vocationalcourse: [''],
      org: [''],
      orgpos: [''],
      orgyear: [''],
      org2: [''],
      orgpos2: [''],
      orgyear2: [''],
      org3: [''],
      orgpos3: [''],
      orgyear3: [''],
    });
    this.fourthFormGroup = this._formBuilder.group({
      siblings: ['', Validators.required],
      mother: ['', Validators.required],
      motheroccupation: ['', Validators.required],
      mothercontact: ['', Validators.required],
      father: ['', Validators.required],
      fatheroccupation: ['', Validators.required],
      fathercontact: ['', Validators.required],
      spouse: [''],
      spousecontact: [''],
      guardian: ['', Validators.required],
      relationship: ['', Validators.required],
      guardianadd: ['', Validators.required],
      emergencynumber: ['', Validators.required],
    });
    this.fifthFormGroup = this._formBuilder.group({
      govproj: [''],
      household: [''],
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

  selectProvince(event){
    let target = event.source.selected._element.nativeElement;
    this.firstFormGroup.controls.province1.setValue(target.innerText.trim())
    console.log(this.firstFormGroup.controls.province1.value)
    this.citySearch.provinceId = this.firstFormGroup.controls.province.value
    this.ds.sendRequest('getCities', this.citySearch).subscribe((cities)=>{
      this.cities = cities.data
      console.log(cities)
    });

  }

  selectCity(event){
    let target = event.source.selected._element.nativeElement;
    this.firstFormGroup.controls.city1.setValue(target.innerText.trim())
    console.log(this.firstFormGroup.controls.city1.value)
    this.citySearch.cityName = this.firstFormGroup.controls.city.value
    this.ds.sendRequest('getCity', this.citySearch).subscribe((cities)=>{
      this.firstFormGroup.controls.zipcode.setValue(cities.data[0].zipcode)
      console.log(cities.data[0].zipcode)
    });
    
  }

  getInfo(){
    this.idnum.idNumber = this.firstFormGroup.value.idnumber
    this.ds.sendRequest('getStudent', this.idnum).subscribe((studentinfo)=>{   
        if(studentinfo.status.remarks==true){
          if(studentinfo.data[0].si_isenlisted==1&&studentinfo.data[0].si_sem==this.sem&&studentinfo.data[0].si_schoolyear==this.acadyear){
            Swal.fire({
              icon: 'error',
              title: 'This student is already enlisted for this semester',
              text: ''
            }).then(() => {
              this.firstFormGroup.controls.idnumber.setValue('')
          });
          } else{
        Swal.fire({
          icon: 'success',
          title: 'WELCOME '+studentinfo.data[0].si_firstname+'!',
          text: ''
        }).then(() => {
          console.log(studentinfo.data[0].si_address)
          this.studentTypeFormGroup.controls.studentType.setValue('old')
          this.isOldStudent = true;
          
          this.firstFormGroup.controls.lname.setValue(studentinfo.data[0].si_lastname)
          this.firstFormGroup.controls.fname.setValue(studentinfo.data[0].si_firstname)
          this.firstFormGroup.controls.mname.setValue(studentinfo.data[0].si_midname)
          this.firstFormGroup.controls.nameext.setValue(studentinfo.data[0].si_extname)
          this.firstFormGroup.controls.nickname.setValue(studentinfo.data[0].si_nickname)
          this.firstFormGroup.controls.email.setValue(studentinfo.data[0].si_email)
          this.firstFormGroup.controls.mobile.setValue(studentinfo.data[0].si_mobile)
          this.firstFormGroup.controls.fulladdress.setValue(studentinfo.data[0].si_address)
          this.firstFormGroup.controls.zipcode.setValue(studentinfo.data[0].si_zipcode)
          this.firstFormGroup.controls.gender.setValue(studentinfo.data[0].si_gender)
          this.firstFormGroup.controls.dob.setValue(studentinfo.data[0].si_bday)
          this.firstFormGroup.controls.age.setValue(studentinfo.data[0].si_age)
          this.computeAge()
          this.firstFormGroup.controls.civilstatus.setValue(studentinfo.data[0].si_civilstatus.toUpperCase())
          this.firstFormGroup.controls.email.setValue(studentinfo.data[0].si_email)
          this.firstFormGroup.controls.mobile.setValue(studentinfo.data[0].si_mobile)
          this.firstFormGroup.controls.birthplace.setValue(studentinfo.data[0].si_pob)
          this.firstFormGroup.controls.religion.setValue(studentinfo.data[0].si_religion)
          this.firstFormGroup.controls.languages.setValue(studentinfo.data[0].si_language)
          this.secondFormGroup.controls.yrlevel.setValue(studentinfo.data[0].si_yrlevel)
          this.secondFormGroup.controls.course.setValue(studentinfo.data[0].si_course)
          this.secondFormGroup.controls.course2.setValue(studentinfo.data[0].si_coursechoice)
          this.secondFormGroup.controls.course3.setValue(studentinfo.data[0].si_coursechoice2)
          this.secondFormGroup.controls.regular.setValue(studentinfo.data[0].si_isregular)
          switch(studentinfo.data[0].si_reason){
              case 'Passion': 
                        this.secondFormGroup.controls.reason.setValue(studentinfo.data[0].si_reason)
                        break;
              case 'Parental Influence':
                        this.secondFormGroup.controls.reason.setValue(studentinfo.data[0].si_reason)
                        break;
              case 'Peer Pressure':
                        this.secondFormGroup.controls.reason.setValue(studentinfo.data[0].si_reason)
                        break;
              case 'Budget':
                        this.secondFormGroup.controls.reason.setValue(studentinfo.data[0].si_reason)
                        break;
              default:
                        this.secondFormGroup.controls.reason.setValue('Other')
                        this.secondFormGroup.controls.reasonother.setValue(studentinfo.data[0].si_reason)
          }
          switch(studentinfo.data[0].si_reasonstudy){
            case 'Parental Preference': 
                      this.secondFormGroup.controls.reasonstudy.setValue(studentinfo.data[0].si_reasonstudy)
                      break;
            case 'Facilities':
                      this.secondFormGroup.controls.reasonstudy.setValue(studentinfo.data[0].si_reasonstudy)
                      break;
            case 'School Location':
                      this.secondFormGroup.controls.reasonstudy.setValue(studentinfo.data[0].si_reasonstudy)
                      break;
            case 'Budget':
                      this.secondFormGroup.controls.reasonstudy.setValue(studentinfo.data[0].si_reasonstudy)
                      break;
            default:
                      this.secondFormGroup.controls.reasonstudy.setValue('Other')
                      this.secondFormGroup.controls.reasonstudyother.setValue(studentinfo.data[0].si_reasonstudy)
        }
        if(studentinfo.data[0].si_scholartype!='NONE'){
          this.secondFormGroup.controls.scholar.setValue('1')
        } else{
          this.secondFormGroup.controls.scholar.setValue('0')
        }
          this.secondFormGroup.controls.scholartype.setValue(studentinfo.data[0].si_scholartype)
          this.secondFormGroup.controls.sponsor.setValue(studentinfo.data[0].si_support)
          this.secondFormGroup.controls.sponsoroccupation.setValue(studentinfo.data[0].si_supportoccupation)
          this.secondFormGroup.controls.transferee.setValue(studentinfo.data[0].si_istransferee)
          this.secondFormGroup.controls.transfercourselevel.setValue(studentinfo.data[0].si_transfercourselevel)
          this.thirdFormGroup.controls.strand.setValue(studentinfo.data[0].si_strand)
          this.thirdFormGroup.controls.lrn.setValue(studentinfo.data[0].si_lrn)
          this.thirdFormGroup.controls.honors.setValue(studentinfo.data[0].si_specialaward)
          this.thirdFormGroup.controls.orgs.setValue(studentinfo.data[0].si_organization)
          this.thirdFormGroup.controls.competitions.setValue(studentinfo.data[0].si_competition)
          this.thirdFormGroup.controls.elem.setValue(studentinfo.data[0].si_elem)
          this.thirdFormGroup.controls.elemyear.setValue(studentinfo.data[0].si_elemyear)
          this.thirdFormGroup.controls.elemaward.setValue(studentinfo.data[0].si_elemaward)
          this.thirdFormGroup.controls.junior.setValue(studentinfo.data[0].si_junior)
          this.thirdFormGroup.controls.junioryear.setValue(studentinfo.data[0].si_junioryear)
          this.thirdFormGroup.controls.junioraward.setValue(studentinfo.data[0].si_junioraward)
          this.thirdFormGroup.controls.senior.setValue(studentinfo.data[0].si_senior)
          this.thirdFormGroup.controls.senioryear.setValue(studentinfo.data[0].si_senioryear)
          this.thirdFormGroup.controls.senioraward.setValue(studentinfo.data[0].si_senioraward)
          this.thirdFormGroup.controls.tertiary.setValue(studentinfo.data[0].si_tertiary)
          this.thirdFormGroup.controls.tertiaryyear.setValue(studentinfo.data[0].si_tertiaryyear)
          this.thirdFormGroup.controls.tertiaryaward.setValue(studentinfo.data[0].si_tertiaryaward)
          this.thirdFormGroup.controls.tertiarycourse.setValue(studentinfo.data[0].si_tertiarycourse)
          this.thirdFormGroup.controls.vocational.setValue(studentinfo.data[0].si_vocational)
          this.thirdFormGroup.controls.vocationalyear.setValue(studentinfo.data[0].si_vocationalyear)
          this.thirdFormGroup.controls.vocationalcourse.setValue(studentinfo.data[0].si_vocationalcourse)
          this.thirdFormGroup.controls.vocationalaward.setValue(studentinfo.data[0].si_vocationalaward)
          this.thirdFormGroup.controls.interest.setValue(studentinfo.data[0].si_interest.split(', '))
          this.thirdFormGroup.controls.talent.setValue(studentinfo.data[0].si_talent.split(', '))
          this.thirdFormGroup.controls.sport.setValue(studentinfo.data[0].si_sport.split(', '))

          this.int = studentinfo.data[0].si_interest.split(', ')
          if(this.int[this.int.length-2]=="Others"){
            this.thirdFormGroup.controls.interestother.setValue(this.int[this.int.length-1])
          }

          this.tal = studentinfo.data[0].si_talent.split(', ')
          if(this.tal[this.tal.length-2]=="Others"){
            this.thirdFormGroup.controls.talentother.setValue(this.tal[this.tal.length-1])
          }

          this.spo = studentinfo.data[0].si_sport.split(', ')
          if(this.int[this.int.length-2]=="Others"){
            this.thirdFormGroup.controls.sportother.setValue(this.spo[this.spo.length-1])
          }

          this.fourthFormGroup.controls.siblings.setValue(studentinfo.data[0].si_siblings)
          this.fourthFormGroup.controls.mother.setValue(studentinfo.data[0].si_momname)
          this.fourthFormGroup.controls.motheroccupation.setValue(studentinfo.data[0].si_momoccupation)
          this.fourthFormGroup.controls.mothercontact.setValue(studentinfo.data[0].si_momcontact)
          this.fourthFormGroup.controls.father.setValue(studentinfo.data[0].si_dadname)
          this.fourthFormGroup.controls.fatheroccupation.setValue(studentinfo.data[0].si_dadoccupation)
          this.fourthFormGroup.controls.fathercontact.setValue(studentinfo.data[0].si_dadcontact)
          this.fourthFormGroup.controls.spouse.setValue(studentinfo.data[0].si_spouse)
          this.fourthFormGroup.controls.spousecontact.setValue(studentinfo.data[0].si_spousecontact)
          this.fourthFormGroup.controls.guardian.setValue(studentinfo.data[0].si_guardname)
          this.fourthFormGroup.controls.relationship.setValue(studentinfo.data[0].si_guardrel)
          this.fourthFormGroup.controls.guardianadd.setValue(studentinfo.data[0].si_guardadd)
          this.fourthFormGroup.controls.emergencynumber.setValue(studentinfo.data[0].si_emergencycontact)
          this.fifthFormGroup.controls.govproj.setValue(studentinfo.data[0].si_govproj.split(', '))
          this.fifthFormGroup.controls.govprojother.setValue(studentinfo.data[0].si_govprojothers)
          this.fifthFormGroup.controls.household.setValue(studentinfo.data[0].si_householdno)
          this.fifthFormGroup.controls.disabled.setValue(studentinfo.data[0].si_isdisabled)
          this.fifthFormGroup.controls.famincome.setValue(studentinfo.data[0].si_famincome)
          this.fifthFormGroup.controls.disability.setValue(studentinfo.data[0].si_disability)

      });
      }
    } else{
      Swal.fire({
        icon: 'error',
        title: 'Student not yet registered!',
        text: ''
      }).then(() => { 
        this.studentTypeFormGroup.controls.studentType.setValue('new')
        
      });
    }
  
    })
  }
  

// changeType(){
//   if(this.studentTypeFormGroup.controls.studentType.value=='old'){
//     this.isOldStudent=true
//     this.firstFormGroup.controls.street.clearValidators();
//     this.firstFormGroup.controls.street.updateValueAndValidity();
//     this.firstFormGroup.controls.city.clearValidators();
//     this.firstFormGroup.controls.city.updateValueAndValidity();
//     this.firstFormGroup.controls.province.clearValidators();
//     this.firstFormGroup.controls.province.updateValueAndValidity();
//   } else{
//     this.isOldStudent=false
//   }
// }

computeAge(){
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
onSelection(e){
    // console.log(this.thirdFormGroup.controls.interests.value.toString())
    // console.log(this.thirdFormGroup.controls.talents.value)
    // console.log(this.thirdFormGroup.controls.sports.value)
  switch(e){
    case 'interest' : 
    this.interests = this.thirdFormGroup.controls.interest.value;
      if(this.interests[this.interests.length-1]=="Others"){
        this.interestisother=false
      } else{
        this.interestisother=true
        this.thirdFormGroup.controls.interestother.setValue('')
      }
      break;
    case 'talent': 
    this.talents1 = this.thirdFormGroup.controls.talent.value;
      if(this.talents1[this.talents1.length-1]=="Others"){
        this.talentisother=false
      } else{
        this.talentisother=true
        this.thirdFormGroup.controls.talentother.setValue('')
      }
      break;
    case 'sport':
        this.sports1 = this.thirdFormGroup.controls.sport.value;
        if(this.sports1[this.sports1.length-1]=="Others"){
          this.sportisother=false
        } else{
          this.sportisother=true
          this.thirdFormGroup.controls.sportother.setValue('')
        }
      break;
    case 'govproj':
          this.x = 0;
          this.govprojs = this.fifthFormGroup.controls.govproj.value;
          console.log(this.govprojs)
          if(this.govprojs[this.govprojs.length-1]=="Others"){
            this.govprojisother=false
          } else{
            this.govprojisother=true
            this.fifthFormGroup.controls.govprojother.setValue('')
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
          if(this.listahanchecked==true){
            this.fifthFormGroup.controls.household.setValue('')
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

setreason(){
  if(this.secondFormGroup.value.reason == 'Other'){
    this.reasonisother=false
    
  }else{
    this.reasonisother=true
    this.secondFormGroup.controls.reasonother.setValue('')
  }
}

setDisabled(){
  if(this.fifthFormGroup.value.disabled == '1'){
    this.isdisabled=false

    
  }else{
    this.isdisabled=true
    this.fifthFormGroup.controls.disability.setValue('NONE')
  }
}

setscholar(){
  if(this.secondFormGroup.value.scholar == '1'){
    this.notScholar=false
    
  }else{
    this.notScholar=true
    this.secondFormGroup.controls.scholartype.setValue('NONE')
  }
}

settransferee(){
  if(this.secondFormGroup.value.transferee == '1'){
    this.notTransferee=false
    
  }else{
    this.notTransferee=true
    this.secondFormGroup.controls.transfercourselevel.setValue('NONE')
  }
}


setreasonstudy(){
  if(this.secondFormGroup.value.reasonstudy == 'Other'){
    this.reasonstudyisother=false
    
  }else{
    this.reasonstudyisother=true
    this.secondFormGroup.controls.reasonstudyother.setValue('')
  }
}

next(){
  this.showErrors1=true;
  this.secondFormGroup.controls.sem.setValue(this.sem)
  console.log(this.sem)
  }

next2(){
  this.showErrors2=true;
  }  

submit(){
  this.showErrors3=true;
  if(this.fifthFormGroup.controls.tos.invalid){
    alert('Please check the "Terms of Service" agreement button')
  } else{
  if(this.studentTypeFormGroup.controls.studentType.value == ''){
    alert('Please select student type at the top of the form.')
  } else{
  this.student.idnumber = this.firstFormGroup.value.idnumber
  this.student.lname = this.firstFormGroup.value.lname;
  this.student.fname = this.firstFormGroup.value.fname;
  this.student.mname = this.firstFormGroup.value.mname;
  this.student.nameext = this.firstFormGroup.value.nameext;
  this.student.addresszip = this.firstFormGroup.value.zipcode;
  this.student.gender = this.firstFormGroup.value.gender;
  this.student.dob = this.firstFormGroup.value.dob;
  this.student.email = this.firstFormGroup.value.email;
  this.student.mobile = this.firstFormGroup.value.mobile;
  this.student.course = this.secondFormGroup.value.course
  this.student.course2 = this.secondFormGroup.value.course2;
  this.student.course3 = this.secondFormGroup.value.course3;
  this.student.reasoncourse = this.secondFormGroup.value.reason;
  this.student.courseother = this.secondFormGroup.value.reasonother;
  this.student.reasonschool = this.secondFormGroup.value.reasonstudy;
  this.student.schoolother = this.secondFormGroup.value.reasonstudyother;
  this.student.scholar = this.secondFormGroup.value.scholar;
  this.student.scholartype = this.secondFormGroup.value.scholartype;
  this.student.sponsor = this.secondFormGroup.value.sponsor;
  this.student.sponsoroccupation = this.secondFormGroup.value.sponsoroccupation;
  this.student.transferee = this.secondFormGroup.value.transferee;
  this.student.transfercourselevel = this.secondFormGroup.value.transfercourselevel;
  this.student.highschool = ''
  this.student.strand = this.thirdFormGroup.value.strand
  this.student.lrn = this.thirdFormGroup.value.lrn
  this.student.highschoolgpa = ''
  this.student.honors = this.thirdFormGroup.value.honors
  this.student.orgs = this.thirdFormGroup.value.orgs
  this.student.competitions = this.thirdFormGroup.value.competitions
  this.student.religion = this.firstFormGroup.value.religion
  this.student.nickname = this.firstFormGroup.value.nickname
  this.int = this.thirdFormGroup.value.interest
  if(this.int[this.int.length-2]=='Others'){
    this.int.pop()
  }
  this.student.interests = this.int
  this.student.interestother = this.thirdFormGroup.value.interestother
  this.tal = this.thirdFormGroup.value.talent
  if(this.tal[this.tal.length-2]=='Others'){
    this.tal.pop()
  }
  this.student.talents = this.tal
  this.student.talentsother = this.thirdFormGroup.value.talentother
  this.spo = this.thirdFormGroup.value.sport
  if(this.spo[this.spo.length-2]=='Others'){
    this.spo.pop()
  }
  this.student.sport = this.thirdFormGroup.value.sport
  this.student.sportother = this.thirdFormGroup.value.sportother
  this.student.siblings = this.fourthFormGroup.value.siblings
  this.student.mother = this.fourthFormGroup.value.mother
  this.student.motheroccupation = this.fourthFormGroup.value.motheroccupation
  this.student.mothercontact = this.fourthFormGroup.value.mothercontact
  this.student.father = this.fourthFormGroup.value.father
  this.student.fatheroccupation = this.fourthFormGroup.value.fatheroccupation
  this.student.fathercontact = this.fourthFormGroup.value.fathercontact
  this.student.spouse = this.fourthFormGroup.value.spouse
  this.student.spousecontact = this.fourthFormGroup.value.spousecontact
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
  this.student.year = this.secondFormGroup.value.yrlevel
  this.student.department = 'CEAS'
  this.student.sem = this.sem;
  this.student.yearenrolled = this.acadyear
  this.student.schoolyear = this.acadyear
  this.student.regular = this.secondFormGroup.value.regular
  this.student.pob = this.firstFormGroup.value.birthplace
  this.student.civilstatus = this.firstFormGroup.value.civilstatus
  this.student.age = this.firstFormGroup.value.age
  this.student.type = this.studentTypeFormGroup.controls.studentType.value
  this.student.fulladdress = this.firstFormGroup.value.houseno+' '+ this.firstFormGroup.value.street+', '+this.firstFormGroup.value.city1+', '+this.firstFormGroup.value.province1;
  this.student.cy = this.cy
  this.ds.sendRequest('insertNewStudent', this.student).subscribe((res)=>{
    console.log(res)
    Swal.fire({
      icon: res[0],
      title: res[1],
      text: res[2]
    }).then(() => {
      this.router.navigate(['enlistment']);
    });
  });
}
}
}



}