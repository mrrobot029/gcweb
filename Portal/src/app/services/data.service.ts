import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, fromEventPattern } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DataService {

<<<<<<< HEAD
  // apiLink = 'http://192.168.100.12/gcweb/';
  apiLink = 'http://gordoncollegeccs.edu.ph/gc/api/';
  // apiLink = 'http://localhost/gordoncollegeweb/';
=======
//   apiLink = 'http://192.168.100.12/gcweb/';
  apiLink = 'http://gordoncollegeccs.edu.ph/gc/api/';
>>>>>>> 0fb1467a17ca2b7b5af20ffd22be4a78a8e1638f

  constructor(private http: HttpClient) { }

  sendRequest(method, data) {
    // console.log(btoa(unescape(encodeURIComponent((JSON.stringify(data))))))
    return this.http.post<any>(this.apiLink + method, btoa(unescape(encodeURIComponent((JSON.stringify(data))))));
  }


  sendRequestWithFile(method, data) {
    return this.http.post<any>(this.apiLink + method, data);
  }

}
