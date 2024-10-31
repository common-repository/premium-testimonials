<?php
defined('PREMIUM_TESTIMONIALS_SCRIPT_VERSION') or exit;

function pts_get_testimonials($active = true)
{
	global $wpdb;
	if ($active === true) $active = '1'; else $active = '0';

	if (get_option('pts_allow_approved') == 1)
	{
		return $wpdb->get_results( "SELECT * FROM " . PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME . ' WHERE pts_active = ' . $active );
	}
	else
	{
		return $wpdb->get_results( "SELECT * FROM " . PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME);
	}
}

function pts_get_testimonials_all()
{
	global $wpdb;
	
	return $wpdb->get_results( "SELECT * FROM " . PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME . ' ORDER BY pts_active ASC' );
}

function pts_get_last_testimonial()
{
	global $wpdb;
	
	return $wpdb->get_results( "SELECT * FROM " . PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME . ' ORDER BY id DESC LIMIT 1' );
}

function frontend_cssjs() { 
	wp_enqueue_script('pts-testimonials-script', PREMIUM_TESTIMONIALS_SCRIPT_JS . '/pt.js', array('jquery'));
// 	wp_enqueue_script('pts-testimonials-script', PREMIUM_TESTIMONIALS_SCRIPT_JS . '/jquery.cycle.js', array('jquery'));
// 	wp_enqueue_script('pts-testimonials-script-3', PREMIUM_TESTIMONIALS_SCRIPT_JS . '/jquery.simple-rotator-div.js', array('jquery'));
// 	wp_enqueue_script('pts-testimonials-script-4', PREMIUM_TESTIMONIALS_SCRIPT_JS . '/jquery.iframe-post-form.js', array('jquery'));
// 	wp_enqueue_script('pts-testimonials-script-5', PREMIUM_TESTIMONIALS_SCRIPT_JS . '/masonry.pkgd.min.js', array('jquery'));
// 	wp_enqueue_script('pts-testimonials-script-6', PREMIUM_TESTIMONIALS_SCRIPT_JS . '/pts-testimonials-frontend.js', array('jquery'));
	
	wp_enqueue_style( 'pts-testimonials-style', PREMIUM_TESTIMONIALS_SCRIPT_CSS . '/pts-style.css', false, '1.0', 'all' ); // Inside a plugin
}

function pts_testiminials_head() {
	?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		var pts_testimonials_assets_img = '<?php echo PREMIUM_TESTIMONIALS_SCRIPT_IMG; ?>';
		var pts_config_carousel_speed = '<?php echo get_option('pts_display_setting_carousel_speed'); ?>';
		var pts_config_carousel_fade_speed = '<?php echo get_option('pts_display_setting_carousel_transition'); ?>';
	</script>
	<?php
}


// resize image function
function pts_aq_resize( $url, $width = null, $height = null, $crop = true, $single = true, $upscale = false ) {

	if ( ! $url || ( ! $width && ! $height ) ) return false;

	if ( true === $upscale ) add_filter( 'image_resize_dimensions', 'pts_aq_upscale', 10, 6 );

	$upload_info = wp_upload_dir();
	$upload_dir = $upload_info['basedir'];
	$upload_url = $upload_info['baseurl'];

	$http_prefix = "http://";
	$https_prefix = "https://";

	if(!strncmp($url,$https_prefix,strlen($https_prefix))){ 
		$upload_url = str_replace($http_prefix,$https_prefix,$upload_url);
	}
	elseif(!strncmp($url,$http_prefix,strlen($http_prefix))){ 
		$upload_url = str_replace($https_prefix,$http_prefix,$upload_url);		
	}


	if ( false === strpos( $url, $upload_url ) ) return false;

	$rel_path = str_replace( $upload_url, '', $url );
	$img_path = $upload_dir . $rel_path;

	if ( ! file_exists( $img_path ) or ! getimagesize( $img_path ) ) return false;

	$info = pathinfo( $img_path );
	$ext = $info['extension'];
	list( $orig_w, $orig_h ) = getimagesize( $img_path );

	$dims = image_resize_dimensions( $orig_w, $orig_h, $width, $height, $crop );
	$dst_w = $dims[4];
	$dst_h = $dims[5];

	if ( ! $dims && ( ( ( null === $height && $orig_w == $width ) xor ( null === $width && $orig_h == $height ) ) xor ( $height == $orig_h && $width == $orig_w ) ) ) {
		$img_url = $url;
		$dst_w = $orig_w;
		$dst_h = $orig_h;
	} else {
		$suffix = "{$dst_w}x{$dst_h}";
		$dst_rel_path = str_replace( '.' . $ext, '', $rel_path );
		$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";

		if ( ! $dims || ( true == $crop && false == $upscale && ( $dst_w < $width || $dst_h < $height ) ) ) {
			return false;
		}
		elseif ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
			$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
		}
		else {

			if ( function_exists( 'wp_get_image_editor' ) ) {

				$editor = wp_get_image_editor( $img_path );

				if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
					return false;

				$resized_file = $editor->save();

				if ( ! is_wp_error( $resized_file ) ) {
					$resized_rel_path = str_replace( $upload_dir, '', $resized_file['path'] );
					$img_url = $upload_url . $resized_rel_path;
				} else {
					return false;
				}

			} else {

				$resized_img_path = image_resize( $img_path, $width, $height, $crop ); // Fallback foo.
				if ( ! is_wp_error( $resized_img_path ) ) {
					$resized_rel_path = str_replace( $upload_dir, '', $resized_img_path );
					$img_url = $upload_url . $resized_rel_path;
				} else {
					return false;
				}

			}

		}
	}

	if ( true === $upscale ) remove_filter( 'image_resize_dimensions', 'pts_aq_upscale' );

	if ( $single ) {
		$image = $img_url;
	} else {
		$image = array (
			0 => $img_url,
			1 => $dst_w,
			2 => $dst_h
		);
	}

	return $image;
}


function pts_aq_upscale( $default, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
	if ( ! $crop ) return null;

	$aspect_ratio = $orig_w / $orig_h;
	$new_w = $dest_w;
	$new_h = $dest_h;

	if ( ! $new_w ) {
		$new_w = intval( $new_h * $aspect_ratio );
	}

	if ( ! $new_h ) {
		$new_h = intval( $new_w / $aspect_ratio );
	}

	$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

	$crop_w = round( $new_w / $size_ratio );
	$crop_h = round( $new_h / $size_ratio );

	$s_x = floor( ( $orig_w - $crop_w ) / 2 );
	$s_y = floor( ( $orig_h - $crop_h ) / 2 );

	return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}