<?php
defined('PREMIUM_TESTIMONIALS_SCRIPT_VERSION') or exit;


function __pts_admin_menu()
{
	add_menu_page('Premium testimonials', 'Testimonials', 'update_core', 'premium-template-script', 'pts_admin_index', PREMIUM_TESTIMONIALS_SCRIPT_IMG . '/pts_icon.png');
	add_submenu_page('premium-template-script', 'Premium testimonials', 'All testimonials', 'update_core', 'premium-template-script', 'pts_admin_index');
	add_submenu_page('premium-template-script', 'Settings - Premium testimonials', 'Settings', 'update_core', 'premium-template-script-settings', 'pts_admin_testimonial_settings');
	add_submenu_page('premium-template-script', 'Integration - Premium testimonials', 'Shortcodes', 'update_core', 'premium-template-script-integration', 'pts_admin_testimonial_integration');
}

function pts_admin_register_head() {
	echo "<link rel='stylesheet' type='text/css' href='".PREMIUM_TESTIMONIALS_SCRIPT_CSS."/pts-admin.css' />\n";
	echo "<script src='".PREMIUM_TESTIMONIALS_SCRIPT_JS."/pts-testimonials-admin.js'></script>";
}

function pts_admin_head()
{
	?>
	<div class="wrap">
    <div class="updated" style="display: none;">
        <p></p>
    </div>
	<?php
}

function pts_admin_footer()
{
	?>
	</div>
	
	<div class="pts-footer-admin">
		&copy; 2013 - <a href="http://eztiv.com/" title="Eztiv">eztiv.com</a>
	</div>
	<?php
}

function pts_admin_index()
{
	pts_admin_head();	
	screen_icon('pts');
	?>
	<h2>Testimonials</h2>
	<table class="widefat pts-testi-table">
		<thead>
		    <tr>
		        <th style="width: 2%;">Id</th>
		        <th style="width: 10%;">Name</th>       
		        <th style="width: 5%;">Email</th> 
		        <th style="width: 8%;">Website</th> 
		        <th style="width: 60%;">Testimonial</th>
		        <th style="width: 2%; text-align: center;">Approved</th>
		    </tr>
		</thead>
		<tfoot>
		    <tr>
			    <th>Id</th>
			    <th>Name</th>
			    <th>Email</th>
			    <th>Website</th>
		        <th>Testimonial</th>
		        <th style="text-align: center;">Approved</th>
		    </tr>
		</tfoot>
		<tbody>
			<?php __getTestimonialsAdminTable(); ?>
		</tbody>
	</table>
	<?php 
	pts_admin_footer();
}

