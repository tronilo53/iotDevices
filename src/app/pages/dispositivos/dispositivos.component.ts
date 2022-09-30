import { Component, ElementRef, OnDestroy, OnInit, Renderer2, ViewChild } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { combineLatest } from 'rxjs';
import Swal from 'sweetalert2';

import { AuthGuard } from 'src/app/guards/auth.guard';
import { AuthService } from 'src/app/services/auth.service';
import { DispositivosService } from 'src/app/services/dispositivos.service';
import { ModalesService } from 'src/app/services/modales.service';
import { Dispositivos, Usuario } from 'src/app/interfaces/response.interface';

@Component({
  selector: 'app-dispositivos',
  templateUrl: './dispositivos.component.html',
  styleUrls: ['./dispositivos.component.css']
})
export class DispositivosComponent implements OnInit, OnDestroy {

  @ViewChild('alias') alias: ElementRef;
  @ViewChild('serie') serie: ElementRef;

  public datosInstalacion: any = {
    alias: '',
    serie: ''
  };

  public dispositivos: Dispositivos[] = [];
  public usuario: Usuario;

  public loading: boolean = true;
  public loadingBtn: boolean = false;

  constructor(
    private __modalesService: ModalesService,
    private __renderer: Renderer2,
    private __dispositivosService: DispositivosService,
    private __authService: AuthService,
    public __authGuard: AuthGuard,
    private __cookieService: CookieService
  ) {
    this.__authService.autenticado();
  }

  ngOnInit(): void {
    
    combineLatest([

      this.__authService.obtenerUsuarioLogueado({ token: this.__cookieService.get('token') }),
      this.__authService.obtenerDispositivosUsuario({ token: this.__cookieService.get('token') })
    ]).subscribe(([usuario, dispositivos]) => {
      
      this.usuario = {...usuario.usuario};
      if(dispositivos.result == 'No hay dispositivos') this.dispositivos = [];
      else this.dispositivos = dispositivos.dispositivos;
      this.loading = false;
    });
  }
  ngOnDestroy(): void {
    Swal.close();
  }

  public resetearBordesCampos(componente: string): void {
    if(componente === 'alias') this.__renderer.setStyle(this.alias.nativeElement, 'border', '1px solid #ced4da');
    else this.__renderer.setStyle(this.serie.nativeElement, 'border', '1px solid #ced4da');
  }

  public instalar(): void {
    if(this.datosInstalacion.alias === '' || this.datosInstalacion.serie === '') {
      this.__modalesService.error('Todos los campos son requeridos');
      if(this.datosInstalacion.alias === '') this.__renderer.setStyle(this.alias.nativeElement, 'border', '1px solid tomato');
      if(this.datosInstalacion.serie === '') this.__renderer.setStyle(this.serie.nativeElement, 'border', '1px solid tomato');
    }else {
      
      this.loadingBtn = true;

     const datos: any = {...this.datosInstalacion, id_usuario: this.usuario.id};
     datos.serie = datos.serie.toUpperCase();
     
     combineLatest([
        this.__dispositivosService.instalarDispositivo(datos)
    ]).subscribe(([response]) => {

      switch(response['result']) {

        case 'No existe el dispositivo':
          this.__modalesService.error(`${response['result']}. Si está en tu poder, ponte en contacto con el administrador`);
          break;
        case 'Este dispositivo está deshabilitado':
          this.__modalesService.error(`${response['result']}, ponte en contacto con el administrador`);
          break;
        case 'Este dispositivo ya tiene propietario':
          this.__modalesService.error(`${response['result']}. Ponte en contacto con el administrador`);
          break;
        case 'No se ha podido instalar el dispositivo':
          this.__modalesService.error(`${response['result']}. Inténtalo de nuevo más tarde`);
          break;
        case 'Dispositivo instalado':
          this.dispositivos.push(this.datosInstalacion);
          this.__modalesService.notificacion('Dispositivo Instalado!');
      }

      console.log(response);
      this.loadingBtn = false;
    });
    }
  }
}
