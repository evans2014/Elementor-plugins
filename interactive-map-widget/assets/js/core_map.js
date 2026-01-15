(function($) {
    function hideDiv(buttonElement, widgetid) {
        var pinesClicks = window['pinesClicks_' + widgetid];
        let attrid = jQuery(buttonElement.parent()).attr('attrid')
        let idEnd = jQuery(buttonElement.parent()).attr('id')[jQuery(buttonElement.parent()).attr('id').length - 1]
        let id = jQuery(buttonElement.parent()).attr('id')
        let clicksNow = pinesClicks[idEnd + '_' + attrid].clicks
        clicksNow = clicksNow.filter(item => item !== '#' + id);

        var parentDiv = buttonElement.parent();
        parentDiv.css({
            "display": "none"
        });
        pinesClicks[idEnd + '_' + attrid].clicks = clicksNow
        if (clicksNow.length == 0) {
            const valor = pinesClicks[idEnd + '_' + attrid];
            let originalSrc = valor.url
            let originalSize = valor.size
            jQuery('img[id="' + idEnd + '_' + attrid + '"]').attr('src', originalSrc);
            jQuery('img[id="' + idEnd + '_' + attrid + '"]').css({
                width: originalSize.width,
            });
            reboteInfinito(jQuery('img[id="' + idEnd + '_' + attrid + '"]'))

            delete pinesClicks[idEnd + '_' + attrid];
            window['pinesClicks_' + widgetid] = pinesClicks
        }
    }
    //delete this function later
    function reboteInfinito(elemento, rebotIni = false) {
        // 2 may: jo: impide ejecucion de funcion rebote infinito sin interrumpir el flujo de codigo normal
        return;

    }

    function esVisible(elemento) {
        var rect = elemento.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)
        );
    }

    function esDispositivoMovil() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    function core_map_function() {
        var intervalWidgetMap = setInterval(() => {

            // Inicializar los widgets existentes al cargar la página
            $('.custom_map_wrap').each(function() {
                clearInterval(intervalWidgetMap);
                var widgetId = $(this).attr('id');
                var option_card = $(this).attr('optioncard');
                var percent = $(this).attr('percent');
                var circle_coordinates = $(this).attr('circlecoordinates');
                var svg = document.getElementById('mapaalemaniab_' + widgetId);
                var paths = svg.querySelectorAll("path");
                var maxWidth = 0;
                var maxHeight = 0;

                paths.forEach(function(path) {
                    var bbox = path.getBBox();
                    var pathWidth = bbox.x + bbox.width;
                    var pathHeight = bbox.y + bbox.height;

                    maxWidth = Math.max(maxWidth, pathWidth);
                    maxWidth = maxWidth * percent

                    maxHeight = Math.max(maxHeight, pathHeight);
                    maxHeight = maxHeight * percent
                });

                svg.setAttribute("viewBox", "0 0 " + maxWidth + " " + maxHeight);
                //jo 01/06 added widgetId + 'c'
                var elementorIds = [widgetId + 'a', widgetId + 'b'];
                //var pinesClicks = {};
                //var mousesFocus = [];
                var dynamicVarName = 'pinesClicks_' + widgetId;
                window[dynamicVarName] = {};
                dynamicVarName = 'mousesFocus_' + widgetId;
                window[dynamicVarName] = [];

                var dynamicMethodName = 'gestionarScroll_' + widgetId;
                window[dynamicMethodName] = function() {
                    //let items = jQuery('[id^="card_"]');
                    if (option_card == 'opcionB') {
                        let items = jQuery('div[attrid="' + widgetId + '"]');
                        let algunoVisible = true;
                        for (let i = 0; i < items.length; i++) {
                            if (!esVisible(jQuery(items[i])[0])) {
                                algunoVisible = false;
                                break;
                            } else {}
                        }

                        if (!algunoVisible) {
                            const claves = Object.keys(window['pinesClicks_' + widgetId]);

                            claves.forEach(clave => {
                                const valor = window['pinesClicks_' + widgetId][clave];
                                let originalSrc = valor.url
                                let originalSize = valor.size
                                jQuery('img[id="' + clave + '"]').attr('src', originalSrc);
                                jQuery('img[id="' + clave + '"]').css({
                                    width: originalSize.width,
                                    height: originalSize.height //jo: 3 may descomentado para que coincida con el codigo de la dev
                                });
                                reboteInfinito(jQuery('img[id="' + clave + '"]'))
                            });
                            jQuery('[id^="card_"]').hide();
                            window['pinesClicks_' + widgetId] = [];
                        }
                    }
                };

                var indi = 0;
                dynamicVarName = 'pinesPorRegionP_' + widgetId;
                var circles = JSON.parse(circle_coordinates);

                let output = '';
                let index = 1;

                let arreglo_coords = [];
                circles.forEach(coordinate => {
                    let arreglo_people = [];
                    let index2 = 1;
                    coordinate.people.forEach(person => {
                        arreglo_people.push({
                            'id': index2,
                            'img': person.person_image.url,
                            'name': person.person_name,
                            'position': person.person_position,
                            'phone': person.person_phone,
                            'url': person.person_url,
                            'email': person.person_email
                        });

                        index2++;
                    });
					
                    var iter_ind = index + "_" + widgetId;
                    arreglo_coords.push({
                        "iter_ind": iter_ind,
                        "pines": arreglo_people,
                        "top": coordinate.top,
                        "left": coordinate.left,
                        "name": coordinate.circle_name,
                        "imgPin": coordinate.circle_image.url,
                        "id": index
                    });
                    index++;
                });

                window[dynamicVarName] = arreglo_coords;

                $('#mapaalemaniab_' + widgetId + ' circle').mouseenter(function(e) {
                    var position = $(this).offset();
                    var width = $(this).width();
                    var height = $(this).height();


                    var region = $(this).attr('id'); // Asegúrate de que cada `path` tiene un id correspondiente a una clave en `pinesPorRegion`
                    $('#' + region + 'x').css('fill', '#e5dfd9'); // Cambia el color a rojo al pasar el mouse

                    var pines = arreglo_coords.find(circle => circle.iter_ind === region);
                    if (!pines) return; // Si no hay pines para esta región, no hacemos nada


                    var popupnContent = '<img id="' + region + '" attrid="' + region + '" class="imgempn_' + widgetId + ' imgempnext"  src="' + pines.imgPin + '" alt="' + pines.name + '">';
                    var topPin = 0;
                    var fontW = "";
                    if (esDispositivoMovil()) {
                        topPin = pines.top - 15;
                        fontW = "14";
                        if (pines.name == "Mittel und Ostdeutschland") {
                            topPin = topPin - 20;
                        }
                    } else {
                        topPin = pines.top;
                        fontW = "18";
                    }


                    //JO: NUEVA CLASE jo-pin-text
                    //ELIMINADO: (font-size:"+fontW+"px) iba justo despues de color #2E2E2E y antes de font-weight:light en la seccion style del div
                    //2 may 2024,jo: eliminado colores estaticos de jo-pin-text  color:#837B72
                    //jo 6 may, agregado elementorids, arreglo que contiene los ids de cada repeater asignado por elementor
                    //jo 7 may, ajuste en base al windowComputedStyle
                    if (mapMargin == null || offsetMargin == null) {
                        var mapMargin = document.querySelector(".map-contenedor");
                        var offsetMargin = window.getComputedStyle(mapMargin).marginLeft; //out: ejm: '800px'
                        offsetMargin = offsetMargin.split("px"); //out ejm: ['800','px']
                        offsetMargin = Number(offsetMargin[0]); //out ejem: 800
                    }
                    //jo 01/06 se comento la linea de font-family:\"Lato\"
                    //jo 05 jul se creo la variable displayPin que recupera la funcionalidad de ocultar la forma del pin
                    var displayPin = document.getElementById('nahiro-hide-pin');
                    if (displayPin instanceof HTMLElement) {
                        displayPin = displayPin.value;
                    } else {
                        displayPin = '';
                    }
                    //fin jo 05 jul
                    popupnContent = "<div class='jo-animation reboteInfinito' style='display:flex; flex-direction:row'><div class='xpin " + displayPin + "'>" + popupnContent + "</div></div><div style='position:absolute; width:100%; text-align:center; font-weight:light; /*font-family:\"Lato\";*/ line-height:1; top:" + topPin + "px; left:" + pines.left + "px' class='jo-pin-text'>" + pines.name + "</div>";

                    //var newParentDiv = $('<div id="parent_"+uniqueId+"' + region +'" class="parentall" style="width:100%; height:100%">');
                    var popupClonado = $('#popupnb_' + widgetId + '').clone();
                    popupClonado.attr('id', 'popupClonb_' + widgetId + '' + region);
                    //jo 6 may
                    popupClonado.addClass('elementor-repeater-item-' + elementorIds[indi++]);


                    $('#popupnb_' + widgetId + '').parent().append(popupClonado);
                    //$('#popupnb_'+uniqueId+'').parent().append(newParentDiv);
                    //newParentDiv.append(popupClonado);

                    widthParent = e.target.getBoundingClientRect().width
                    $('#popupClonb_' + widgetId + '' + region).css({
                        'top': (position.top - $(this).parent().offset().top - 100) + 'px',
                        //jo 7 may +offset
                        'left': (position.left - $(this).parent().offset().left - widthParent - 34 + offsetMargin) + 'px'
                    });


                    $('#popupClonb_' + widgetId + '' + region).html(popupnContent);
                    $('#popupClonb_' + widgetId + '' + region).show();
                    // Ahora mostramos el popupn...
                });

                $(document).on('click', 'img.imgempn_' + widgetId + '', function(e1) {
                    //jo 01/06 stop event propagation, solve bug element executing click event twice when clicked once
                    e1.stopPropagation();
                    e1.stopImmediatePropagation();
                    //fin jo 01/06

                    var makeclick = false;
                    var attrid = $(this).attr('attrid');
                    var pinesClicks = window['pinesClicks_' + widgetId];
                    if (pinesClicks.hasOwnProperty(attrid)) {
                        makeclick = true;
                        originalSrc = pinesClicks[$(this).attr('attrid')].url
                        //delete pinesClicks[$(this).attr('attrid')];
                    } else {
                        pinesClicks[attrid] = {
                            "url": $(this).attr('src')
                        }
                    }

                    if (!makeclick) {
                        //if (!$(this).data('original-src')) {
                        originalSrc = $(this).attr('src');

                        originalSize = {
                            width: $(this).width(),
                            height: $(this).height()
                        };

                        pinesClicks[$(this).attr('attrid')].size = originalSize

                        $(this).data('original-src', $(this).attr('src'));
                        //        }

                        $(this).css({
                            //width: originalSize.width - 10,
                            //height: originalSize.height - 10
                            //jo
                            width: originalSize.width,
                            //height: originalSize.height + 5
                        });
                        //jo 3 may, si se comenta la siguiente linea, se elimina el efecto de imagen cuando se hace click
                        //    $(this).attr('src', 'https://somelink-removebg-preview.png');
                        $(this).stop(true, false);

                        //originalSrc = $(this).attr('src');


                        var imgid = $(this).attr('id');
                        var regid = $(this).attr('attrid');
                        var position2 = $(this).offset();

                        arreglo_coords = window["pinesPorRegionP_" + widgetId];
                        var pines = arreglo_coords.find(circle => circle.iter_ind === regid);
                        var enc = pines.pines.find(pin => pin.id === imgid);
                        pines.pines.map((pin, ipin) => {

                            if (pinesClicks.hasOwnProperty($(this).attr('attrid'))) {
                                let arrayClicks = pinesClicks[$(this).attr('attrid')].clicks
                                if (typeof arrayClicks === "undefined") {
                                    arrayClicks = []
                                }
                                arrayClicks.push("#card_" + widgetId + "" + pin.id + pines.id)
                                pinesClicks[$(this).attr('attrid')].clicks = arrayClicks
                            }

                            var cardClon = $('#cardn_' + widgetId + '').clone();

                            let newh = 0
                            if (window.innerWidth <= 768) {
                                let neww = (window.innerWidth * 250) / 1366 + 70
                                let newp = (neww * 100) / 250
                                newh = (newp / 100) * 100 + 20
                                $(cardClon).css({
                                    width: neww,
                                    height: newh,
                                    padding: 1,
                                });
                            }

                            cardClon.attr('id', 'card_' + widgetId + '' + pin.id + pines.id);
                            if (option_card != 'opcionD' && option_card != 'opcionE') {
                                $('#' + 'card_' + widgetId + '' + pin.id + pines.id + ' .close-btn').remove();
                            } else {

                            }

                            $('#cardn_' + widgetId + '').parent().append(cardClon);

                            $('.close-btn').click(function(event) {
                                hideDiv($(this), widgetId);
                            });


                            $("#card_" + widgetId + "" + pin.id + pines.id + " .imagecrd").css('background', 'url(' + pin.img + ') center/cover no-repeat');
                            if (window.innerWidth <= 768) {
                                $("#card_" + widgetId + "" + pin.id + pines.id + " .imagecrd").css('height', newh);

                            }
                            if (pin.url !== "") {
                                $("#card_" + widgetId + "" + pin.id + pines.id + " #aW_" + widgetId + "").attr('href', pin.url);
                            } else {
                                $("#card_" + widgetId + "" + pin.id + pines.id + " #aW_" + widgetId + "").click(function(event) {
                                    event.preventDefault();
                                });
                            }
                            $("#card_" + widgetId + "" + pin.id + pines.id + " #nameW_" + widgetId + "").text(pin.name);
                            if (window.innerWidth <= 768) {
                                $("#card_" + widgetId + "" + pin.id + pines.id + " #nameW_" + widgetId + "").css({
                                    //fontSize: 11,
                                });
                            }
                            $("#card_" + widgetId + "" + pin.id + pines.id + " #positionW_" + widgetId + "").text(pin.position);
                            if (window.innerWidth <= 768) {
                                $("#card_" + widgetId + "" + pin.id + pines.id + " #positionW_" + widgetId + "").css({
                                    //fontSize: 9,
                                });
                                $("#card_" + widgetId + "" + pin.id + pines.id + " #positionW_" + widgetId + "").parent().parent().css({
                                    paddingLeft: 5,
                                });
                                $("#card_" + widgetId + "" + pin.id + pines.id + " #positionW_" + widgetId + "").parent().parent().css({
                                    paddingTop: 5,
                                });
                            }
                            //$("#card_"+uniqueId+""+pin.id+pines.id+" #celW_"+uniqueId+"").text(pin.phone);
                            $("#card_" + widgetId + "" + pin.id + pines.id + " #celW_" + widgetId + "").empty();
                            $("#card_" + widgetId + "" + pin.id + pines.id + " #celW_" + widgetId + "").append('<a style="" href="tel:' + pin.phone + '">' + pin.phone + '</a>');
                            if (window.innerWidth <= 768) {
                                $("#card_" + widgetId + "" + pin.id + pines.id + " #celW_" + widgetId + "").css({
                                    marginTop: 8,
                                    // fontSize: 14,
                                });

                                $("#card_" + widgetId + "" + pin.id + pines.id + " #celW_" + widgetId + " a").css({
                                    //  fontSize: 14,
                                });
                            }
                            $("#card_" + widgetId + "" + pin.id + pines.id + " #emailW_" + widgetId + "").empty();
                            $("#card_" + widgetId + "" + pin.id + pines.id + " #emailW_" + widgetId + "").append('<a style="" href = "mailto: ' + pin.email + '">' + pin.email + '</a>');
                            if (window.innerWidth <= 768) {
                                $("#card_" + widgetId + "" + pin.id + pines.id + " #emailW_" + widgetId + "").css({
                                    marginTop: 1,
                                    // fontSize: 14,
                                });

                                $("#card_" + widgetId + "" + pin.id + pines.id + " #emailW_" + widgetId + " a").css({
                                    //fontSize: 9,
                                });
                            }
                            //.text(pin.email);
                            if (window.innerWidth <= 768) {
                                $("#card_" + widgetId + "" + pin.id + pines.id).css({
                                    'top': ($(this).parent().parent().parent()[0].offsetTop - $(this).parent().parent().parent()[0].offsetHeight - (ipin * 80) + 15) + 'px',
                                    'left': (e1.pageX - $(this).parent().parent().parent().offset().left - ($(this).parent().parent().parent()[0].offsetWidth / 2) - 20) + 'px'
                                });
                            } else {
                                $("#card_" + widgetId + "" + pin.id + pines.id).css({
                                    'top': ($(this).parent().parent().parent()[0].offsetTop - $(this).parent().parent().parent()[0].offsetHeight - (ipin * 105) - 40) + 'px',
                                    'left': (e1.pageX - $(this).parent().parent().parent().parent().offset().left - ($(this).parent().parent().parent()[0].offsetWidth / 2) - 83) + 'px'
                                });
                            }
                            $("#card_" + widgetId + "" + pin.id + pines.id).show();

                        });
                    } else {
                        let arrayClicks = pinesClicks[$(this).attr('attrid')].clicks

                        if (option_card == 'opcionA' || option_card == 'opcionE') {
                            arrayClicks.map((item, iitem) => {
                                $(item).hide();
                            })

                            $(this).attr('src', originalSrc);
                            $(this).css({
                                width: originalSize.width,
                                //height: originalSize.height
                                //height: originalSize.height + 5
                            });
                            reboteInfinito($(this))
                            delete pinesClicks[$(this).attr('attrid')];
                        }
                    }
                });

                $('#popupnb_' + widgetId + '').on('mouseover', function() {
                    $('#popupnb_' + widgetId + '').show();
                    if ($("#cardn_" + widgetId + "").is(":visible")) {
                        //$('#cardn').show();
                    }
                });

                $(document).on('mouseenter', 'div.cardnc', function(e1) {
                    let attrid = $(this).attr('attrid')
                    let id = $(this).attr('id')[$(this).attr('id').length - 1]
                    mousesFocus[id + '_' + attrid] = true
                })

                $(document).on('mouseleave', 'div.cardnc', function(e1) {
                    if (option_card == 'opcionC') {
                        let attrid = $(this).attr('attrid')
                        let id = $(this).attr('id')[$(this).attr('id').length - 1]
                        attrid = id + '_' + attrid
                        mousesFocus[attrid] = false

                        setTimeout(() => {
                            if (mousesFocus[attrid] == false) {
                                let itempin = pinesClicks[attrid]
                                for (let i = 0; i < itempin.clicks.length; i++) {
                                    $(itempin.clicks[i]).hide();
                                }
                                let originalSrc = itempin.url
                                let originalSize = itempin.size
                                jQuery('img[id="' + attrid + '"]').attr('src', originalSrc);
                                jQuery('img[id="' + attrid + '"]').css({
                                    width: originalSize.width,
                                    height: originalSize.height //jo 3 may descomentado para que coincida con la dev
                                });
                                pinesClicks = []
                                reboteInfinito(jQuery('img[id="' + attrid + '"]'))
                            }
                        }, "1000");
                    }
                })

                $(document).on('mouseenter', 'img.imgempn_' + widgetId + '', function(e1) {
                    let attrid = $(this).attr('attrid')
                    mousesFocus[attrid] = true;
                    $(this).addClass('mouse-hover-map-maehren');
                })

                $(document).on('mouseleave', 'img.imgempn_' + widgetId + '', function(e1) {
                    if (option_card == 'opcionC') {
                        let attrid = $(this).attr('attrid')
                        mousesFocus[attrid] = false
                        setTimeout(() => {
                            if (mousesFocus[attrid] == false) {
                                let itempin = pinesClicks[attrid]
                                for (let i = 0; i < itempin.clicks.length; i++) {
                                    $(itempin.clicks[i]).hide();
                                }
                                let originalSrc = itempin.url
                                let originalSize = itempin.size
                                jQuery('img[id="' + attrid + '"]').attr('src', originalSrc);
                                jQuery('img[id="' + attrid + '"]').css({
                                    width: originalSize.width,
                                    height: originalSize.height //jo 3 may descomentado para que coincida con la dev
                                });
                                pinesClicks = []
                                reboteInfinito(jQuery('img[id="' + attrid + '"]'))
                            }
                        }, "1000");
                    }
                })

                $(document).on('mouseleave2', 'img.imgempn_' + widgetId + '', function(e1) {
                    $('div[attrid="' + $(this).attr('attrid') + '"]').hide();
                    if (originalSize !== undefined) {
                        $(this).css({
                            width: originalSize.width,
                            height: originalSize.height //jo 3 may descomentado para que coincida con la dev
                        });
                        $(this).attr('src', $(this).data('original-src'));
                        reboteInfinito($(this));
                    }
                })

                $('#mapaalemaniab_' + widgetId + ' circle').trigger('mouseenter');

                jQuery('img.imgempn_' + widgetId + '').each(function() {
                    reboteInfinito($(this));
                });

                const divElements = document.querySelectorAll('.elementor-element.elementor-element-edit-mode.elementor-element-8748de8 e-con.e-flex.e-con-full.e-con--row.elementor-hidden-tablet.elementor-hidden-mobile.ui-resizable');

                // Agregar un evento de clic a cada elemento
                divElements.forEach(div => {
                    div.addEventListener('click', function(event) {});
                });

                jQuery(window).scroll(window["gestionarScroll_" + widgetId]);

                // Llama a la función inicialmente para verificar el estado inicial
                window["gestionarScroll_" + widgetId]();

            });
        }, "100");
    }
    //});


    $(window).on('elementor/frontend/init', function() {
        var nahiroMapWidgetEditorHandler = function($scope, $) {
            core_map_function();
        };

        elementorFrontend.hooks.addAction('frontend/element_ready/nahiro_map_widget.default', nahiroMapWidgetEditorHandler);
		if (elementorFrontend.isEditMode()) {
			
			 // Debounce function to limit the rate at which a function can fire
            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }
			
			 // Debounced version of core_map_function
            const debouncedCore_map_function = debounce(core_map_function, 150);

			
			// Use MutationObserver to detect changes in the Elementor editor
            var targetNode = document.querySelector('body');
            var config = { attributes: true, attributeFilter: ['class'] };
			
            var callback = function(mutationsList, observer) {
                for(var mutation of mutationsList) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                      debouncedCore_map_function();
                    }
                }
            };

            var observer = new MutationObserver(callback);
            observer.observe(targetNode, config);
		}
		
    });
    core_map_function();
})(jQuery);