function __getTestimonialsAdminTable($only_last = false)
{
	if ($only_last === true)
	{
		$testimonials = pts_get_last_testimonial();
	}
	else
	{
		$testimonials = pts_get_testimonials_all();
	}
	
	if (is_array($testimonials) && !empty($testimonials)) : ?>
		<?php foreach ($testimonials as $key=>$row) : ?>
		   <tr pts_id="<?php echo $row->id; ?>" class="<?php if ($row->pts_active == '1') echo 'pts-approved'; else echo 'pts-unapproved'; ?> pts-action-list-<?php echo $row->id; ?>">
				<td class="column-columnname"><?php echo $row->id; ?></td>
				<td class="column-columnname pts-table-field-name"><?php echo htmlentities(stripslashes($row->pts_name)); ?></td>
				<td class="column-columnname pts-table-field-email"><?php echo htmlentities(stripslashes($row->pts_email)); ?></td>
				<td class="column-columnname pts-table-field-website"><?php echo htmlentities(stripslashes($row->pts_website)); ?></td>
				<td class="column-columnname">
					<span class="pts-table-field-content"><?php echo htmlentities(stripslashes($row->pts_content)); ?></span>
	                <div class="row-actions">
	                    <span class="<?php if ($row->pts_active == '1') echo 'pts-active'; else echo 'pts-inactive'; ?> pts-status-approve pts-testimonials-action-button"><a href="#" class="button-secondary pts-testimonials-unapprove">Unapprove</a></span>
	                    <span class="<?php if ($row->pts_active != '1') echo 'pts-active'; else echo 'pts-inactive'; ?> pts-status-unapprove pts-testimonials-action-button"><a href="#" class="button-secondary pts-testimonials-approve">Approve</a></span>
	                    <span><a href="#" class="button-secondary pts-inline-edit-button">Edit</a></span>
	                    <span class="spam"><a href="" class="button-secondary pts-testimonials-remove">Delete</a></span>
	                </div>
				</td>
				<td class="column-columnname pts-testimonials-status-cell" style="text-align: center;"><?php echo ($row->pts_active == '1') ? 'Yes' : '<span class="spam"><a>No</a></span>'; ?></td>
		   </tr>
		   <tr pts_id="<?php echo $row->id; ?>" class="pts-action-form pts-action-form-<?php echo $row->id; ?>">
		   		<td colspan="5">
			   		<form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			   			<input type="hidden" name="id" value="<?php echo $row->id; ?>"/>
					    <ul>
					        <li><label for="fname">Name: </label>
					        <input class="pts-input" id="fname" maxlength="45" size="10" name=pts_name value="<?php echo htmlentities(stripslashes($row->pts_name)); ?>" /></li>  
					         
					        <li><label for="femail">Email: </label>
					        <input class="pts-input" id="femail" maxlength="45" size="10" name="pts_email" value="<?php echo htmlentities(stripslashes($row->pts_email)); ?>" /></li>  
					         
					        <li><label for="fwebsite">Website: </label>
					        <input class="pts-input" id="fwebsite" maxlength="45" size="10" name="pts_website" value="<?php echo htmlentities(stripslashes($row->pts_website)); ?>" /></li>  
					         
					        <li><label for="fphoto">Photo: </label>
					        <a href="<?php echo PREMIUM_TESTIMONIALS_SCRIPT_URL_BASE_UPLOAD_DIR . stripslashes($row->pts_photo); ?>" target="_blank"><img src="<?php echo pts_aq_resize(PREMIUM_TESTIMONIALS_SCRIPT_URL_BASE_UPLOAD_DIR . stripslashes($row->pts_photo), 150, 150); ?>" /></a>
					        </li>  
					         
					        <li><label for="ftestimonial">Testimonial: </label>
					        <textarea id="ftestimonial" name="pts_content"><?php echo stripslashes($row->pts_content); ?></textarea></li>  
					        
					        <li><input type="submit" value="Edit" class="button-primary pts-testimonial-save"/></li>
					    </ul>
					</form>
		   		</td>
		   </tr>
		<?php endforeach; ?>
	<?php else: ?>
		<tr class="pts-no-entries">
			<td colspan="4">No entries in this table.</td>
		</tr>
	<?php endif;
}

function pts_admin_testimonial_settings()
{

	pts_admin_head();
	screen_icon('tools');
	?>
	<h2>Settings</h2>
	
	<div class="pts-admin-form">
		<form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<input type="hidden" name="action" value="pts_integration_settings"/>

			<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
			<h2>General</h2>
			<ul>
		    	<li><label for="submit-photo" class="inline-label">Display option:</label>
		    		<input <?php if (get_option('pts_allow_approved') == '1') echo 'checked'; ?> type="checkbox" name="pts_allow_approved" value="1" /> Display only approved testimonials
		    	</li>
			</ul>
			<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
			<h2>Form</h2>
		    <ul>
		    	<li><label for="submit-photo" class="inline-label">Photo:</label>
		    		<input <?php if (get_option('pts_allow_photo') == '1') echo 'checked'; ?> type="checkbox" name="pts_allow_photo" value="1" /> Enable image upload
		    	</li>
		    	<li><label for="submit-photo" class="inline-label">Name:</label>
		    		<input <?php if (get_option('pts_allow_name') == '1') echo 'checked'; ?> type="checkbox" name="pts_allow_name" value="1" /> Add required field Name
		    	</li>
		    	<li><label for="submit-photo" class="inline-label">Email:</label>
		    		<input <?php if (get_option('pts_allow_email') == '1') echo 'checked'; ?> type="checkbox" name="pts_allow_email" value="1" /> Add required field Email
		    	</li>
		    	<li><label for="submit-photo" class="inline-label">Website:</label>
		    		<input <?php if (get_option('pts_allow_website') == '1') echo 'checked'; ?> type="checkbox" name="pts_allow_website" value="1" /> Add required field Website
		    	</li>
		    	
		    	<li><label for="pts_testimonial_success_form" class="inline-label">Success message:</label>
		    		<textarea id="pts_testimonial_success_form" name="pts_testimonial_success_form"><?php echo get_option('pts_testimonial_success_form'); ?></textarea></li>
		    		
		    	<li><label for="pts_popup_add_title" class="inline-label-input inline-label">Popup title:</label>
		    		<input class="pts-input" id="pts_popup_add_title" name="pts_popup_add_title" value="<?php echo get_option('pts_popup_add_title'); ?>"/></li>
		    </ul>


			<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
		    <h2>Carousel</h2>
			<ul>
		    	<li><label for="pts_display_setting_carousel_speed" class="inline-label-input inline-label">Carousel speed:</label>
		    		<input class="pts-input" id="pts_display_setting_carousel_speed" name="pts_display_setting_carousel_speed" value="<?php echo get_option('pts_display_setting_carousel_speed'); ?>"/></li>
		    
		    	<li><label for="pts_display_setting_carousel_transition" class="inline-label-input inline-label">Carousel fade speed:</label>
		    		<input class="pts-input" id="pts_display_setting_carousel_transition" name="pts_display_setting_carousel_transition" value="<?php echo get_option('pts_display_setting_carousel_transition'); ?>"/></li>
		    </ul>

			<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
		    <h2>Add Color Codes to be used by Testimonials without uploaded images</h2>
			<ul>
		    	<li><label for="pts_testimonial_color_box" class="inline-label">Colors (comma separated):</label>
		    		<textarea id="pts_testimonial_color_box" name="pts_testimonial_color_box"><?php echo implode(',', unserialize(get_option('pts_testimonial_color_box'))); ?></textarea></li>
		    </ul>

		    <input type="submit" value="Save settings" class="button-primary pts-testimonial-integration-save"/>
		</form>
	</div>
	<?php 
	pts_admin_footer();
}

