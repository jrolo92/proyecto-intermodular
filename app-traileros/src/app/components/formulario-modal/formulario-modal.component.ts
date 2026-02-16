import { Component, Input, OnInit } from '@angular/core';
import { Carrera, Dificultad } from '../../interfaces/carrera';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import {
  IonInput,
  IonItem,
  IonList,
  IonLabel,
  IonSelect,
  IonSelectOption,
  IonButton,
  ModalController,
  AlertController,
  ToastController
} from '@ionic/angular/standalone';

@Component({
  selector: 'app-formulario-modal',
  templateUrl: './formulario-modal.component.html',
  styleUrls: ['./formulario-modal.component.scss'],
  standalone: true,
  imports: [
    FormsModule,
    CommonModule,
    IonInput,
    IonItem,
    IonList,
    IonLabel,
    IonSelect,
    IonSelectOption,
    IonButton,
  ]
})
export class FormularioModalComponent implements OnInit {
  
  // 🔹 Si FolderPage no pasa nada, esto es undefined (Modo Crear)
  // 🔹 Si DetallePage pasa la carrera, esto tiene datos (Modo Editar)
  @Input() carreraParaEditar?: Carrera;

  public nuevaCarrera: Carrera = {
    id: 0,
    titulo: "",
    dificultad: Dificultad.Moderada,
    descripcion: "",
    fecha: "",
    ubicacion: "",
    distanciaKm: null as any, // Mantengo tu lógica de null para el placeholder
    desnivelPositivo: null as any,
    imagenUrl: ""
  };

  constructor(
    private modalController: ModalController,
    private alertController: AlertController,
    private toastController: ToastController
  ) {}

  ngOnInit() {
    // Si recibimos datos, rellenamos el formulario
    if (this.carreraParaEditar) {
      this.nuevaCarrera = { ...this.carreraParaEditar };
    }
  }

  // Mantenemos tu lógica de validación
  validarDatos(): boolean {
    const c = this.nuevaCarrera;
    if (!c.titulo?.trim() || !c.descripcion?.trim() || !c.fecha?.trim() || !c.ubicacion?.trim()) return false;
    if (!c.distanciaKm || c.distanciaKm <= 0) return false;
    if (!c.desnivelPositivo || c.desnivelPositivo <= 0) return false;
    if (!c.imagenUrl?.trim()) return false;
    return true;
  }

  async guardar() {
    if (!this.validarDatos()) {
      this.mostrarToastError();
      return;
    }

    const esEdicion = !!this.carreraParaEditar;

    const alert = await this.alertController.create({
      header: esEdicion ? 'Confirmar Edición' : 'Confirmar Registro',
      message: esEdicion ? '¿Quieres actualizar los datos?' : '¿Quieres guardar esta nueva carrera?',
      buttons: [
        { text: 'Cancelar', role: 'cancel' },
        {
          text: 'Aceptar',
          handler: () => {
            // Si es nueva, le damos un ID temporal (el server luego dará el real)
            if (!esEdicion) {
              this.nuevaCarrera.id = Date.now();
            }
            // 🔹 ESTO ENVÍA LOS DATOS A FOLDERPAGE o DETALLEPAGE
            this.modalController.dismiss(this.nuevaCarrera);
          }
        }
      ]
    });
    await alert.present();
  }

  cancelar() {
    this.modalController.dismiss();
  }

  private async mostrarToastError() {
    const toast = await this.toastController.create({
      message: 'Por favor, rellena todos los campos correctamente.',
      duration: 2000,
      color: 'warning'
    });
    await toast.present();
  }
}