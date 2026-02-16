import { Injectable } from '@angular/core';
import { Carrera, Dificultad } from '../interfaces/carrera';
// Http client (JSON server)
import { HttpClient } from '@angular/common/http';
// Utilidad para promesas
import {first, firstValueFrom } from 'rxjs';
// Import para usar la vv global desde environment
import { environment } from 'src/environments/environment';


@Injectable({
  providedIn: 'root'
})
export class CarreraService {

  // Definir la URL base de nuestra API (Por defecto JSON server: puerto 3000)
  private _url = `${environment.apiUrl}/carreras`;

  // Inyectamos el servicio HTTP (JSON Server) en el constructor
  constructor(private http: HttpClient){}

  /**
   *  Método para obtener todas las carreras (GET).
   */
  async getCarreras(): Promise<Carrera[]> {
    // Hacemos la petición GET a la URL.
    // Usamos el genérico <Carrera[]> para decirle a TypeScript que esperamos un array de carreras.
    // firstValueFrom convierte el Observable en una Promesa.
    return firstValueFrom(
      this.http.get<Carrera[]>(this._url)
    );
  };

  /**
   *  Método para obtener una carrera por su ID (GET /carreras/ID).
   */
  async getCarreraPorId(id: string | number): Promise<Carrera> {
    // Preparamos la URL específica
    const urlEspecifica = `${this._url}/${id}`;
    return firstValueFrom(
      this.http.get<Carrera>(urlEspecifica)
    );
  }

  /**
   *  Método para añadir nuevas carreras al servidor (POST /carreras)
   */
  async agregarCarrera(carrera: Carrera): Promise<Carrera> {
    // 1. Eliminamos el ID antes de enviar
    // Creamos una copia del objeto sin el campo id
    const {id, ...carreraSinId} = carrera;

    // 2. Hacemos petición post para envíar el objeto sin id
    // Parámetros: URL de la coleccion y el objeto que se va a crear
    return firstValueFrom(
      this.http.post<Carrera>(this._url, carreraSinId)
    );
  }

  /**
   *  Método para actualizar una carrera existente (PUT /carreras/id)
   * Se envía el objeto completo con los cambios ya aplicados
   */
  async updateCarrera(carrera: Carrera) : Promise<Carrera> {
    // Preparamos la URL específica
    const urlEspecifica = `${this._url}/${carrera.id}`;

    // Hacemos la petición PUT enviando el objeto modificado
    return firstValueFrom(
      this.http.put<Carrera>(urlEspecifica, carrera)
    );

  }

  /**
   *  Método para eliminar una tarea por su id (DELETE /carreras/id)
   */
  async deleteCarrera(id: string | number): Promise<void> {
    const urlEspecifica = `${this._url}/${id}`;

    // Hacemos la petición DELETE. No enviamos body.
    // firstValueFrom convierte el Observable en Promesa.
    return firstValueFrom(
      this.http.delete<void>(urlEspecifica)
    );
  }

}
