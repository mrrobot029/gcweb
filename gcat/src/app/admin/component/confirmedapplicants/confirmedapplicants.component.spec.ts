import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ConfirmedapplicantsComponent } from './confirmedapplicants.component';

describe('ConfirmedapplicantsComponent', () => {
  let component: ConfirmedapplicantsComponent;
  let fixture: ComponentFixture<ConfirmedapplicantsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ConfirmedapplicantsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ConfirmedapplicantsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
