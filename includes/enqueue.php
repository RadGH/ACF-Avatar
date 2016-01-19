<?php
function acfav_enqueue_scripts_and_styles() {
	wp_enqueue_style( 'acf-avatar-admin', AcfAV_URL . '/assets/acf-avatar-admin.css', array(), '1.0' );
}

add_action( 'admin_enqueue_scripts', 'acfav_enqueue_scripts_and_styles' );