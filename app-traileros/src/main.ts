// Imports para poner el idioma en español
import { registerLocaleData } from '@angular/common';
import localeEs from '@angular/common/locales/es';
registerLocaleData(localeEs);
import { LOCALE_ID } from '@angular/core';

import { bootstrapApplication } from '@angular/platform-browser';
import { RouteReuseStrategy, provideRouter, withPreloading, PreloadAllModules } from '@angular/router';
import { IonicRouteStrategy, provideIonicAngular } from '@ionic/angular/standalone';

// Imports para utilidades de Ionic Storage
import { importProvidersFrom } from '@angular/core';
import { IonicStorageModule } from '@ionic/storage-angular';
import { Drivers } from '@ionic/storage';

// Imports para el proveedor HTTP (JSON Server)
import { provideHttpClient } from '@angular/common/http';

import { routes } from './app/app.routes';
import { AppComponent } from './app/app.component';

// Iconos instalados manualmente
import { addIcons } from 'ionicons';
import { informationCircle, informationCircleOutline, informationCircleSharp, settings, settingsOutline, settingsSharp } from 'ionicons/icons';

addIcons({
  'information-circle': informationCircle,
  'information-circle-outline': informationCircleOutline,
  'information-circle-sharp': informationCircleSharp,
  'settings': settings,
  'settings-outline': settingsOutline,
  'settings-sharp': settingsSharp
})

bootstrapApplication(AppComponent, {
  providers: [
    { provide: RouteReuseStrategy, useClass: IonicRouteStrategy },
    provideIonicAngular(),
    provideRouter(routes, withPreloading(PreloadAllModules)),
    // Esta línea también es necesaria para cambiar el idioma a español
    { provide: LOCALE_ID, useValue: 'es'},

    // Habilitar cliente HTTP (JSON Server)
    provideHttpClient(),

    // Configuración Ionic Storage
    importProvidersFrom(
      IonicStorageModule.forRoot({
        name: '__mydb', // Nombre de la base de datos
        driverOrder: [Drivers.IndexedDB, Drivers.LocalStorage] // Orden de preferencia
      })
    ),
  ]
});
