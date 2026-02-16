import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonContent, IonHeader, IonTitle, IonToolbar } from '@ionic/angular/standalone';

import { ActivatedRoute } from '@angular/router';
import { CarreraService } from 'src/app/services/carrera-service';
import { Carrera } from 'src/app/interfaces/carrera';
import { Dificultad } from 'src/app/interfaces/carrera';

import { AlertController, ModalController, ToastController } from '@ionic/angular/standalone';
import { FormularioModalComponent } from '../../components/formulario-modal/formulario-modal.component';


import{ IonButtons, IonBackButton, IonBadge, IonCard, IonCardHeader, IonCardSubtitle,
        IonIcon, IonCardTitle, IonCardContent, IonChip, IonLabel, IonButton, IonSpinner
 } from '@ionic/angular/standalone';
import { addIcons } from 'ionicons';
import { locationOutline, calendarOutline, speedometerOutline, prismOutline } from 'ionicons/icons';

import { Router } from '@angular/router';

@Component({
  selector: 'app-detalle-carrera',
  templateUrl: './detalle-carrera.page.html',
  styleUrls: ['./detalle-carrera.page.scss'],
  standalone: true,
  imports: [IonContent, IonHeader, IonTitle, IonToolbar, CommonModule, FormsModule, 
            IonButtons, IonBackButton, IonBadge, IonCard, IonCardHeader, IonCardSubtitle,
            IonIcon, IonCardTitle, IonCardContent, IonChip, IonLabel, IonButton, IonSpinner
            ]
})
export class DetalleCarreraPage implements OnInit {
  // esto que es???
  carrera!: Carrera;
  public DificultadEnum = Dificultad;

  constructor(
    private route: ActivatedRoute,
    private carreraService: CarreraService,
    private modalController: ModalController,
    private toastController: ToastController,
    private alertController: AlertController,
    private router: Router
  ) {
    // Registramos los iconos que vamos a usar
    addIcons({ locationOutline, calendarOutline, speedometerOutline, prismOutline });
   }

  async ngOnInit() {
    // Obtenemos el id de la carrera para la URL
    const id = this.route.snapshot.paramMap.get('carreraId');

    if (id) {
      // Pedimos al servicio solo esa carrera con el método
      this.carrera = await this.carreraService.getCarreraPorId(id);
    }
  }

  // Método para eliminar la carrera
async eliminarCarrera() {
  if (!this.carrera) return;

  const alert = await this.alertController.create({
    header: '¿Eliminar carrera?',
    message: `¿Estás seguro de que quieres borrar "${this.carrera.titulo}"? Esta acción no se puede deshacer.`,
    buttons: [
      {
        text: 'Cancelar',
        role: 'cancel',
        cssClass: 'secondary'
      },
      {
        text: 'Eliminar',
        role: 'destructive', // Esto lo pone en rojo en iOS
        handler: async () => {
          try {
            // 1. Llamamos al servicio para borrar en el servidor
            await this.carreraService.deleteCarrera(this.carrera!.id);

            // 2. Mostramos confirmación
            const toast = await this.toastController.create({
              message: 'Carrera eliminada correctamente',
              duration: 2000,
              color: 'danger'
            });
            await toast.present();

            // 3. Volvemos a la lista principal
            this.router.navigate(['/folder/carreras']); 
            
          } catch (error) {
            console.error('Error al eliminar:', error);
          }
        }
      }
    ]
  });

  await alert.present();
}

  async abrirModalEditar() {
    if (!this.carrera) return;

    const modal = await this.modalController.create({
      component: FormularioModalComponent,
      componentProps: {
        // Le pasamos la carrera actual al @Input del modal
        carreraParaEditar: this.carrera 
      }
    });

    await modal.present();

    // Esperamos a que el usuario guarde los cambios
    const { data } = await modal.onDidDismiss<Carrera>();

    if (data) {
      try {
        // 1. Enviamos la actualización al servidor (PUT)
        await this.carreraService.updateCarrera(data);
        
        // 2. Actualizamos la vista local con los nuevos datos
        this.carrera = data;

        // 3. Feedback visual
        const toast = await this.toastController.create({
          message: 'Carrera actualizada correctamente',
          duration: 2000,
          color: 'success'
        });
        await toast.present();
        
      } catch (error) {
        console.error('Error al actualizar:', error);
      }
    }
  }
}
