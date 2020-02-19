import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { Notavailable2Component } from './notavailable2.component';

describe('Notavailable2Component', () => {
  let component: Notavailable2Component;
  let fixture: ComponentFixture<Notavailable2Component>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ Notavailable2Component ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(Notavailable2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
