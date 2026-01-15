var mousesFocus = [];

function esVisible(elemento) {
    var rect = elemento.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)
    );
}

jQuery(function($) {


    var targetLi;

    // Iterar sobre cada elemento <li>
    $("#menu-main-menu li").each(function() {
        // Si el texto del elemento <a> dentro de este <li> es "Esto es una prueba"

        let childA = $(this).children().first()
        if (childA.text().trim().indexOf('ESG: Gesellschaftliche') > -1) {
            if (childA.is('a')) {
                targetLi = $(this); // Guardamos la referencia al <li>
                return false;
            }
        }
    });

    // Si se encontró el <li>, ahora puedes hacer algo con él, por ejemplo:
    if (targetLi) {
        targetLi.css('line-height', '22px');
    }

    $(document).on('mouseleave1', "li[id^='menu-item-'] > a", function() {
        $(this).siblings("div").remove();
    });

    $(document).on('mouseenter', "li[id^='menu-item-'] > a", function() {
        $(this).siblings("div").remove();
        var liElement = $(this).parent();

        var sectionParent = liElement.closest("section");

        var sectionWidth = sectionParent.width();
        var aHeight = liElement.find("a").outerHeight();
        var aElement = $(this);

        var position = aElement.offset();


        var positionRelativeToParent = aElement.position();

        var leftSpace = aElement.offset().left - sectionParent.offset().left
        var newDiv = $("<div class='newDivC' style='line-height:22px; letter-spacing:0px;font-family: \"Lato\", Sans-serif; padding-top:6px; padding-right:40px ;padding-left: " + (leftSpace) + "px ; background-color:#514D49; z-index:11; position:relative; left:-" + (leftSpace) + "px; top:-32px; color:#fff; font-weight: 300; font-size:18px; width:" + (sectionWidth + 30) + "px; height:" + (aHeight + 2) + "px'></div>");

        // Agrega el nuevo <div> al <li> seleccionado.
        liElement.css('height', liElement.outerHeight() + "px");

        $(".newDivC").remove()
        let newA = $(this).clone()
        liElement.append(newDiv);
        newDiv.append(newA)
        if (newA.text().trim().indexOf('ESG: Gesellschaftliche') > -1) {
            if (newA.is('a')) {
                newDiv.css('padding-top', "5px");

                var number = parseInt(newDiv.css('height'), 10);
                var resultNumber = number - 4;
                var resultString = resultNumber + "px";
                newDiv.css('height', resultString);
            }
        }

        newDiv.mouseout(function() {
            $(this).remove();
        });

        let totalHeight = 0;
        newDiv.prevAll().each(function() {
            totalHeight += $(this).outerHeight(); // Utiliza outerHeight() para incluir el padding y border en el cálculo
        });

        if (newA.text().trim().indexOf('ESG: Gesellschaftliche') > -1) {
            if (newA.is('a')) {
                newDiv.css('top', "-" + (totalHeight - 5) + "px");
            }
        } else {
            newDiv.css('top', "-" + (totalHeight) + "px");
        }
    });

    $(document).on('mouseenter', 'div.cardnc', function(e1) {
        let attrid = $(this).attr('attrid')
        let id = $(this).attr('id')[$(this).attr('id').length - 1]
        mousesFocus[id + '_' + attrid] = true
    })



    const divElements = document.querySelectorAll('.elementor-element.elementor-element-edit-mode.elementor-element-8748de8 e-con.e-flex.e-con-full.e-con--row.elementor-hidden-tablet.elementor-hidden-mobile.ui-resizable');


});