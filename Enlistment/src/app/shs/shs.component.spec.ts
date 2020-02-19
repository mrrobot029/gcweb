import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ShsComponent } from './shs.component';

describe('ShsComponent', () => {
  let component: ShsComponent;
  let fixture: ComponentFixture<ShsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ShsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ShsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
