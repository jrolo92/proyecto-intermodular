import { AfterViewInit, Component, inject, OnInit, ElementRef, ViewChildren, QueryList } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Carrera } from '../interfaces/carrera';
import { CarreraComponent } from '../components/carrera/carrera.component';
import { FooterComponent } from '../components/footer/footer.component';
import { IonHeader, IonToolbar, IonButtons, IonMenuButton, IonTitle, IonContent, IonCard, IonCardHeader, IonCardTitle, IonCardContent, IonList, IonItem, IonLabel, IonSkeletonText, IonCardSubtitle } from '@ionic/angular/standalone';
import { IonGrid, IonRow, IonCol, IonButton } from '@ionic/angular/standalone'; // componentes de la rejilla y del select del formulario
import { CommonModule } from '@angular/common';
import { ToastController, AlertController, ModalController, LoadingController } from '@ionic/angular/standalone';
import { FormularioModalComponent } from '../components/formulario-modal/formulario-modal.component';
import { CarreraService } from '../services/carrera-service';
import { AnimationController, Animation } from '@ionic/angular/standalone';
import { SettingsService } from '../services/settings.service';

@Component({
  selector: 'app-folder',
  templateUrl: './folder.page.html',
  styleUrls: ['./folder.page.scss'],
  standalone: true,
  imports: [
    IonCardSubtitle, IonSkeletonText, IonLabel, IonItem, IonList,
    IonCardContent, IonCardTitle, IonCardHeader, IonCard, IonHeader, IonToolbar, IonButtons,
    IonMenuButton, IonTitle, IonContent, CarreraComponent, CommonModule, FooterComponent,
    IonGrid, IonRow, IonCol, IonButton
  ],
})

export class FolderPage implements OnInit, AfterViewInit {

  public folder!: string;
  // Guardará la lista de carreras que nos devuelva el servicio
  public listaDeCarreras: Carrera[] = [];
  // Propiedad para controlar si está cargando la pagina o no (para mostrar skeletons):
  public cargando: boolean = true;
  // Creamos la vv de nombre de usuario vacía para mostrarla en inicio una vez cargado el valor
  nombreUsuario: string = '';

  // Obtenemos la referencia al elemento del HTML que queremos animar
  // Usamos ViewChildren para que pille todas las tarjetas en una Lista
  @ViewChildren('tarjetaExterna', { read: ElementRef }) tarjetaExterna!: QueryList<ElementRef>;
  private animacion!: Animation;

  private activatedRoute = inject(ActivatedRoute);

  //  Lista de secciones válidas del menú
  private seccionesValidas = ['inicio', 'carreras', 'ajustes', 'about', 'mensajes', 'inscripciones', 'resultados', 'rutas', 'comunidad'];

  // Inyectamos los controladores necesarios en el constructor 
  constructor(
    // Controladores de componentes para notificaciones
    private toastController: ToastController,
    private alertController: AlertController,
    private modalController: ModalController,  
    // Controlador de servicio Carrera
    private carreraService: CarreraService,
    // Controlador de Animaciones
    private animationCtrl: AnimationController,
    // Servicio de Ajustes
    private settingsService: SettingsService,
    private router: Router,
    // Servicio / Controlador de indicador de carga
    private loadingController: LoadingController
  ) {
    // Simulamos un tiempo de carga de datos de 1 segundos
    setTimeout(() => {
      this.cargarCarreras();
      this.cargando = false;
    }, 1000);
  }

    // Se ejecuta cada vez que la página va a entrar en pantalla (antes de la animación)
  async ionViewWillEnter(){
    await this.cargarCarreras();
  }

  // Método que ejecuta cuando la vista ya está lista (para animaciones)
  ngAfterViewInit() {
    // Usamos el setTimeOut para asegurar que no empieza la animación hasta que este todo cargado
    setTimeout(() => {
      this.tarjetaExterna.forEach(tarjeta => {
        // Creamos la animación
        const animacion = this.animationCtrl
          .create()
          .addElement(tarjeta.nativeElement)
          .duration(800)
          .fromTo('transform', 'translateX(-100px)', 'translateX(0px)')
          .fromTo('opacity', '0', '1');
        animacion.play();
      });
    }, 1000);
  }

  async ngOnInit() {

    this.activatedRoute.paramMap.subscribe(async params => {

      this.folder = params.get('id') as string;

      // Si el ID no existe, redirigimos y mostramos toast
      if (!this.seccionesValidas.includes(this.folder)) {
        this.mostrarToastYRedirigir();
        return;
      }

      // Cargar ajustes de modo oscuro
      const oscuro = await this.settingsService.get('modo_oscuro') || false;
      document.body.classList.toggle('dark', oscuro);

      // Cargar ajustes de nombre de usuario del servicio
      this.nombreUsuario = await this.settingsService.get('nombre_usuario') || '';

      if (this.folder === 'inicio') {
        // Aquí irá el contenido del apartado Inicio
      }

      if (this.folder === 'carreras') {
        // Llamamos al método que carga las carreras
        this.cargarCarreras();
      }

      if (this.folder === 'ajustes') {
        // Aquí irá el contenido del apartado Ajustes (si lo necesitas)
      }

    });

  }

  // Aquí van a ir los métodos (fuera del ngOnInit)
  // Método para cargar el array de carreras (con mensaje de loading)
  async cargarCarreras() {
    // Creamos y mostramos el loading
    const loading = await this.loadingController.create({
      message: 'Cargando carreras...',
      spinner: 'circles'
    });
    await loading.present();

    // Debemos esperar a que lleguen los datos del servidor
    try {
      this.listaDeCarreras = await this.carreraService.getCarreras();

    } catch (error){
      console.error('Error al cargar las carreras:', error);
      this.mostrarError('No se pudieron cargar las tareas. Comprueba tu conexión.')

    } finally {
      // Cerramos el loading al terminar
      loading.dismiss();
    }
  }

  /**
   *  Método para implementar la funcionalidad de agregar una carrera (servicio)
   */
  async guardarNuevaCarrera(carrera: Carrera) {
    // Creamos y mostramos el loading
     const loading = await this.loadingController.create({
      message: 'Guardando carrera...',
      spinner: 'crescent'
    });
    await loading.present();

    try {
      // Llamamos al sevicio para agregar carreras
      await this.carreraService.agregarCarrera(carrera);
      // Si todo va bien, cerramos loading y refrescamos la lista de carreras
      await loading.dismiss();
      await this.cargarCarreras();

      // Creamos toast para notificación de éxito
      const toast = await this.toastController.create({
        message: `¡Carrera "${carrera.titulo}" guardada con éxito!`,
        duration: 2500,
        color: 'success',
        position: 'bottom',
        buttons: [{ text: 'OK', role: 'cancel' }]
      });
      await toast.present();

    } catch (error) {
      // En caso de error, cerramos loading y avisamos al usuario
      await loading.dismiss();
      console.error('Error al guardar:', error);

      this.mostrarError('No se pudo guardar la carrera');
    }
  }

  /**
   *  Método para implementar la funcionalidad de borrado de una carrera
   */
  async borrarCarrera (id:string | number){
    try {
      // Llamamos al servicio para borrar
      await this.carreraService.deleteCarrera(id);

      // Creamos toast para notificación de éxito
      const toast = await this.toastController.create({
        message: `¡Carrera con id: "${id}" ha sido borrada con éxito!`,
        duration: 2500,
        color: 'success',
        position: 'bottom'
      });
      await toast.present();

      // Recargamos la lista para que desaparezca
      await this.cargarCarreras();
    } catch (error) {
      console.error('Error al borrar: ', error);
      this.mostrarError('No se pudo borrar la carrera')
    }
  }

  /**
   *  Método para implementar la actualización de una carrera
   */
  async actualizarCarrera(carrera: Carrera) {
    try {
      // Enviamos el objeto completo con todos los cambios modificados
      await this.carreraService.updateCarrera(carrera);
      console.log('Todos los cambios guardados');
      // Mostramos un toast de confirmación
      const toast = await this.toastController.create({
        message: 'Carrera actualizada',
        duration: 1500,
        position: 'bottom',
        color: 'succes'
      });
      await toast.present();
    } catch (error) {
      console.error('Error al guardar cambios:', error);
      this.mostrarError('No se pudo modificar la carrera');
    }
  }

  // Método para cargar los detalles de una carrera
  verDetalle(id: string | number) {
    // Esto navegará a /folder/carreras/1 (por ejemplo)
    this.router.navigate(['/folder/carreras', id]);
  }

  // Método para abrir el modal del formulario
  async abrirModalFormulario() {

    const modal = await this.modalController.create({
      component: FormularioModalComponent, // El componente que se mostrará dentro
    });

    // Aquí presentas el modal en pantalla
    await modal.present();

    // Esperas a que se cierre y recibes datos
    const { data } = await modal.onDidDismiss<Carrera>();
    // Si hay datos en el formulario
    if (data) {
      try {
        // Llamamos al servicio para enviar el objeto al server
        const carreraCreada = await this.carreraService.agregarCarrera(data);
        // Añadimos el resultado a nuestra lista y la ponemos al principio
        this.listaDeCarreras.unshift(data);
        // Mostramos mensaje de confirmación de tipo toast:
        const toast = await this.toastController.create({
          message: `Carrera "${data.titulo}" añadida correctamente`,
          duration: 1000,
          color: 'success'
        });
        await toast.present();
      } catch (error){
        console.error('Error al guardar la carrera:', error);
      }    
    }
  }

  // Método para manejar IDs inválidos
  private async mostrarToastYRedirigir() {
    const toast = await this.toastController.create({
      message: 'Elemento no encontrado',
      duration: 1500,
      color: 'danger'
    });

    await toast.present();

    // Pequeño retraso para que el toast se vea antes de redirigir
    setTimeout(() => {
      this.router.navigate(['/folder/inicio']);
    }, 300);
  }

  // Método para mostrar errores
  async mostrarError(mensaje: string) {
    const toast = await this.toastController.create({
      message: mensaje,
      duration: 3000,
      color: 'danger',
      position: 'bottom',
      icon: 'alert-circle-outline'
    });
    await toast.present();
  }
}
