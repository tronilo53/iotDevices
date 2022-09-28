import { Injectable } from '@angular/core';
import Swal from 'sweetalert2';

@Injectable({
  providedIn: 'root'
})
export class ModalesService {

  constructor() { }

  public success(message: string): void {
    Swal.fire({ icon: 'success', title: 'Success', text: message });
  }
  public error(message: string): void {
    Swal.fire({ icon: 'error', title: 'Error', text: message });
  }
  public notificacion(title: string): void {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })
    
    Toast.fire({
      icon: 'success',
      title
    })
  }
}
