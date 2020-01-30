import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';


@Injectable({
  providedIn: 'root'
})
export class DataService {


  apiLink = 'http://localhost/gordoncollegeweb/';
  // apiLink = 'http://gordoncollegeccs.edu.ph/gc/api/';

  constructor(private http: HttpClient) {}

  sendrequest(method, data) {
    return this.http.post<any>( this.apiLink + method, btoa(JSON.stringify(data)));
  }


}
