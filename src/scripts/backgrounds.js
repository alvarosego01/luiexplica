

const origin = window.location.origin;

const homeVideo = `${origin}/wp-content/uploads/2025/07/rostro-flotante-izquierda.mp4`;
const servicesVideo = `${origin}/wp-content/uploads/2025/07/service-hero.mp4`;
const method360Video = `${origin}/wp-content/uploads/2025/07/metodo-360.mp4`;
const aboutUsVideo = `${origin}/wp-content/uploads/2025/07/nosotros-video.mp4`;
const blogVideo = `${origin}/wp-content/uploads/2025/07/blog-video.mp4`;
const contactVideo = `${origin}//wp-content/uploads/2025/07/contacto-video.mp4`;

function backgroundHome() {

  // Helper to create a video element with common attributes
  function createVideoElement(src) {
    const video = document.createElement('video');
    video.src = src;
    video.autoplay = true;
    video.loop = true;
    video.muted = true;
    video.playsInline = true;
    video.classList.add('background-hero-video');
    return video;
  }

  // Array of page configs: selector and video src
  const configs = [
    {
      desktopSelector: '.header-hero.home',
      mobileSelector: '.header-hero-mobile.home .header-hero-content-video-mobile',
      videoSrc: homeVideo
    },
    {
      desktopSelector: '.header-hero.services',
      mobileSelector: '.header-hero-mobile.services .header-hero-content-video-mobile',
      videoSrc: servicesVideo
    },
    {
      desktopSelector: '.header-hero.method-360',
      mobileSelector: '.header-hero-mobile.method-360 .header-hero-content-video-mobile',
      videoSrc: method360Video
    },
    {
      desktopSelector: '.header-hero.about-us',
      mobileSelector: '.header-hero-mobile.about-us .header-hero-content-video-mobile',
      videoSrc: aboutUsVideo
    },
    {
      desktopSelector: '.header-hero.blog',
      mobileSelector: '.header-hero-mobile.blog .header-hero-content-video-mobile',
      videoSrc: blogVideo
    },
    {
      desktopSelector: '.header-hero.contact',
      mobileSelector: '.header-hero-mobile.contact .header-hero-content-video-mobile',
      videoSrc: contactVideo
    }
  ];

  configs.forEach(({ desktopSelector, mobileSelector, videoSrc }) => {
    const desktop = document.querySelector(desktopSelector);
    if (desktop) {
      const video = createVideoElement(videoSrc);
      desktop.appendChild(video);
    }
    const mobile = document.querySelector(mobileSelector);
    if (mobile) {
      const video = createVideoElement(videoSrc);
      mobile.appendChild(video);
    }
  });

}

jQuery(document).ready(function () {
  backgroundHome();

});