jQuery(document).ready(function($){
	
	$('.widget_pts_testimonial ul.pts-default-cycle').cycle();

	$('.pts_view_masonry').masonry({
		itemSelector: '.pts_masonry_item',
		gutter: 40
	});
	$('.pts_view_boxed').masonry({
		itemSelector: '.pts_boxed_item',
		gutter: 40
	});
	
	
	$('.pts_portrait_item').hover(function(){
		$(this).mousemove(event, function(){
			$(this).find('div.pts_image_details').css('top', event.pageY).css('left', event.pageX).show();
		});
	}, function(){
		$('div.pts_image_details').hide();
	});

	pts_call_iframe_post_add($('#pts_front_add_testimonial'));	
	$('#pts_front_popup_add_testimonial_link').click(function(){
		$.post(ajaxurl, {action: 'pts_get_add_form'}, function(res){
			$('.pts_front_add_popup').remove();
			$.ptsFormAddDialogPopup({width: 500, height: 430}).find('div.pts_front_add_popup_wrapper').append(res);
			pts_call_iframe_post_add($('#pts_front_popup_add_testimonial'));
		});
		return false;
	});
	$('body').on('click', '.pts_front_add_popup_close', function() {
		$('.pts_front_add_popup').fadeOut(500, function(){
			$(this).remove();	
		});
		return false;
	});
	$('.pts_view_carousel').simpleRotatorDiv({
		itemContainer: '.pts_carousel_item',
		itemActiveContainer: '.pts_item_active',
		navigationContainer: '.pts_carousel_navigation',
		navigationItemContainer: '.pts_carousel_item_navigation',
		navigationItemActiveContainer: '.pts_carousel_item_navigation_active',
		speed: pts_config_carousel_speed,
		transition: pts_config_carousel_fade_speed,
		autoplay: true
	});
	function pts_call_iframe_post_add(form)
	{
		form.iframePostForm
		({
			json : true,
			post : function ()
			{
				
			},
			complete : function (response)
			{
				console.log(response);
				
				if (response.length === 0)
				{
					// success
					form.parent('div').find('div.pts_front_add_testimonial_success').fadeIn();
					form.parent('div').find('div.pts_front_popup_add_testimonial_success').fadeIn();
					form.remove();
				}
				else
				{
					form.find('input.pts-it-error, textarea.pts-it-error').removeClass('pts-it-error');
					form.find('label.pts-label-error').removeClass('pts-label-error');
					$.each(response, function(i,e){
						$('input[name=' + i + '], textarea[name=' + i + ']', form).addClass('pts-it-error');
						$('input[name=' + i + '], textarea[name=' + i + ']', form).closest('li').find('label').addClass('pts-label-error');
					});
					
				}
			}
		});
	}
});

(function($) {
    var pts_dialogHTML = '<div class="pts_front_add_popup"><div class="pts_front_add_popup_close"><img src="' + pts_testimonials_assets_img + '/close.png" alt="X"/></div><div class="pts_front_add_popup_wrapper"></div></div>';
 
    $.ptsFormAddDialogPopup = function(opts) {
        var dialog = $(pts_dialogHTML);
        dialog.appendTo('body');
        
        dialog.css({
            position: 'absolute', 
            'z-index': Math.pow(2,32) 
        });
        
        // Position the dialog on the screen
        var horizOffset = ($(window).width() - opts.width || dialog.outerWidht()) / 2;
        var vertOffset = ($(window).height() - opts.height || dialog.outerHeight()) / 2;
        dialog.css({
            left: horizOffset,
            right: horizOffset,
            top: vertOffset,
            bottom: vertOffset
        });
        return dialog;            
    };      
    
}(jQuery));