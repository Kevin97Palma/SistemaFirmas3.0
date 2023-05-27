import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { PersonaService } from './servicios/persona.service';
const routes: Routes = [
  {
    path: '',
    children: [
      {
        path: 'signup',
        loadComponent: () => import('./auth-signup/auth-signup.component'),
      },
      {
        path: 'signin',
        loadComponent: () => import('./auth-signin/auth-signin.component'),
      },
    
    ],
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AuthenticationRoutingModule {}
