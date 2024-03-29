import { Storage } from '@ionic/storage';
import { Component, OnInit, ElementRef } from '@angular/core';
import { Router } from '@angular/router';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.page.html',
  styleUrls: ['./navbar.page.scss'],
})
export class NavbarPage implements OnInit {


  mobile_menu_visible: any = 0;
  private toggleButton: any;
  private sidebarVisible: boolean;

  constructor(
    private router: Router,
    private element: ElementRef,
    private storage: Storage
    ) { }

  ngOnInit() {
    
    const navbar: HTMLElement = this.element.nativeElement;
    this.toggleButton = navbar.getElementsByClassName('navbar-toggler')[0];
    
  }

  logout() {
    Swal.fire({
      title: 'Log Out',
      icon: 'warning',
      text:
        'You are about to leave this portal?',
      showConfirmButton: true,
      showCancelButton: true,
      confirmButtonText:
        'Proceed',
      cancelButtonText:
        'Cancel'
    }).then((res) => {
      if (res.value) {
        this.storage.clear();
        this.router.navigate(['/login']);
      }
    });
  }


  sidebarOpen() {
    const toggleButton = this.toggleButton;
    const body = document.getElementsByTagName('body')[0];
    setTimeout(() => {
        toggleButton.classList.add('toggled');
    }, 500);

    body.classList.add('nav-open');
    this.sidebarVisible = true;
}


sidebarClose() {
    const body = document.getElementsByTagName('body')[0];
    this.toggleButton.classList.remove('toggled');
    this.sidebarVisible = false;
    body.classList.remove('nav-open');
}

sidebarToggle() {
    var $toggle = document.getElementsByClassName('navbar-toggler')[0];

    if (this.sidebarVisible === false) {
        this.sidebarOpen();
    } else {
        this.sidebarClose();
    }

    const body = document.getElementsByTagName('body')[0];

    if (this.mobile_menu_visible === 1) {

        body.classList.remove('nav-open');
        if ($layer) {
          $layer.remove();
        }
        setTimeout(() => {
          $toggle.classList.remove('toggled');
        }, 400);
        this.mobile_menu_visible = 0;
    } else {
        setTimeout(() => {
            $toggle.classList.add('toggled');
        }, 430);

        var $layer = document.createElement('div');
        $layer.setAttribute('class', 'close-layer');

        if (body.querySelectorAll('.main-panel')) {
            document.getElementsByClassName('main-panel')[0].appendChild($layer);
        } else if (body.classList.contains('off-canvas-sidebar')) {
            document.getElementsByClassName('wrapper-full-page')[0].appendChild($layer);
        }

        setTimeout(() => {
            $layer.classList.add('visible');
        }, 100);

        $layer.onclick = function() {

            body.classList.remove('nav-open');
            this.mobile_menu_visible = 0;
            $layer.classList.remove('visible');
            setTimeout(() => {
                $layer.remove();
                $toggle.classList.remove('toggled');
            }, 400);

        }.bind(this);

        body.classList.add('nav-open');
        this.mobile_menu_visible = 1;
    }
}
}
