import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';

import { ComponentsModule } from '../components/components.module';
import { DispositivosComponent } from './dispositivos/dispositivos.component';
import { PerfilComponent } from './perfil/perfil.component';
import { DispositivosInstaladosComponent } from './dispositivos/dispositivos-instalados/dispositivos-instalados.component';



@NgModule({
  declarations: [
    DispositivosComponent,
    PerfilComponent,
    DispositivosInstaladosComponent
  ],
  imports: [
    CommonModule,
    ComponentsModule,
    RouterModule,
    FormsModule
  ],
  exports: [
    DispositivosComponent,
    PerfilComponent,
    DispositivosInstaladosComponent
  ]
})
export class PagesModule { }
