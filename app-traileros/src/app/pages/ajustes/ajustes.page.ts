import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { IonList, IonListHeader, IonItem, IonLabel, IonToggle, IonInput } from '@ionic/angular/standalone';
import { SettingsService } from '../../services/settings.service';


@Component({
  selector: 'app-ajustes',
  templateUrl: './ajustes.page.html',
  styleUrls: ['./ajustes.page.scss'],
  standalone: true,
  imports: [ CommonModule, FormsModule, ReactiveFormsModule, IonList, IonListHeader, IonItem, IonLabel, IonToggle, IonInput ]
})
export class AjustesPage implements OnInit {
  // Por defecto estará apagado el modo oscuro
  modoOscuro: boolean = false;
  // Creo una vv para el nombre de usuario (que se va a mostrar en la pagina inicial)
  nombreUsuario: string = '';

  constructor(private settingsService: SettingsService) { }

  // Añadimos async a este método para poder usar await dentro
  async ngOnInit() {
    // Al entrar, cargamos el valor guardado
    // Si no existe (es la primera vez), settingsService.get devuelve null, 
    // así que usamos '|| false' para que sea false por defecto.
    this.modoOscuro = await this.settingsService.get('modo_oscuro') || false;
    // Cargamos el valor guardado de nombre de usuario (si existe)
    this.nombreUsuario = await this.settingsService.get('nombre_usuario') || '';

  }

  // También debe ser async porque settingsService.set devuelve una promesa
  async cambiarModoOscuro() {
    // 1. Guardamos el nuevo valor en la base de datos
    await this.settingsService.setModoOscuro(this.modoOscuro);
    // 2. Se le aplica al body
    document.body.classList.toggle('dark', this.modoOscuro);
    
  }

  // Método para guarda el nombre de usuario
  async guardarNombre() { 
    await this.settingsService.set('nombre_usuario', this.nombreUsuario); 
  }
  
}