function pts_admin_testimonial_integration() {

	pts_admin_head();
	?>
	<div class="pts-admin-form">
		<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
		<h2>Shortcodes</h2>
		
		<form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<input type="hidden" name="action" value=""/>
			<ul>
				<li><label for="shortcode-add-page">Embed testimonial form:</label>
					<textarea id="shortcode-add-page" readonly="readonly">[premiumTestimonialsAdd ][/premiumTestimonialsAdd]</textarea>
				</li>
			
				<li><label for="shortcode-add-page">Embed testimonials (basic version):</label>
					<textarea id="shortcode-add-page" readonly="readonly">[premiumTestimonialsDisplay template="basic"][/premiumTestimonialsDisplay]</textarea>
				</li>
			
				<li><label for="shortcode-add-page">Embed testimonials (portraits version):</label>
					<textarea id="shortcode-add-page" readonly="readonly">[premiumTestimonialsDisplay template="portraits"][/premiumTestimonialsDisplay]</textarea>
				</li>
			
				<li><label for="shortcode-add-page">Embed testimonials (masonry version):</label>
					<textarea id="shortcode-add-page" readonly="readonly">[premiumTestimonialsDisplay template="masonry"][/premiumTestimonialsDisplay]</textarea>
				</li>
				
				<li><label for="shortcode-add-page">Embed testimonials (carousel version):</label>
					<textarea id="shortcode-add-page" readonly="readonly">[premiumTestimonialsDisplay template="carousel"][/premiumTestimonialsDisplay]</textarea>
				</li>
				
				<li><label for="shortcode-add-page">Embed testimonials (boxed version):</label>
					<textarea id="shortcode-add-page" readonly="readonly">[premiumTestimonialsDisplay template="boxed"][/premiumTestimonialsDisplay]</textarea>
				</li>
			</ul>
		</form>
	</div>
	<?php
	pts_admin_footer();
}


