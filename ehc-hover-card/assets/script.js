document.addEventListener('DOMContentLoaded', function () {
   
    document.querySelectorAll('[class*="ehc-hover-card-wrapper-"] .ehc-card').forEach(card => {
        const normal = card.getAttribute('data-bg-normal') || 'rgba(0,34,26,0.8)';
        const hover = card.getAttribute('data-bg-hover') || 'rgba(0,20,15,0.9)';
        card.style.setProperty('--ehc-bg-normal', normal);
        card.style.setProperty('--ehc-bg-hover', hover);
    });

    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    const allCards = document.querySelectorAll('.ehc-card');

    if (isTouchDevice) {
       
        allCards.forEach(card => {
            card.addEventListener('click', function (e) {
                e.preventDefault();
                const link = card.getAttribute('data-link') || '#';
               
                allCards.forEach(c => {
                    if (c !== card) {
                        c.classList.remove('is-hovered');
                    }
                });

                const isCurrentlyOpen = card.classList.contains('is-hovered');

                if (!isCurrentlyOpen) {
                   
                    card.classList.add('is-hovered');
                } else {
                  
                    if (link && link !== '#') {
                        window.location.href = link;
                    }
                }
            });
        });
    } else {

        let isHoveringAny = false;

        allCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                isHoveringAny = true;
            });
            card.addEventListener('mouseleave', () => {        
                setTimeout(() => {
                    if (!isHoveringAny) {
                    }
                }, 100);
            });
        });
        
    }
});