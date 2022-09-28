import { Component, ElementRef, OnInit, Renderer2, ViewChild } from '@angular/core';
import { combineLatest } from 'rxjs';
import { AuthService } from 'src/app/services/auth.service';
import { ModalesService } from 'src/app/services/modales.service';
import Swal from 'sweetalert2';

interface Datos {
  nombre: string;
  email: string;
  clave: string;
}

@Component({
  selector: 'app-registro',
  templateUrl: './registro.component.html',
  styleUrls: ['./registro.component.css']
})
export class RegistroComponent implements OnInit {

  private exEmail: RegExp = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/gi;

  public datos: Datos ={
    nombre: '',
    email: '',
    clave: ''
  };

  public visibilidad: boolean = false;

  public anio: number = new Date().getFullYear();

  @ViewChild('nombre') nombre: ElementRef;
  @ViewChild('email') email: ElementRef;
  @ViewChild('clave') clave: ElementRef;

  constructor(
    private __modales: ModalesService,
    private __renderer: Renderer2,
    private __authService: AuthService
  ) { }

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
    if(componente === 'nombre') this.__renderer.setStyle(this.nombre.nativeElement, 'border', '1px solid #ced4da');
    else if(componente === 'email') this.__renderer.setStyle(this.email.nativeElement, 'border', '1px solid #ced4da');
    else this.__renderer.setStyle(this.clave.nativeElement, 'border', '1px solid #ced4da');
  }

  public onSubmit(): void {
    if(this.datos.nombre === '' || this.datos.email === '' || this.datos.clave === '') {
      
      this.__modales.error('Todos los campos son requeridos');
      if(this.datos.nombre === '') this.__renderer.setStyle(this.nombre.nativeElement, 'border', '1px solid tomato');
      if(this.datos.email === '') this.__renderer.setStyle(this.email.nativeElement, 'border', '1px solid tomato');
      if(this.datos.clave === '') this.__renderer.setStyle(this.clave.nativeElement, 'border', '1px solid tomato');
    }else {

      if(!this.exEmail.test(this.datos.email)) {

        this.__modales.error('Introduce un email válido');
        this.__renderer.setStyle(this.email.nativeElement, 'border', '1px solid tomato');
      }else {

        Swal.fire({ text: 'Cargando...' });
        Swal.showLoading();
        //peticion a la api para registrar el usuario;
        combineLatest([

          this.__authService.registrarUsuario(this.datos)
        ]).subscribe(([response]) => {

          console.log(response);
          Swal.close();
        });
      }
    }
  }
}
