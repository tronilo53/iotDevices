import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { map } from 'rxjs/operators'
import { Observable } from 'rxjs';
import { CookieService } from 'ngx-cookie-service';
import { addHours } from 'date-fns'
import { Router } from '@angular/router';

import { environment } from 'src/environments/environment';
import { Response, ResponseDispositivos, ResponseToken, ResponseUsuario } from '../interfaces/response.interface';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private expirarUnaHora: Date = addHours( new Date(), 3 );

  constructor(
    private __http: HttpClient,
    private __cookieService: CookieService,
    private __router: Router
  ) { }

  public registrarUsuario(data: any): Observable<any> {
    return this.__http.post(`${environment.urlApi}registro.php`, JSON.stringify(data));
  }
  public loguearUsuario(data: any): Observable<any> {
    return this.__http.post(`${environment.urlApi}login.php`, JSON.stringify(data))
      .pipe(map((resp: any) => {
        this.guardarToken(resp.token);
        return resp;
      }));
  }
  public verificarToken(data: any): Observable<any> {
    return this.__http.post(`${environment.urlApi}verificarToken.php`, JSON.stringify(data));
  }
  public obtenerUsuarioLogueado(data: any): Observable<any> {
    return this.__http.post(`${environment.urlApi}obtenerUsuarioLogueado.php`, JSON.stringify(data));
  }
  public obtenerDispositivosUsuario(data: any): Observable<any> {
    return this.__http.post(`${environment.urlApi}obtenerDispositivosUsuario.php`, JSON.stringify(data));
  }

  public logout(): void {
    this.__cookieService.delete('token');
  }
  public autenticadoGuard(): boolean {
    return this.__cookieService.get('token').length > 2;
  }
  public autenticado(): void {
    if(this.__cookieService.get('token').length < 2) this.__router.navigate(['/login']);
  }
  public eleminarToken(): void {
    this.__cookieService.delete('token');
  }
  private guardarToken(token: string): void {
    this.__cookieService.set('token', token, { expires: this.expirarUnaHora, path: '/' });
  }
}
