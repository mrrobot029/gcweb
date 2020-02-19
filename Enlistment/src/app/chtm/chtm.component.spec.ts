import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChtmComponent } from './chtm.component';

describe('ChtmComponent', () => {
  let component: ChtmComponent;
  let fixture: ComponentFixture<ChtmComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChtmComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChtmComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
