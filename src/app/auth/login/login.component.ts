import { Component, ElementRef, OnInit, Renderer2, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { CookieService } from 'ngx-cookie-service';
import { combineLatest } from 'rxjs';

import { AuthService } from 'src/app/services/auth.service';
import { ModalesService } from 'src/app/services/modales.service';

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
  public loading: boolean = false;

  @ViewChild('email') email: ElementRef;
  @ViewChild('clave') clave: ElementRef;

  constructor(
    private __renderer: Renderer2,
    private __authService: AuthService,
    private __modalesService: ModalesService,
    private __cookieService: CookieService,
    private __router: Router
  ) {
    if(this.__cookieService.get('token').length > 2) this.__router.navigate(['/dashboard']);
  }

  ngOnInit(): void {
    if(this.__cookieService.check('email')) {
      this.datos.email = this.__cookieService.get('email');
      this.datos.recordar = true;
    }
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
      this.__modalesService.error('Todos los campos son requeridos');
      if(this.datos.email === '') this.__renderer.setStyle(this.email.nativeElement, 'border', '1px solid tomato');
      if(this.datos.clave === '') this.__renderer.setStyle(this.clave.nativeElement, 'border', '1px solid tomato');
    }else {
      this.loading = true;
      combineLatest([
        this.__authService.loguearUsuario(this.datos)
      ]).subscribe(([response]) => {
        switch(response.result) {
          case 'El usuario no existe':
            this.__modalesService.error('Usuario o contraseña Incorrectos');
          break;
          case 'la clave no coincide':
            this.__modalesService.error('Usuario o contraseña Incorrectos');
          break;
          case 'usuario deshabilitado':
            this.__modalesService.error('Esta cuenta está deshabilitada. Contacta con el administrador');
          break;
          case 'usuario correcto':
            if(this.datos.recordar) this.__cookieService.set('email', this.datos.email);
            else this.__cookieService.delete('email');
            this.__modalesService.notificacion('Bienvenido!');
            this.__router.navigate(['/dispositivos']);
          break;
        }
        this.loading = false;
      });
    }
  }
}
