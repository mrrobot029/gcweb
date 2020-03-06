import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ApplicantsforsubmissionComponent } from './applicantsforsubmission.component';

describe('ApplicantsforsubmissionComponent', () => {
  let component: ApplicantsforsubmissionComponent;
  let fixture: ComponentFixture<ApplicantsforsubmissionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ApplicantsforsubmissionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ApplicantsforsubmissionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
