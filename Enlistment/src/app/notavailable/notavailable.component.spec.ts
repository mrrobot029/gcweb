import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NotavailableComponent } from './notavailable.component';

describe('NotavailableComponent', () => {
  let component: NotavailableComponent;
  let fixture: ComponentFixture<NotavailableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NotavailableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NotavailableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
