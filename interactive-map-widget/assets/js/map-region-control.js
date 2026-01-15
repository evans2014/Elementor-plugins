(function($) {
    var WidgetRegionSelectorHandler = function($scope, $) {
        	console.log("JO element_ready for nahiro_map_widget triggered");
		
        // Add a timeout to delay execution, ensuring that the DOM is fully updated
      //  setTimeout(function() {
            console.log("WidgetRegionSelectorHandler triggered with delay");

            var mapCont = $scope.find(".map-contenedor")[0]; // Scoped to this widget instance
            console.log("mapCont:", mapCont); // Check if mapCont is found

            if (mapCont) {
                console.log("mapCont found:", mapCont); // Confirm element is found

                var arrPath = mapCont.querySelectorAll("svg path");
                var map = mapCont.querySelector("svg");
                var divSelector = document.querySelector('#regionSelector');
                var selectInput = divSelector.querySelector('select');
                var hcord = divSelector.querySelector('.h-cord');
                var vcord = divSelector.querySelector('.v-cord');

                // Handle click event on the map
                map.addEventListener("click", function(e) {
                    const mapRect = map.getBoundingClientRect();
                    const relativeX = e.clientX - mapRect.left;
                    const relativeY = e.clientY - mapRect.top;
                    const normalizedX = relativeX / mapRect.width;
                    const normalizedY = relativeY / mapRect.height;
                    const scaledX = normalizedX * 570;
                    const scaledY = normalizedY * 810;
                    hcord.textContent = Math.round(scaledX);
                    vcord.textContent = Math.round(scaledY);
                });

                // Populate select options from path titles
                arrPath.forEach((path) => {
                    var pathTitle = path.getAttribute('title');
                    var opt = document.createElement('option');
                    opt.value = pathTitle;
                    opt.text = pathTitle;
                    selectInput.add(opt);
                });

                function getCoordinatesOfPath(path) {
                    const bbox = path.getBBox();
                    const centerX = bbox.x + bbox.width / 2;
                    const centerY = bbox.y + bbox.height / 2;
                    hcord.textContent = Math.round(centerX) - 20;
                    vcord.textContent = Math.round(centerY);
                }

                function showMapRegion(e) {
                    const isInitialLoad = !e;
                    const storedTitle = localStorage.getItem('selectedMapRegion');
                    const title = isInitialLoad ? (storedTitle || selectInput.value) : e.target.value;

                    if (!isInitialLoad) {
                        localStorage.setItem('selectedMapRegion', title);
                    }

                    if (isInitialLoad && storedTitle) {
                        selectInput.value = storedTitle;
                    }

                    const pathRegion = document.querySelector(`.map-contenedor svg path[title="${title}"]`);

                    if (pathRegion) {
                        mapCont.querySelectorAll('svg path').forEach((pathx) => {
                            pathx.style.cssText = 'filter: none';
                        });
                        pathRegion.style.cssText = 'filter: brightness(0.5)';
                        getCoordinatesOfPath(pathRegion);
                    }
                }

                selectInput.addEventListener('change', showMapRegion);
                showMapRegion();

            } else {
                console.log("mapCont not found.");
            }
       // }, 300); // Adjust the delay (300ms) as needed for your case
    };
	
	/*
	* function to adjust the position of the region selector by dragging
	*/
	var WidgetRegionSelectorDrag = function($scope, $){
		var regionSelectorD = document.getElementById("regionSelector");
		
		const storedPos = localStorage.getItem('nhRegionSelectorPosY');
		if(storedPos != null){
			regionSelectorD.style.top = storedPos + "px";
		}
		// Make the DIV element draggable:
		dragElement(regionSelectorD);

		function dragElement(elmnt) {
		  var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0, currentY=0;			

		  if (document.getElementById(elmnt.id + "Move")) {
			// if present, the header is where you move the DIV from:
			document.getElementById(elmnt.id + "Move").onmousedown = dragMouseDown;
		  } else {
			// otherwise, move the DIV from anywhere inside the DIV:
			elmnt.onmousedown = dragMouseDown;
		  }

		  function dragMouseDown(e) {
			e = e; //|| window.event;
			e.preventDefault();
			  console.log('offsetTop',elmnt.offsetTop,'event',e);
			// get the mouse cursor position at startup:
			pos3 = e.clientX;
			pos4 = e.clientY;


			document.onmouseup = closeDragElement;
			// call a function whenever the cursor moves:
			document.onmousemove = elementDrag;
		  }

		  function elementDrag(e) {
			e = e; //|| window.event;
			e.preventDefault();
			// calculate the new cursor position:
			pos1 = pos3 - e.clientX;
			pos2 = pos4 - e.clientY;
			  /*
			  console.log('pos4',pos4,'clientY',e.clientY,'pos2',pos2, 'offsetTop',elmnt.offsetTop, 'e.screenY', e.screenY, 'PageY', e.pageY, 'calc', elmnt.offsetTop - pos2, 'currentY', currentY);
			  */
			pos3 = e.clientX;
			pos4 = e.clientY;

			  // set the element's new position:
			elmnt.style.top = (elmnt.offsetTop - pos2) + "px";

			  elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";

			  //declare element position
			  currentY = elmnt.offsetTop - pos2;
			  localStorage.setItem('nhRegionSelectorPosY', currentY);
		  }

		  function closeDragElement() {
			// stop moving when mouse button is released:
			document.onmouseup = null;
			document.onmousemove = null;
		  }
		}
	}

       // Ensure the widget's JS logic runs on every render (including re-renders)
    $(window).on('elementor/frontend/init', function() {
		console.log("JO Elementor frontend initialized");
      
        elementorFrontend.hooks.addAction('frontend/element_ready/nahiro_map_widget.default', WidgetRegionSelectorHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/nahiro_map_widget.default', WidgetRegionSelectorDrag);

    });
})(jQuery);
