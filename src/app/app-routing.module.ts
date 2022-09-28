import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './auth/login/login.component';
import { RegistroComponent } from './auth/registro/registro.component';
import { DashboardComponent } from './pages/dashboard/dashboard.component';
import { DispositivosComponent } from './pages/dispositivos/dispositivos.component';
import { PerfilComponent } from './pages/perfil/perfil.component';

const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { path: 'registro', component: RegistroComponent },
  { 
    path: 'dashboard', 
    component: DashboardComponent,
    children: [
      { path: 'dispositivos', component: DispositivosComponent },
      { path: 'perfil', component: PerfilComponent },
      { path: '', pathMatch: 'full', redirectTo: 'dispositivos' },
      { path: '**', pathMatch: 'full', redirectTo: 'dispositivos' }
    ] 
  },
  { path: '', pathMatch: 'full', redirectTo: 'dashboard' },
  { path: '**', pathMatch: 'full', redirectTo: 'dashboard' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
