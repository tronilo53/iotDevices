import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './auth/login/login.component';
import { RegistroComponent } from './auth/registro/registro.component';
import { AuthGuard } from './guards/auth.guard';
import { DispositivosComponent } from './pages/dispositivos/dispositivos.component';
import { PerfilComponent } from './pages/perfil/perfil.component';

const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { path: 'registro', component: RegistroComponent },
  { path: 'dispositivos', component: DispositivosComponent, canActivate: [ AuthGuard ]},
  { path: 'perfil', component: PerfilComponent, canActivate: [ AuthGuard ] },
  { path: '', pathMatch: 'full', redirectTo: 'dispositivos' },
  { path: '**', pathMatch: 'full', redirectTo: 'dispositivos' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }