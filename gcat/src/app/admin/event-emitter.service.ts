import { Injectable, EventEmitter } from "@angular/core";
import { BehaviorSubject } from "rxjs";
import { DataService } from "../services/data.service";

@Injectable({
  providedIn: "root"
})
export class EventEmitterService {
  private unconfirmedCountSource = new BehaviorSubject(0);
  private confirmedCountSource = new BehaviorSubject(0);
  private scheduledCountSource = new BehaviorSubject(0);
  constructor(private ds: DataService) {
    this.ds.sendRequest("getUnconfirmedCount", null).subscribe(res => {
      this.unconfirmedCountSource.next(res.data[0].unconfirmedCount);
    });
    this.ds.sendRequest("getConfirmedCount", null).subscribe(res => {
      this.confirmedCountSource.next(res.data[0].confirmedCount);
    });
    this.ds.sendRequest("getScheduledCount", null).subscribe(res => {
      this.scheduledCountSource.next(res.data[0].scheduledCount);
    });
  }

  getCount() {
    this.ds.sendRequest("getUnconfirmedCount", null).subscribe(res => {
      this.unconfirmedCountSource.next(res.data[0].unconfirmedCount);
    });
    this.ds.sendRequest("getConfirmedCount", null).subscribe(res => {
      this.confirmedCountSource.next(res.data[0].confirmedCount);
    });
    this.ds.sendRequest("getScheduledCount", null).subscribe(res => {
      this.scheduledCountSource.next(res.data[0].scheduledCount);
    });
  }

  getUnconfirmedCount() {
    return this.unconfirmedCountSource.asObservable();
  }
  getConfirmedCount() {
    return this.confirmedCountSource.asObservable();
  }
  getScheduledCount() {
    return this.scheduledCountSource.asObservable();
  }

  setUnconfirmedCount(e) {
    this.unconfirmedCountSource.next(e);
  }
}
