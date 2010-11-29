jQuery.noConflict();
jQuery(function(){
	var $ = jQuery;
	$("div.field-slider").each(function(){
		var range = $("var.range", this).text() == 1;
		var min   = parseInt($("var.min_range", this).text());
		var max   = parseInt($("var.max_range", this).text());
		var value = $("input", this).val();
		var inc   = parseInt($("var.increment_value", this).text());
		var input = $("input", this);
		var values = input.val().split('-');
		if(range && values.length == 1) {
			values.push(parseInt(value) + 1);
		}
		if(range)
		{
			input.val(values[0] + '-' + values[1]);
		} else {
			input.val(values[0]);
		}
		$("div.slider", this).slider({
			range: range,
			min: min,
			max: max,
			values: values,			
			step: inc,
			slide: function(e, ui){
				if(range)
				{
					input.val(ui.values[0] + '-' + ui.values[1]);	
				} else {
					input.val(ui.value);
				}
			}
		});
	});
});