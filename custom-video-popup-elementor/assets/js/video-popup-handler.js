document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.cvp-video-thumb').forEach(function(container) {
    container.addEventListener('click', function(e) {
      e.preventDefault();

      const id = this.id.replace('cvp-', '');
      const widget = elementorFrontend.elementsHandler.getWidgetType('cvp_video_popup');

      // Вземи URL от настройките (ще го подадем по-долу)
      // Но за простота — използваме data атрибут
      const videoUrl = 'https://www.youtube.com/watch?v=iyZyl-rTGOc'; // ← ТУК ТРЯБВА ДА Е ДИНАМИЧЕН

      // Отваряне на lightbox
      if (window.elementorLightbox) {
        window.elementorLightbox.openSlideshow('cvp_' + id, {
          type: 'video',
          url: videoUrl,
          videoType: 'youtube'
        });
      }
    });
  });
});