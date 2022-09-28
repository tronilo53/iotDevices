import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class DispositivosService {

  constructor(private __http: HttpClient) { }

  public instalarDispositivo(data: any) {
    return this.__http.post(`${environment.urlApi}instalarDispositivo.php`, JSON.stringify(data));
  }
}
