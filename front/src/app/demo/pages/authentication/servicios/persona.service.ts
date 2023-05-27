import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { server } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class PersonaService {

  constructor(private http:HttpClient) { }

  iniciarSesion(data:any){
    return this.http.post(`${server}/persona/iniciarSesion.php`,data)
  }

}
