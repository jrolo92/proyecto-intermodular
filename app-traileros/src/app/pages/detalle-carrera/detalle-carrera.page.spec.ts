import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DetalleCarreraPage } from './detalle-carrera.page';

describe('DetalleCarreraPage', () => {
  let component: DetalleCarreraPage;
  let fixture: ComponentFixture<DetalleCarreraPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(DetalleCarreraPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
