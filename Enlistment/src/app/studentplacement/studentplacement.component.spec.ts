import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StudentplacementComponent } from './studentplacement.component';

describe('StudentplacementComponent', () => {
  let component: StudentplacementComponent;
  let fixture: ComponentFixture<StudentplacementComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StudentplacementComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StudentplacementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
