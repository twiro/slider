(function($) {

	$(document).ready(function() {
		
		var slider = [];

		$('div.field-slider').each(function(i){
	
			var input   = $("input", this);
			var label   = $(".slider-field-label-value", this);
			var range   = input.data('range') == 1;
			var connect = (range) ? true : false;
			var min     = parseInt(input.data('min-range'));
			var max     = parseInt(input.data('max-range'));
			var step    = parseInt(input.data('increment-value'));
			var value   = input.val();
			var values  = input.val().split('-');
			
			// Calculate preselected 'End Value' for a slider in 'range mode'
			if(range && values.length == 1) {
				if (step > 1) {
					values.push(parseInt(value) + step);
				} else {
					values.push(parseInt(value) + 1);
				}
			}
			
			// Set initial values
			setSliderFieldValues(input, label, range, values);
			
			// Fetch Slider element
			var div = $("div[id^='noUi-slider-']", this);
			slider[i] = div[0];
			
			// Create Slider instance
			noUiSlider.create( slider[i], {
				start: values,
				step: step,
				connect: connect,
				range: {
				  'min': min,
				  'max': max
				},
				format: {
				  to: function ( value ) {
					return Math.round(value);
				  },
				  from: function ( value ) {
					return Math.round(value);
				  }
				}
			});
			
			// Event : Update Slider
			slider[i].noUiSlider.on('update', function( values, handle ) {
				setSliderFieldValues(input, label, range, values);
			});

			
		});
		
		
		// Submit values to input (for data storage) and label (for display) when updating the slider
		function setSliderFieldValues(input, label, range, values) {
			if(range) {
				input.val(values[0] + '-' + values[1]);
				label.html(values[0] + '-' + values[1]);
			} else {
				input.val(values[0]);
				label.html(values[0]);
			}
		}
		
	});

})(jQuery);