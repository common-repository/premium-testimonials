<?php
defined('PREMIUM_TESTIMONIALS_SCRIPT_VERSION') or exit;

class Pts_Testimonials_Widget extends WP_Widget {

	function Pts_Testimonials_Widget() {
		// Instantiate the parent object
		$widget_ops = array('classname' => 'widget_pts_testimonial', 'description' => __('Your premium testimonials as display.') );
		 
		$this->WP_Widget('pts-testimonial', __('Premium testimonials'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $before_widget,$before_title,$after_title,$after_widget;
		extract($args);
		$args_output = array();
		
		
	    //  Get the title of the widget and the specified width of the image
	    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	    $width = empty($instance['width']) ? ' ' : $instance['width'];
	    $type = empty($instance['type']) ? false : $instance['type'];
	    $popup_add_testimonial = empty($instance['popup_add_testimonial']) ? false : $instance['popup_add_testimonial'];
	    $add_testimonial_link = empty($instance['add_testimonial_link']) ? false : $instance['add_testimonial_link'];
	    $show_title = empty($instance['show_title']) ? '2' : $instance['show_title'];
	    $submit_testimonial = empty($instance['submit_testimonial']) ? '2' : $instance['submit_testimonial'];
	    $add_testimonial_text = empty($instance['add_testimonial_text']) ? false : $instance['add_testimonial_text'];
	    
	    if ($type === false) return;
	    
	    $args_output['submit_testimonial'] = ($submit_testimonial == '1') ? true : false;
	    $args_output['add_testimonial_text'] = ($add_testimonial_text === false) ? __('Add Testimonial') : $add_testimonial_text;
	    
	    $args_output['popup_add_testimonial'] = $popup_add_testimonial;
	    $args_output['add_testimonial_link'] = $add_testimonial_link;
	    
	    echo $before_widget;
	    if (!empty( $title ) && strlen($title) > 1) { 
	        echo $before_title . $title . $after_title;
	    }

	    $this->widget_output($type, $args_output);
	    
	    echo $after_widget;
	}
	
	function widget_output($type = 0, $args_output = array())
	{
		switch ($type)
		{
			case '1': $this->widget_output_default($args_output); break;
			case '2': $this->widget_output_list($args_output); break;
		}
	}

	function widget_output_default($args_output)
	{
		$this->widget_output_submit_testimonial($args_output);
		?>
		<ul class="pts-default-cycle">
			<?php if (($testimonials = pts_get_testimonials()) == true && is_array($testimonials)) : ?>
				<?php foreach ($testimonials as $row) : ?>
				<li>
					<div class="pts-cycle-content">
						<?php echo stripslashes($row->pts_content); ?> 
					</div>
					<div class="pts-cycle-by">
						<?php if (!empty($row->pts_photo) && get_option('pts_allow_photo') == 1) : ?>
						<div class="pts_cycle_image">
							<div class="pts_image_round">
								<img src="<?php echo pts_aq_resize(PREMIUM_TESTIMONIALS_SCRIPT_URL_BASE_UPLOAD_DIR . $row->pts_photo, 45, 45); ?>" />
							</div>
						</div>
						<?php endif; ?>
						<?php if (get_option('pts_allow_name') == 1) : ?>
						<p class="pts_di_name"><?php echo stripslashes($row->pts_name); ?></p> 
						<?php endif; ?>
						<?php if (get_option('pts_allow_website') == 1) : ?> 
						<p class="pts_di_website"><?php echo stripslashes($row->pts_website); ?></p>
						<?php endif; ?>
					</div>
				</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
		<?php 
	}
	function widget_output_list($args_output)
	{
		$this->widget_output_submit_testimonial($args_output);
		?>
		<ul>
			<?php if (($testimonials = pts_get_testimonials()) == true && is_array($testimonials)) : ?>
				<?php foreach ($testimonials as $row) : ?>
				<li>
					<div class="pts-cycle-content">
						<?php echo stripslashes($row->pts_content); ?> 
					</div>
					<div class="pts-cycle-by">
						<?php if (!empty($row->pts_photo) && get_option('pts_allow_photo') == 1) : ?>
						<div class="pts_cycle_image">
							<div class="pts_image_round">
								<img src="<?php echo pts_aq_resize(PREMIUM_TESTIMONIALS_SCRIPT_URL_BASE_UPLOAD_DIR . $row->pts_photo, 45, 45); ?>" />
							</div>
						</div>
						<?php endif; ?>
						<?php if (get_option('pts_allow_name') == 1) : ?>
						<p class="pts_di_name"><?php echo stripslashes($row->pts_name); ?></p> 
						<?php endif; ?>
						<?php if (get_option('pts_allow_website') == 1) : ?> 
						<p class="pts_di_website"><?php echo stripslashes($row->pts_website); ?></p>
						<?php endif; ?>
					</div>
				</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
		<?php
	}
	
	function widget_output_submit_testimonial($args_output)
	{
		if ($args_output['submit_testimonial'] == 1)
		{
			if ($args_output['popup_add_testimonial'] == 1)
			{
				?>
				<a href="#" class="pts_widget_add_testimonial" id="pts_front_popup_add_testimonial_link"><?php echo $args_output['add_testimonial_text']; ?></a>		
				<?php
			} 
			else
			{
				?>
				<a href="<?php echo $args_output['add_testimonial_link']; ?>" class="pts_widget_add_testimonial"><?php echo $args_output['add_testimonial_text']; ?></a>		
				<?php
			}
		}	
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
	    $instance['title'] = strip_tags($new_instance['title']);
	    $instance['width'] = strip_tags($new_instance['width']);
	    $instance['type'] = strip_tags($new_instance['type']);
	    $instance['popup_add_testimonial'] = strip_tags($new_instance['popup_add_testimonial']);
	    $instance['add_testimonial_link'] = strip_tags($new_instance['add_testimonial_link']);
	    $instance['show_title'] = strip_tags($new_instance['show_title']);
	    $instance['submit_testimonial'] = strip_tags($new_instance['submit_testimonial']);
	    $instance['add_testimonial_text'] = strip_tags($new_instance['add_testimonial_text']);
	 
	    return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'width' => '', 'type' => '', 'popup_add_testimonial' => '', 'add_testimonial_link' => '', 'show_title' => '', 'submit_testimonial' => '', 'add_testimonial_text' => '' ) );
	    $title = strip_tags($instance['title']);
	    $width = strip_tags($instance['width']);
	   	$type = strip_tags($instance['type']);
	   	$popup_add_testimonial = strip_tags($instance['popup_add_testimonial']);
	   	$add_testimonial_link = strip_tags($instance['add_testimonial_link']);
	   	$show_title = strip_tags($instance['show_title']);
	   	$submit_testimonial = strip_tags($instance['submit_testimonial']);
	   	$add_testimonial_text = strip_tags($instance['add_testimonial_text']);
	   	
    	?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
        <p style="display: none;"><label for="<?php echo $this->get_field_id('width'); ?>"><?php echo __('Width'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr($width); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('add_testimonial_text'); ?>"><?php echo __('Hyperlink Text (eg: Add Testimonial)'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('add_testimonial_text'); ?>" name="<?php echo $this->get_field_name('add_testimonial_text'); ?>" type="text" value="<?php echo esc_attr($add_testimonial_text); ?>" /></label></p>
        <p>
        	<label for="<?php echo $this->get_field_id('popup_add_testimonial'); ?>"><?php echo __('Open form in Modal Popup'); ?>: 
		        <select class="widefat" id="<?php echo $this->get_field_id('popup_add_testimonial'); ?>" name="<?php echo $this->get_field_name('popup_add_testimonial'); ?>">
		        	<option value="1" <?php if (esc_attr($popup_add_testimonial) == '1') echo 'selected'; ?>>Yes</option>
		        	<option value="2" <?php if (esc_attr($popup_add_testimonial) == '2') echo 'selected'; ?>>No</option>
		        </select>
	        </label>
	    </p>
        <p><label for="<?php echo $this->get_field_id('add_testimonial_link'); ?>"><?php echo __('Link to submission form, if  Modal Popup not selected'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('add_testimonial_link'); ?>" name="<?php echo $this->get_field_name('add_testimonial_link'); ?>" type="text" value="<?php echo esc_attr($add_testimonial_link); ?>" /></label></p>
	    <p>
        	<label for="<?php echo $this->get_field_id('type'); ?>"><?php echo __('Template'); ?>: 
		        <select class="widefat" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
		        	<option value="1" <?php if (esc_attr($type) == '1') echo 'selected'; ?>>Default (Cycle)</option>
		        	<option value="2" <?php if (esc_attr($type) == '2') echo 'selected'; ?>>List</option>
		        </select>
	        </label>
	    </p>
	    <p style="display: none;">
        	<label for="<?php echo $this->get_field_id('show_title'); ?>"><?php echo __('Display widget title'); ?>: 
		        <select class="widefat" id="<?php echo $this->get_field_id('show_title'); ?>" name="<?php echo $this->get_field_name('show_title'); ?>">
		        	<option value="1" <?php if (esc_attr($show_title) == '1') echo 'selected'; ?>>Yes</option>
		        	<option value="2" <?php if (esc_attr($show_title) == '2') echo 'selected'; ?>>No</option>
		        </select>
	        </label>
	    </p>
	    <p>
        	<label for="<?php echo $this->get_field_id('submit_testimonial'); ?>"><?php echo __('Insert submission link in widget'); ?>: 
		        <select class="widefat" id="<?php echo $this->get_field_id('submit_testimonial'); ?>" name="<?php echo $this->get_field_name('submit_testimonial'); ?>">
		        	<option value="1" <?php if (esc_attr($submit_testimonial) == '1') echo 'selected'; ?>>Yes</option>
		        	<option value="2" <?php if (esc_attr($submit_testimonial) == '2') echo 'selected'; ?>>No</option>
		        </select>
	        </label>
	    </p>
      	<?php
	}
}

function pts_register_widgets() {
	register_widget( 'Pts_Testimonials_Widget' );
}

add_action( 'widgets_init', 'pts_register_widgets' );