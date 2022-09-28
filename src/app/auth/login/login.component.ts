import { Component, ElementRef, OnInit, Renderer2, ViewChild } from '@angular/core';
import Swal from 'sweetalert2';

interface Datos {
  email: string;
  clave: string;
  recordar: boolean;
}

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit{

  public anio: number = new Date().getFullYear();

  public datos: Datos ={
    email: '',
    clave: '',
    recordar: false
  };

  public visibilidad: boolean = false;

  @ViewChild('email') email: ElementRef;
  @ViewChild('clave') clave: ElementRef;

  constructor(private __renderer: Renderer2) { }

  ngOnInit(): void {
  }

  public mostrarClave(): void {
    this.visibilidad = true;
    this.__renderer.setAttribute(this.clave.nativeElement, 'type', 'text');
  }
  public ocultarClave(): void {
    this.visibilidad = false;
    this.__renderer.setAttribute(this.clave.nativeElement, 'type', 'password');
  }
  public resetearBordesCampos(componente: string): void {
    if(componente === 'email') this.__renderer.setStyle(this.email.nativeElement, 'border', '1px solid #ced4da');
    else this.__renderer.setStyle(this.clave.nativeElement, 'border', '1px solid #ced4da');
  }

  public onSubmit(): void {
    if(this.datos.email === '' || this.datos.clave === '') {
      this.error('Todos los campos son requeridos');
      if(this.datos.email === '') this.__renderer.setStyle(this.email.nativeElement, 'border', '1px solid tomato');
      if(this.datos.clave === '') this.__renderer.setStyle(this.clave.nativeElement, 'border', '1px solid tomato');
    }else {
      //TODO: PETICIÓN A LA API PARA INICIAR SESIÓN.
      console.log(this.datos);
    }
  }

  private success(message: string): void {
    Swal.fire({ icon: 'success', title: 'Success', text: message });
  }
  private error(message: string): void {
    Swal.fire({ icon: 'error', title: 'Error', text: message });
  }
}
