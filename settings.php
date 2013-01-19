<?php

// Adding options page
function js_menu() {
	add_options_page('3D Gallery Room','3D Gallery Room','manage_options','js_options','js_options');
}
add_action('admin_menu', 'js_menu');

function js_options(){
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	?>
	<form action="options.php" method="post">
	  <div class="wrap">
		<?php wp_nonce_field('update-options') ?>
		  <h2>3D Gallery Room <?php _e('Settings', 'imageGalleryRoom') ?></h2>
		  <table border="0" cellspacing="6" cellpadding="6">
			<tr>
			  <td><?php _e('Wall 1', 'imageGalleryRoom') ?></td>
			  <td><input name="wall1" type="number" id="wall1" value="<?php echo get_option('wall1'); ?>" />px</td>
			</tr>
			<tr>
			  <td><?php _e('Wall 2', 'imageGalleryRoom') ?></td>
			  <td><input name="wall2" type="number" id="wall2" value="<?php echo get_option('wall2'); ?>" size="4" />px</td>
			</tr>
			<tr>
			  <td><?php _e('Wall 3', 'imageGalleryRoom') ?></td>
			  <td><input name="wall3" type="number" id="wall3" value="<?php echo get_option('wall3'); ?>" size="4" />px</td>
			</tr>
			<tr>
			  <td><?php _e('Wall 4', 'imageGalleryRoom') ?></td>
			  <td><input name="wall4" type="number" id="wall4" value="<?php echo get_option('wall4'); ?>" size="4" />px</td>
			</tr>
		  </table>
		</div>
	</form>
	<?php
}