import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AllapplicantsComponent } from './allapplicants.component';

describe('AllapplicantsComponent', () => {
  let component: AllapplicantsComponent;
  let fixture: ComponentFixture<AllapplicantsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AllapplicantsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AllapplicantsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
