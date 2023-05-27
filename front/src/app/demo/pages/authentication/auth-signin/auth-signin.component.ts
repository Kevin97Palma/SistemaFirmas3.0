import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { HttpClient } from '@angular/common/http';
@Component({
  selector: 'app-auth-signin',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './auth-signin.component.html',
  styleUrls: ['./auth-signin.component.scss'],
})
export default class AuthSigninComponent {
  usuario: string;
  contrasena: string;

  constructor(private http: HttpClient) {}

  login() {
    // Aquí puedes realizar la llamada a tu API PHP para autenticar al usuario
    // Puedes usar this.usuario y this.contrasena para obtener los valores del formulario
    // Ejemplo de llamada a API usando HttpClient:
    this.http.post('URL_DE_TU_API', { usuario: this.usuario, contrasena: this.contrasena })
      .subscribe(
        (response) => {
          // Manejar la respuesta de la API en caso de éxito
        },
        (error) => {
          // Manejar el error de la API en caso de fallo
        }
      );
  }
}
