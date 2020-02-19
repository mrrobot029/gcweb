import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SchoolorgsComponent } from './schoolorgs.component';

describe('SchoolorgsComponent', () => {
  let component: SchoolorgsComponent;
  let fixture: ComponentFixture<SchoolorgsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SchoolorgsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SchoolorgsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
