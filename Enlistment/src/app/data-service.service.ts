import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { from } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DataService {
  apiLink: string = "https://gordoncollegeccs.edu.ph/gc/api/";
  // apiLink: string = "http://localhost/gordoncollegeweb/";
  // private serviceUrl = 'https://jsonplaceholder.typicode.com/users';
  
  constructor(private http: HttpClient) { }


  sendRequest(method, data){
    return this.http.post<any>(this.apiLink + method, btoa(unescape(encodeURIComponent((JSON.stringify(data))))));
  }

  update(num, data){
    return this.http.post(this.apiLink+"update.php?z="+num,
    JSON.stringify(data));
  }
}
