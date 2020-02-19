import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CollegeprofileComponent } from './collegeprofile.component';

describe('CollegeprofileComponent', () => {
  let component: CollegeprofileComponent;
  let fixture: ComponentFixture<CollegeprofileComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CollegeprofileComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CollegeprofileComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
