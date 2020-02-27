import { Injectable, EventEmitter } from '@angular/core';    
import { Subscription } from 'rxjs/internal/Subscription';    

@Injectable({
  providedIn: 'root'
})
export class EventEmitterService {
  invokeGetCount = new EventEmitter();    
  subsVar: Subscription;    
  constructor() { }

  onGetCount() {    
    this.invokeGetCount.emit();    
  }    
}
