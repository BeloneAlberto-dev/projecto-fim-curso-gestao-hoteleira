const navLinks = document.querySelectorAll('.nav-menu .nav-link');
const menuOpenButton = document.querySelector('#menu-open-button');
const menuCloseButton = document.querySelector('#menu-close-button');

menuOpenButton.addEventListener('click', () => {
    document.body.classList.toggle('show-mobile-menu');
});

//Fechar menu com click no botao clouse
menuCloseButton.addEventListener('click', () => menuOpenButton.click());
//Fechar menu com click na lista de navegação
navLinks.forEach(link => {
  link.addEventListener('click', () =>  menuOpenButton.click());
})

// Initialize Swiper

const swiper = new Swiper('.slider-wrapper', {
  loop: true,
  grabCursor: true,
  spaceBetween: 25,

  // If we need pagination
  pagination: {
    el: '.swiper-pagination',
    clickable:true,
    dynamicBullets:true,
  },

  // Navigation arrows
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
// Responsivo
  breakpoints:{
    0: {
        slidesPerView: 1
    },
    768: {
        slidesPerView: 2
    },
    1024: {
        slidesPerView: 3
    }
  }
});


function mostrarMapa() {
    var mapa = document.getElementById("mapa");
    
    if (mapa.style.display === "none") {
        mapa.style.display = "block";
    } else {
        mapa.style.display = "none";
    }
}