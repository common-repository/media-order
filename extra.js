jQuery(function(){
	jQuery('.column-menu_order input').blur(function(){
		var url = jQuery(this).parents('form').attr('action'),
			name = jQuery(this).attr('name'),
			val = jQuery(this).val(),
			values = {},
			target = jQuery(this);
		values[name] = val;
		jQuery.post(url, values, function(response){
			if(response.success) target.after('<span>success</span>')
				.next('span')
				.delay(1000)
				.slideUp(500, function(){
					jQuery(this).remove()
					});
		},'json');
	})
});