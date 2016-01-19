<?php
function acfav_enqueue_scripts_and_styles() {
	$screen = get_current_screen();

	if ( $screen && ($screen->id == 'profile' || $screen->id == 'user-edit') ) {
		wp_enqueue_style( 'acf-avatar-admin', AcfAV_URL . '/assets/acf-avatar-admin.css', array(), '1.0' );
	}
}

add_action( 'admin_enqueue_scripts', 'acfav_enqueue_scripts_and_styles' );