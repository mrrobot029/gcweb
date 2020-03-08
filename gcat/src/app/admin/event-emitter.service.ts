import { Injectable, EventEmitter } from "@angular/core";
import { BehaviorSubject } from "rxjs";

@Injectable({
  providedIn: "root"
})
export class EventEmitterService{
  private unconfirmedCount
  private confirmedCount 
  private scheduledCount
  constructor() {

  }

  
  setUnconfirmedCount(e){
    this.unconfirmedCount = e
  }

  setConfirmedCount(e){
    this.confirmedCount = e
  }

  setScheduledCount(e){
    this.scheduledCount = e
  }

  getUnconfirmedCount(){
    return this.unconfirmedCount
  }

  getConfirmedCount(){
    return this.confirmedCount
  }

  getScheduledCount(){
    return this.scheduledCount
  }
  

}
