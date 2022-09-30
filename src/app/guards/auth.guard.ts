import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, Router } from '@angular/router';
import { CookieService } from 'ngx-cookie-service';
import { combineLatest } from 'rxjs';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {

  public loading: boolean;

  constructor(
    private __authService: AuthService,
    private __router: Router,
    private __cookieService: CookieService
  ) {
    this.loading = true;
  }

  canActivate( __activatedRouteSnapshot: ActivatedRouteSnapshot): boolean {

    //Si exite un token en las cookies
    if(this.__authService.autenticadoGuard()) {

      //llamar api para verificar que existe el token
      combineLatest([
        this.__authService.verificarToken({ token: this.__cookieService.get('token') })
      ]).subscribe(([response]) => {

        if(response.result === 'El token no existe') {
          this.__authService.eleminarToken();
          this.__router.navigate(['/login']);
          return false;
        }

        this.loading = false;
        return true;
      });
    }else {

      this.__authService.eleminarToken();
      this.__router.navigate(['/login']);
      return false;
    }

    return null;
  }
}
