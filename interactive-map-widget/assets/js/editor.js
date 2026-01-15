(function($) {
    $(window).on('elementor/frontend/init', function() {

		if (typeof elementorFrontend !== 'undefined') {
            //console.log('Is Edit Mode:', elementorFrontend.isEditMode());
        }
		//check if we are in elementor editor
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.isEditMode()) {
			
			//hook that executes when the panel of our widget is opened
			elementor.hooks.addAction( 'panel/open_editor/widget/nahiro_map_widget', function( panel, model, view ) {
				
				//the section dropdown called Pin Contents
				var pinContents = window.parent.document.querySelector('.elementor-control-section_content');
				
				//when clicked get the repeaters elements inside it
				pinContents.addEventListener('click', function(){
					var repeaters = window.parent.document.querySelectorAll(".elementor-control-circle_coordinates > div > div > .elementor-repeater-fields");
					//for each repeater (two in this version), get the nested repeaters
					repeaters.forEach((repeaterField)=>{
						repeaterField.addEventListener('click',function(e){
							var peopleRepeaters = this.querySelectorAll('.elementor-control-people.elementor-control-type-repeater .elementor-repeater-fields');
							//for each nested repeater add the class 'editable' (that displays the controls) only if isn't already active
							peopleRepeaters.forEach((people)=>{
								let controls = people.querySelector('.elementor-repeater-row-controls');
								if( controls.classList.contains('editable')) {
									//do nothing
								}else{
									controls.classList.add('editable');
								}
							});
						});
					});
				});
			
			});
			
        }
    });
})(jQuery);
