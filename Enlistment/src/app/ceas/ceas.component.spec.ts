import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CeasComponent } from './ceas.component';

describe('CeasComponent', () => {
  let component: CeasComponent;
  let fixture: ComponentFixture<CeasComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CeasComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CeasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
