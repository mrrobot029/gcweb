import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Observable, fromEventPattern } from "rxjs";
import Swal from "sweetalert2";

@Injectable({
  providedIn: "root"
})
export class DataService {
  apiLink = "http://localhost/gordoncollegeweb/";
  // apiLink = 'https://gordoncollegeccs.edu.ph/gc/api/'

  constructor(private http: HttpClient) {}

  sendRequest(method, data) {
    return this.http.post<any>(
      this.apiLink + method,
      btoa(unescape(encodeURIComponent(JSON.stringify(data))))
    );
  }

  sendRequestWithFile(method, data) {
    return this.http.post<any>(this.apiLink + method, data);
  }

  async callSwal(stitle, stext, sicon) {
    Swal.fire({ title: stitle, text: stext, icon: sicon });
  }
}
