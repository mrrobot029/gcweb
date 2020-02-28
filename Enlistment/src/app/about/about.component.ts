import { Component, OnInit, HostListener, ChangeDetectorRef } from '@angular/core';
import { MediaMatcher } from '@angular/cdk/layout';
@Component({
  selector: 'app-about',
  templateUrl: './about.component.html',
  styleUrls: ['./about.component.css']
})
export class AboutComponent implements OnInit {
  mobileQuery: MediaQueryList;

  private _mobileQueryListener: () => void;
  
  public innerWidth: any;
  break = false
  constructor(changeDetectorRef: ChangeDetectorRef, media: MediaMatcher) {
    this.mobileQuery = media.matchMedia('(max-width: 600px)');
    this._mobileQueryListener = () => changeDetectorRef.detectChanges();
    this.mobileQuery.addListener(this._mobileQueryListener);
   }
  animating() { }
  animated() { }

  log(msg) {  }
  
  ngOnInit() {
    this.innerWidth = window.innerWidth;
    if(this.innerWidth < 488){
      this.break = true
    } else{
      this.break = false
    }
  }

  @HostListener('window:resize', ['$event'])
  onResize(event) {
  this.innerWidth = window.innerWidth;
  if(this.innerWidth < 488){
    this.break= true
  } else{
    this.break = false
  }
}


  scrollToElement($element){
    $element.scrollIntoView({behavior: "smooth", block: "end", inline: "nearest"});
  }
}
