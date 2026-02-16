import { Component, OnInit, Input } from '@angular/core';
import { Carrera } from 'src/app/interfaces/carrera';
import { IonCard, IonCardHeader, IonCardTitle, IonCardSubtitle, IonCardContent, IonRow, IonCol, IonImg } from "@ionic/angular/standalone";
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-carrera',
  templateUrl: './carrera.component.html',
  styleUrls: ['./carrera.component.scss'],
  standalone:true,
  imports: [IonImg, IonCol, IonRow, IonCardContent, IonCardSubtitle, IonCardTitle, IonCardHeader, IonCard, CommonModule]
})
export class CarreraComponent  implements OnInit {

  @Input() carrera!: Carrera | undefined; 
  constructor() { }

  ngOnInit() {}

}
