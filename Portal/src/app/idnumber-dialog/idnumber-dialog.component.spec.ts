import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { IdnumberDialogComponent } from './idnumber-dialog.component';

describe('IdnumberDialogComponent', () => {
  let component: IdnumberDialogComponent;
  let fixture: ComponentFixture<IdnumberDialogComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ IdnumberDialogComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(IdnumberDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
