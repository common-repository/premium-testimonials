<?php
function pts_testimonials_display( $atts )
{
	global $wpdb;
	
	$template = (string)"";
	extract( shortcode_atts( array('template' => 'basic'), $atts ) );
	
	$res = pts_get_testimonials(true);
	
	switch ($template)
	{
		case 'basic':
			pts_testimonials_display_view_basic($res);
		break;
		case 'portraits':
			pts_testimonials_display_view_portraits($res);
		break;
		case 'carousel':
			pts_testimonials_display_view_carousel($res);
		break;
		case 'masonry':
			pts_testimonials_display_view_masonry($res);
		break;
		case 'boxed':
			pts_testimonials_display_view_boxed($res);
		break;
	}
}

function pts_testimonials_display_view_basic($res = array())
{
	?>
	<div class="pts_view_basic">
		<?php foreach ($res as $row) : ?>
			<div class="pts_basic_item">
				<?php if (!empty($row->pts_photo) && get_option('pts_allow_photo') == 1) : ?>
				<div class="pts_basic_image">
					<div class="pts_image_round">
						<img src="<?php echo pts_aq_resize(PREMIUM_TESTIMONIALS_SCRIPT_URL_BASE_UPLOAD_DIR . $row->pts_photo, 150, 150); ?>" />
					</div>
				</div>
				<?php endif; ?>
				<blockquote class="pts_basic_content <?php if (empty($row->pts_photo) || get_option('pts_allow_photo') != 1) echo 'pts_full_width'; ?>">
					<p><?php echo stripslashes($row->pts_content); ?></p>
				</blockquote>
				<div class="pts_basic_by">
					<?php if (get_option('pts_allow_name') == 1) : ?>
						<p class="pts_di_name"><?php echo stripslashes($row->pts_name); ?></p> 
					<?php endif; ?>
					<?php if (get_option('pts_allow_website') == 1) : ?> 
						<p class="pts_di_website"><?php echo stripslashes($row->pts_website); ?></p>
					<?php endif; ?>
				</div>
			</div>		
		<?php endforeach; ?>
	</div>
	<?php
	
}

function pts_testimonials_display_view_carousel($res = array())
{
	?>
	<div class="pts_view_carousel">
		<?php foreach ($res as $key=>$row) : ?>
			<div class="pts_carousel_item">
				<?php if (!empty($row->pts_photo) && get_option('pts_allow_photo') == 1) : ?>
				<div class="pts_carousel_image">
					<div class="pts_image_round">
						<img src="<?php echo pts_aq_resize(PREMIUM_TESTIMONIALS_SCRIPT_URL_BASE_UPLOAD_DIR . $row->pts_photo, 150, 150); ?>" />
					</div>
				</div>
				<?php endif; ?>
				<blockquote class="pts_carousel_content <?php if (empty($row->pts_photo) || get_option('pts_allow_photo') != 1) echo 'pts_full_width'; ?>">
					<p><?php echo stripslashes($row->pts_content); ?></p>
				</blockquote>
				<div class="pts_carousel_by">
					<?php if (get_option('pts_allow_name') == 1) : ?>
						<p class="pts_di_name"><?php echo stripslashes($row->pts_name); ?></p> 
					<?php endif; ?>
					<?php if (get_option('pts_allow_website') == 1) : ?> 
						<p class="pts_di_website"><?php echo stripslashes($row->pts_website); ?></p>
					<?php endif; ?>
				</div>
			</div>		
		<?php endforeach; ?>
	</div>
	<div class="pts_carousel_navigation">
	</div>
	<?php
	
}

function pts_testimonials_display_view_masonry($res = array())
{
	$colors = unserialize(get_option('pts_testimonial_color_box'));
	?>
	<div class="pts_view_masonry">
		<?php foreach ($res as $row) : ?>
			<div class="pts_masonry_item">
				<blockquote class="pts_masonry_content <?php if (empty($row->pts_photo) || get_option('pts_allow_photo') != 1) echo 'pts_full_width'; ?>">
					<p><?php echo stripslashes($row->pts_content); ?></p>
				</blockquote>
				<div class="pts_masonry_image">
					<?php if (!empty($row->pts_photo) && get_option('pts_allow_photo') == 1) : ?>
						<div class="pts_image_round">
							<img src="<?php echo pts_aq_resize(PREMIUM_TESTIMONIALS_SCRIPT_URL_BASE_UPLOAD_DIR . $row->pts_photo, 80, 80); ?>" />
						</div>
					<?php endif; ?>
					<?php if (empty($row->pts_photo) || get_option('pts_allow_photo') != 1) : ?>
						<div class="pts_image_round" style="width: 80px; height: 80px; background: <?php echo $colors[array_rand($colors)]; ?>; overflow: hidden;">
						</div>
					<?php endif; ?>
					<div class="pts_image_details">
						<?php if (get_option('pts_allow_name') == 1) : ?>
							<p class="pts_di_name"><?php echo stripslashes($row->pts_name); ?></p> 
						<?php endif; ?>
						<?php if (get_option('pts_allow_website') == 1) : ?> 
							<p class="pts_di_website"><?php echo stripslashes($row->pts_website); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>		
		<?php endforeach; ?>
	</div>
	<?php
}

