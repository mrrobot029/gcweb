import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { GcatattendanceComponent } from './gcatattendance.component';

describe('GcatattendanceComponent', () => {
  let component: GcatattendanceComponent;
  let fixture: ComponentFixture<GcatattendanceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GcatattendanceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GcatattendanceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
