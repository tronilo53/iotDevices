import { Component, Input, OnInit } from '@angular/core';
import { Usuario } from 'src/app/interfaces/response.interface';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {

  @Input() usuario: Usuario;

  constructor() { }

  ngOnInit(): void {
  }

}
