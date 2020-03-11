import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { GcatreportsComponent } from './gcatreports.component';

describe('GcatreportsComponent', () => {
  let component: GcatreportsComponent;
  let fixture: ComponentFixture<GcatreportsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GcatreportsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GcatreportsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
