(function($) {
            var wrapper = document.querySelector('.<?php echo esc_js($unique_class); ?>');
            if (!wrapper) return;

            var trigger = wrapper.querySelector('.xai-lightbox-trigger');
            if (!trigger) return;

            wrapper.addEventListener('click', function(e) {
                e.preventDefault();
                openPopup(trigger.href);
            });

            function openPopup(url) {
                var popup = document.createElement('div');
                popup.id = 'xai-lightbox-popup';
                popup.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.95);z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px;box-sizing:border-box;';

                var iframe = document.createElement('iframe');
                iframe.src = url;
                iframe.style.cssText = 'width:100%;height:100%;max-width:1200px;max-height:675px;border:none;border-radius:8px;';
                iframe.allowFullscreen = true;
                iframe.allow = 'autoplay; fullscreen';

                var closeBtn = document.createElement('div');
                closeBtn.innerHTML = '×';
                closeBtn.style.cssText = 'position:absolute;top:15px;right:20px;color:white;font-size:40px;font-weight:bold;cursor:pointer;z-index:1000;';
                closeBtn.onclick = function() {
                    document.body.removeChild(popup);
                };

                popup.appendChild(closeBtn);
                popup.appendChild(iframe);
                document.body.appendChild(popup);

                // ESC за затваряне
                document.addEventListener('keydown', function escHandler(e) {
                    if (e.key === 'Escape') {
                        if (document.getElementById('xai-lightbox-popup')) {
                            document.body.removeChild(popup);
                            document.removeEventListener('keydown', escHandler);
                        }
                    }
                });
            }
        })();