import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, fromEventPattern } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DataService {

  // apiLink = 'http://192.168.100.12/gcweb/';
  apiLink = 'http://localhost/gordoncollegeweb/';

  constructor(private http: HttpClient) { }

  sendRequest(method, data) {
    return this.http.post<any>(this.apiLink + method, btoa(JSON.stringify(data)));
  }

  sendRequestWithFile(method, data) {
    return this.http.post<any>(this.apiLink + method, data);
  }

}
