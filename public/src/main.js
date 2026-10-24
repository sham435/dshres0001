// Main JavaScript entry point for Vite build
// Import SCSS
import '../scss/main.scss';

// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Modal logic
window.homeProgrammeModal = function() {
  return {
    isOpen: false,
    selected: null,
    open(p) { this.selected = p; this.isOpen = true; document.body.style.overflow = 'hidden'; },
    close() { this.isOpen = false; document.body.style.overflow = ''; },
    getYoutubeEmbed(url) {
      if (!url) return '';
      const match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/);
      return match ? 'https://www.youtube.com/embed/' + match[1] + '?autoplay=1' : '';
    }
  };
};

// AOS
import AOS from 'aos';
import 'aos/dist/aos.css';
AOS.init({ duration: 800, once: true, offset: 50 });

// Bootstrap Icons
import 'bootstrap-icons/font/bootstrap-icons.css';

console.log('Vite bundle loaded');