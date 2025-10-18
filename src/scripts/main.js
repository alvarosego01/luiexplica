console.log('main script loaded');

if (!document.body.classList.contains('fusion-builder-live')) {
  document.addEventListener('DOMContentLoaded', () => {
    try {
      const containers = document.querySelectorAll('.image-info-content.invert-mobile');
      if (!containers.length) return;

      containers.forEach((container) => {
        // Buscar elementos objetivo dentro del contenedor
        const imageContent = container.querySelector('.image-content');
        const contentTitle = container.querySelector('.content-title');

        if (!imageContent || !contentTitle) return; // Falla silenciosa si falta algo

        // Evitar clonar múltiple veces: buscar un clon previo marcado
        const alreadyCloned = container.querySelector('.image-content.hide-desktop.cloned-from-image-content');
        if (alreadyCloned) return;

        // Clonar profundamente el bloque de imagen
        const clone = imageContent.cloneNode(true);
        clone.classList.add('hide-desktop', 'cloned-from-image-content');

        // Insertar el clon justo después del título dentro del mismo contenedor
        contentTitle.insertAdjacentElement('afterend', clone);
      });
    } catch (err) {
      // Log no invasivo para depuración, sin romper la página
      console.warn('invert-mobile clone routine failed:', err);
    }
  });
}