import { Routes } from '@angular/router';

export const routes: Routes = [
  {
    path: '',
    redirectTo: 'folder/inicio',
    pathMatch: 'full',
  },
    // Ruta para mostrar detalles de carreras
  {
    path: 'folder/carreras/:carreraId', 
    loadComponent: () => import('./pages/detalle-carrera/detalle-carrera.page').then( m => m.DetalleCarreraPage)
  },
  // Ruta para mostrar la pagina de ajustes
  {
    path: 'folder/ajustes',
    loadComponent: () => import('./pages/ajustes/ajustes.page').then((m) => m.AjustesPage),
  },
  {
    path: 'folder/:id',
    loadComponent: () => import('./folder/folder.page').then((m) => m.FolderPage),
  },
];
