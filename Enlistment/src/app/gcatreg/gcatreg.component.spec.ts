import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { GcatregComponent } from './gcatreg.component';

describe('GcatregComponent', () => {
  let component: GcatregComponent;
  let fixture: ComponentFixture<GcatregComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GcatregComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GcatregComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
