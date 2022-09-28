import { Component, ElementRef, OnDestroy, OnInit, Renderer2, ViewChild } from '@angular/core';
import { combineLatest } from 'rxjs';
import { Dispositivos } from 'src/app/interfaces/dispositivos.interface';
import { DispositivosService } from 'src/app/services/dispositivos.service';
import { ModalesService } from 'src/app/services/modales.service';
import Swal from 'sweetalert2';

interface DatosInstalacionDispositivo {
  alias: string;
  serie: string;
}

@Component({
  selector: 'app-dispositivos',
  templateUrl: './dispositivos.component.html',
  styleUrls: ['./dispositivos.component.css']
})
export class DispositivosComponent implements OnInit, OnDestroy {

  @ViewChild('alias') alias: ElementRef;
  @ViewChild('serie') serie: ElementRef;

  public datosInstalacion: DatosInstalacionDispositivo = {
    alias: '',
    serie: ''
  };

  public dispositivos: any[] = [];

  constructor(
    private __modalesService: ModalesService,
    private __renderer: Renderer2,
    private __dispositivosService: DispositivosService
  ) { }

  ngOnInit(): void {
    
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
      /**TODO:
       * 1- Comprobar que la serie esté habilitada
       * 2- Comprobar que la serie no tenga propietario
       * 3- Instalar dispositivo
      */
     // this.dispositivos.push(this.datosInstalacion);
     // console.log(this.dispositivos);
     const datos: DatosInstalacionDispositivo = {...this.datosInstalacion};
     datos.serie = datos.serie.toUpperCase();
     
     combineLatest([
        this.__dispositivosService.instalarDispositivo(datos)
    ]).subscribe(([instalarDispositivo]) => console.log(instalarDispositivo));

     //this.__modalesService.notificacion('Dispositivo Instalado!');
    }
  }
}