function pts_testimonials_display_view_portraits($res = array())
{	
	$colors = unserialize(get_option('pts_testimonial_color_box'));
	?>
	<div class="pts_view_portraits">
		<?php foreach ($res as $row) : ?>
			<div class="pts_portrait_item">
				<?php if (!empty($row->pts_photo) && get_option('pts_allow_photo') == 1) : ?>
				<div class="pts_portrait_image">
					<img src="<?php echo pts_aq_resize(PREMIUM_TESTIMONIALS_SCRIPT_URL_BASE_UPLOAD_DIR . $row->pts_photo, 100, 140); ?>" />
					<div class="pts_image_details">
						<p class="pts_di_content"><?php echo stripslashes($row->pts_content); ?></p>
						<?php if (get_option('pts_allow_name') == 1) : ?>
						<p class="pts_di_name"><?php echo stripslashes($row->pts_name); ?></p> 
						<?php endif; ?>
						<?php if (get_option('pts_allow_website') == 1) : ?> 
						<p class="pts_di_website"><?php echo stripslashes($row->pts_website); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
				<?php if (empty($row->pts_photo) || get_option('pts_allow_photo') != 1) : ?>
				<div class="pts_portrait_item">
					<div class="pts_portrait_image">
						<div style="background: <?php echo $colors[array_rand($colors)]; ?>;" class="pts_image_generated">
							<?php echo substr(stripslashes($row->pts_content), 0, 66); ?>
						</div>
						<div class="pts_image_details">
							<p class="pts_di_content"><?php echo stripslashes($row->pts_content); ?></p>
							<?php if (get_option('pts_allow_name') == 1) : ?>
								<p class="pts_di_name"><?php echo stripslashes($row->pts_name); ?></p> 
							<?php endif; ?>
							<?php if (get_option('pts_allow_website') == 1) : ?> 
								<p class="pts_di_website"><?php echo stripslashes($row->pts_website); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

function pts_testimonials_display_view_boxed($res = array())
{
	$colors = unserialize(get_option('pts_testimonial_color_box'));
	?>
	<div class="pts_view_boxed">
		<?php foreach ($res as $row) : ?>
			<div class="pts_boxed_item">
				<blockquote class="pts_boxed_content <?php if (empty($row->pts_photo) || get_option('pts_allow_photo') != 1) echo 'pts_full_width'; ?>">
					<p><?php echo stripslashes($row->pts_content); ?></p>
				</blockquote>
				<div class="pts_boxed_image">
					<?php if (!empty($row->pts_photo) && get_option('pts_allow_photo') == 1) : ?>
						<div class="pts_image_round">
							<img src="<?php echo pts_aq_resize(PREMIUM_TESTIMONIALS_SCRIPT_URL_BASE_UPLOAD_DIR . $row->pts_photo, 80, 80); ?>" />
						</div>
					<?php endif; ?>
					<?php if (empty($row->pts_photo) || get_option('pts_allow_photo') != 1) : ?>
						<div class="pts_image_round" style="width: 80px; height: 80px; background: <?php echo $colors[array_rand($colors)]; ?>; overflow: hidden;">
						</div>
					<?php endif; ?>
					<div class="pts_image_details">
						<?php if (get_option('pts_allow_name') == 1) : ?>
							<p class="pts_di_name"><?php echo stripslashes($row->pts_name); ?></p> 
						<?php endif; ?>
						<?php if (get_option('pts_allow_website') == 1) : ?> 
							<p class="pts_di_website"><?php echo stripslashes($row->pts_website); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>		
		<?php endforeach; ?>
	</div>
	<?php
}