import { Component, Input, OnInit } from '@angular/core';
import { DispositivosService } from 'src/app/services/dispositivos.service';

@Component({
  selector: 'app-dispositivos-instalados',
  templateUrl: './dispositivos-instalados.component.html',
  styleUrls: ['./dispositivos-instalados.component.css']
})
export class DispositivosInstaladosComponent implements OnInit {

  @Input() dispositivos: any[] = [];

  constructor(private __dispositivosService: DispositivosService) { }

  ngOnInit(): void {
    /*this.__dispositivosService.obtenerDispositivos().subscribe(resp => {
      this.dispositivos.push(...resp);
      console.log(resp);
    });*/
  }

}
