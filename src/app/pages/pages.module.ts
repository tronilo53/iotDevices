import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DashboardComponent } from './dashboard/dashboard.component';
import { ComponentsModule } from '../components/components.module';
import { DispositivosComponent } from './dispositivos/dispositivos.component';
import { PerfilComponent } from './perfil/perfil.component';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { DispositivosInstaladosComponent } from './dispositivos/dispositivos-instalados/dispositivos-instalados.component';



@NgModule({
  declarations: [
    DashboardComponent,
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
    DashboardComponent,
    DispositivosComponent,
    PerfilComponent,
    DispositivosInstaladosComponent
  ]
})
export class PagesModule { }
