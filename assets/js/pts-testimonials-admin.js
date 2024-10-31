jQuery(document).ready(function($){
	$('.pts-testi-table').on('click', '.pts-testimonials-approve', function(){	
		var $container = $(this).closest('tr');
		var $testimonial_id = $container.attr('pts_id');
		
		$.post(ajaxurl, {testimonial_id: $testimonial_id, action: 'pts_approve_testiminial'}, function(res){
			$container.removeClass('pts-unapproved').fadeOut('500', function(){
				$container.addClass('pts-approved').fadeIn();

				$container.find('span.pts-status-approve').removeClass('pts-inactive').addClass('pts-active');
				$container.find('span.pts-status-unapprove').removeClass('pts-active').addClass('pts-inactive');
				
				$container.find('td.pts-testimonials-status-cell').html('Yes');
			});
		});
		return false;
	});
	$('.pts-testi-table').on('click', '.pts-testimonials-unapprove', function(){
		var $container = $(this).closest('tr');
		var $testimonial_id = $container.attr('pts_id');
		
		$.post(ajaxurl, {testimonial_id: $testimonial_id, action: 'pts_unapprove_testiminial'}, function(res){
			$container.removeClass('pts-approved').fadeOut('500', function(){
				$container.addClass('pts-unapproved').fadeIn();

				$container.find('span.pts-status-approve').removeClass('pts-active').addClass('pts-inactive');
				$container.find('span.pts-status-unapprove').removeClass('pts-inactive').addClass('pts-active');
				
				$container.find('td.pts-testimonials-status-cell').html('<span class="spam"><a>No</a></span>');
			});
		});
		return false;
	});
	$('.pts-testi-table').on('click', '.pts-testimonials-remove', function(){
		var $container = $(this).closest('tr');
		var $testimonial_id = $container.attr('pts_id');
		
		if (confirm('Are you sure that you want to delete this testimonial ?'))
		{
			$.post(ajaxurl, {testimonial_id: $testimonial_id, action: 'pts_delete_testiminial'}, function(res){
				$container.fadeOut();
				$('.pts-action-form-' + $testimonial_id).fadeOut().remove();
			});
		}
		
		return false;
	});
	$('.pts-testi-table').on('click', '.pts-inline-edit-button', function(){
		var $container = $(this).closest('tr');
		var $testimonial_id = $container.attr('pts_id');
		
		if ($('.pts-action-form-' + $testimonial_id).is(':hidden'))
		{
			$('.pts-action-form-' + $testimonial_id).fadeIn();
		}
		else
		{
			$('.pts-action-form-' + $testimonial_id).fadeOut();
		}
		
		return false;
	});
	$('.pts-testi-table').on('click', '.pts-testimonial-save', function(){
		var $container = $(this).closest('tr');
		var $testimonial_id = $container.attr('pts_id');
		
		$.post(ajaxurl, {testimonial_id: $testimonial_id, action: 'pts_edit_testimonial', name: $container.find('input[name=pts_name]').val(), email: $container.find('input[name=pts_email]').val(), website: $container.find('input[name=pts_website]').val(), content: $container.find('textarea[name=pts_content]').val()}, function(res){
			$container.fadeOut();
			
			$('.pts-action-list-' + $testimonial_id).fadeOut().fadeIn();
			
			$('.pts-action-list-' + $testimonial_id).find('td.pts-table-field-name').html(res.name);
			$('.pts-action-list-' + $testimonial_id).find('td.pts-table-field-email').html(res.email);
			$('.pts-action-list-' + $testimonial_id).find('td.pts-table-field-website').html(res.website);
			$('.pts-action-list-' + $testimonial_id).find('span.pts-table-field-content').html(res.content);
			

			pts_admin_notify_updated('This row has been successfully updated.');
		}, 'json');
		
		return false;
	});
	$('.pts-add-testimonial').click(function(){
		if ($('.pts-add-testimonial-form').is(':hidden'))
		{
			$('.pts-add-testimonial-form').fadeIn();
			$('.pts-add-testimonial-form form').reset();
		}
		else
		{
			$('.pts-add-testimonial-form').fadeOut();
		}
		
		return false;
	});
	$('.pts-action-submit-form-testimonial-add input[type=submit]').click(function(){
		var $form = $(this).closest('form');
		
		$.post(ajaxurl, $form.serialize(), function(res){
			pts_admin_notify_updated('This testimonial has been successfully added.');
			$form.closest('div').hide();

			$('.pts-no-entries').remove();
			$('.pts-testi-table tbody').prepend(res);
			$('.pts-testi-table tbody tr:first').fadeOut().fadeIn(2000);
		});
		
		return false;
	});
	$('.pts-testimonial-integration-save').click(function(){
		var $form = $(this).closest('form');
		
		$.post(ajaxurl, $form.serialize(), function(res){
			pts_admin_notify_updated('Changes has been saved.');
		});
		return false;
	});
});

function pts_admin_notify_updated(msg) {
	jQuery('.updated').find('p').html(msg).closest('div').fadeIn();
	
	setTimeout(function(){
		jQuery('.updated').fadeOut();
	}, 1500);
}