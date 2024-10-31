<?php
defined('PREMIUM_TESTIMONIALS_SCRIPT_VERSION') or exit;

function pts_form_add_testimonial()
{
	?>
	<form enctype="multipart/form-data" id="pts_front_add_testimonial" method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">
		<input type="hidden" name="action" value="pts_front_add_testimonial"/>
		<ul>
			<?php if (get_option('pts_allow_name') == 1) : ?>
			<li><label for="pts_name">Name:</label>
				<input type="text" id="pts_name" class="pts-it" name="pts_name" value=""/></li>
			<?php endif; ?>
			
			<?php if (get_option('pts_allow_email') == 1) : ?>
			<li><label for="pts_email">Email:</label>
				<input type="text" id="pts_email" class="pts-it" name="pts_email" value=""/></li>
			<?php endif; ?>
			
			<?php if (get_option('pts_allow_website') == 1) : ?>	
			<li><label for="pts_website">Website:</label>
				<input type="text" id="pts_website" class="pts-it" name="pts_website" value=""/></li>
			<?php endif; ?>
			
			<?php if (get_option('pts_allow_photo') == 1) : ?>	
			<li><label for="pts_photo">Photo:</label>
				<input type="file" id="pts_photo" class="pts-it" name="pts_photo" value=""/></li>
			<?php endif; ?>
				
			<li><label for="pts_content">Testimonial:</label>
				<textarea id="pts_content" name="pts_content"></textarea></li>
				
			<li><input type="submit" value="Submit" id="pts_form_add_testimonial_submit"/></li>
		</ul>
	</form>
	<div class="pts_front_add_testimonial_success"><p><?php echo get_option('pts_testimonial_success_form'); ?></p></div>
	<?php
}

function pts_form_add_testimonial_popup()
{
	?>
	<h3 class="pts_front_popup_add_testimonial_title"><?php echo get_option('pts_popup_add_title'); ?></h3>
	<form enctype="multipart/form-data" id="pts_front_popup_add_testimonial" method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">
		<input type="hidden" name="action" value="pts_front_add_testimonial"/>
		<ul>
			<?php if (get_option('pts_allow_name') == 1) : ?>
			<li><label for="pts_name">Name:</label>
				<input type="text" id="pts_name" class="pts-it" name="pts_name" value=""/></li>
			<?php endif; ?>
			
			<?php if (get_option('pts_allow_email') == 1) : ?>
			<li><label for="pts_email">Email:</label>
				<input type="text" id="pts_email" class="pts-it" name="pts_email" value=""/></li>
			<?php endif; ?>
			
			<?php if (get_option('pts_allow_website') == 1) : ?>	
			<li><label for="pts_website">Website:</label>
				<input type="text" id="pts_website" class="pts-it" name="pts_website" value=""/></li>
			<?php endif; ?>
			
			<?php if (get_option('pts_allow_photo') == 1) : ?>	
			<li><label for="pts_photo">Photo:</label>
				<input type="file" id="pts_photo" class="pts-it" name="pts_photo" value=""/></li>
			<?php endif; ?>
				
			<li><label for="pts_content">Testimonial:</label>
				<textarea id="pts_content" name="pts_content"></textarea></li>
				
			<li><input type="submit" value="Submit" id="pts_form_add_testimonial_submit"/></li>
		</ul>
	</form>
	<div class="pts_front_popup_add_testimonial_success"><p><?php echo get_option('pts_testimonial_success_form'); ?></p></div>
	<?php
	exit;
}

function pts_front_add_testimonial()
{
	global $wpdb;
	$insertData = $_POST;
	unset($insertData['action']);
	
	$res = array();

	if (empty($insertData['pts_name']) && get_option('pts_allow_name') == 1)
	{
		$res['pts_name'] = '';
	}
	if (empty($insertData['pts_email']) && get_option('pts_allow_email') == 1)
	{
		$res['pts_email'] = '';
	}
	if (empty($insertData['pts_website']) && get_option('pts_allow_website') == 1)
	{
		$res['pts_website'] = '';
	}
	if (empty($insertData['pts_content']))
	{
		$res['pts_content'] = '';
	}
	if (empty($res))
	{
		if (!empty($_FILES['pts_photo']['tmp_name']))
		{
			$_ext = end(explode('.', $_FILES['pts_photo']['name']));
			if (strtolower($_ext) == 'jpg' || strtolower($_ext) == 'jpeg')
			{
				$upload_dir = wp_upload_dir();
				$pts_photo = time() . uniqid() . '_pts.' . $_ext;
				move_uploaded_file($_FILES['pts_photo']['tmp_name'], $upload_dir['path'] . '/' . $pts_photo);
				
				$insertData['pts_photo'] = $upload_dir['subdir'] . '/' . $pts_photo;			
			}
		}
		$wpdb->insert(PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME, $insertData);
	}
	echo json_encode($res);
	exit;
}