function pts_testimonials_options_install() {
	global $wpdb;

	// create the ECPT metabox database table
	if($wpdb->get_var("show tables like '".PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME."'") != PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME)
	{	
		$sql = "CREATE TABLE IF NOT EXISTS `" . PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME . "` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `pts_name` varchar(255) DEFAULT NULL,
			  `pts_email` varchar(255) DEFAULT NULL,
			  `pts_website` varchar(255) DEFAULT NULL,
			  `pts_photo` varchar(255) DEFAULT NULL,
			  `pts_content` text NOT NULL,
			  `pts_active` int(11) NOT NULL DEFAULT '0',
			  UNIQUE KEY `id` (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	add_option('pts_allow_photo', 1);
	add_option('pts_allow_name', 1);
	add_option('pts_allow_email', 1);
	add_option('pts_allow_website', 1);
	add_option('pts_allow_approved', 1);
	add_option('pts_testimonial_success_form', 'Your testimonial has been submitted and awaiting to be approved.');
	add_option('pts_popup_add_title', 'Add Testimonial');
	add_option('pts_display_setting_carousel_speed', 4000);
	add_option('pts_display_setting_carousel_transition', 1000);
	add_option('pts_testimonial_color_box', serialize( array('#69c5ff', '#80a96c', '#d5945a', '#775ad5', '#b59ffc') ));
}

function pts_approve_testiminial() {
	global $wpdb;
	$id = (isset($_POST['testimonial_id'])) ? intval($_POST['testimonial_id']) : false;
	$wpdb->update(PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME, array('pts_active' => '1'), array( 'id' => $id ));
	exit;
}
function pts_unapprove_testiminial() {
	global $wpdb;
	$id = (isset($_POST['testimonial_id'])) ? intval($_POST['testimonial_id']) : false;
	$wpdb->update(PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME, array('pts_active' => '0'), array( 'id' => $id ));
	exit;
}
function pts_delete_testiminial() {
	global $wpdb;
	$id = (isset($_POST['testimonial_id'])) ? intval($_POST['testimonial_id']) : false;
	
	$row = $wpdb->get_row( "SELECT * FROM " . PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME . ' WHERE id = ' . $id . ' LIMIT 1' );
	unlink(PREMIUM_TESTIMONIALS_SCRIPT_DIR_BASE_UPLOAD_DIR . $row->pts_photo);
	$wpdb->delete(PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME, array( 'id' => $id ));
	exit;
}
function pts_edit_testimonial() {
	global $wpdb;
	$id = (isset($_POST['testimonial_id'])) ? intval($_POST['testimonial_id']) : false;
	$pts_name = (isset($_POST['name'])) ? $_POST['name'] : '';
	$pts_email = (isset($_POST['email'])) ? $_POST['email'] : '';
	$pts_website = (isset($_POST['website'])) ? $_POST['website'] : '';
	$pts_content = (isset($_POST['content'])) ? $_POST['content'] : '';
	
	$wpdb->update(PREMIUM_TESTIMONIALS_SCRIPT_DB_NAME, array('pts_name' => $pts_name, 'pts_email' => $pts_email, 'pts_website' => $pts_website, 'pts_content' => $pts_content), array( 'id' => $id ));
	$res = array();
	foreach ($_POST as $key=>$val)
	{
		$res[$key] = stripslashes($val);
	}
	echo json_encode($res);
	exit;
}
function pts_integration_settings() {
	update_option('pts_allow_photo', (!empty($_POST['pts_allow_photo'])) ? 1 : 0);
	update_option('pts_allow_name', (!empty($_POST['pts_allow_name'])) ? 1 : 0);
	update_option('pts_allow_email', (!empty($_POST['pts_allow_email'])) ? 1 : 0);
	update_option('pts_allow_website', (!empty($_POST['pts_allow_website'])) ? 1 : 0);
	update_option('pts_allow_approved', (!empty($_POST['pts_allow_approved'])) ? 1 : 0);
	update_option('pts_testimonial_success_form', (!empty($_POST['pts_testimonial_success_form'])) ? $_POST['pts_testimonial_success_form'] : 'Your testimonial has been submitted and awaiting to be approved.');
	update_option('pts_popup_add_title', (!empty($_POST['pts_popup_add_title'])) ? $_POST['pts_popup_add_title'] : 'Add Testimonial');
	update_option('pts_display_setting_carousel_speed', (!empty($_POST['pts_display_setting_carousel_speed'])) ? $_POST['pts_display_setting_carousel_speed'] : 1000);
	update_option('pts_display_setting_carousel_transition', (!empty($_POST['pts_display_setting_carousel_transition'])) ? $_POST['pts_display_setting_carousel_transition'] : 500);
	
	$colors = (!empty($_POST['pts_testimonial_color_box'])) ? $_POST['pts_testimonial_color_box'] : false;
	if ($colors !== false)
	{
		$array_colors = explode(',', $colors);
		array_walk($array_colors, create_function('&$val', '$val = trim($val);'));
		update_option('pts_testimonial_color_box', serialize($array_colors));
	}
	exit;
}

