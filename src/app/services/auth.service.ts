import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(private __http: HttpClient) { }

  public registrarUsuario(data: any): Observable<Response> {
    return this.__http.post<Response>(`${environment.urlApi}registro.php`, JSON.stringify(data));
  }
}
