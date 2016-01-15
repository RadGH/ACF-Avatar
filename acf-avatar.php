<?php
/*
Plugin Name: Advanced Custom Fields - Avatar
Description: Select images with Advanced Custom Fields to use as an avatar on your website.
Plugin URI:  http://www.radgh.com/
Version:     1.0.2
Author:      Radley Sustaire
Author URI:  mailto:radleygh@gmail.com
License:     GPL2
*/

/*
GNU GENERAL PUBLIC LICENSE

Adds a widget with three tabs to choose between day/week/month of popular posts.
Copyright (C) 2015 Radley Sustaire

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if( !defined( 'ABSPATH' ) ) exit;

define( 'AcfAV_URL', plugin_dir_url( __FILE__ ) );
define( 'AcfAV_PATH', plugin_dir_path(__FILE__) );

add_action( 'plugins_loaded', 'acfav_init_plugin' );
add_action( 'after_setup_theme', 'acfav_add_image_size', 15 );
add_action( 'admin_enqueue_scripts', 'acfav_enqueue_scripts_and_styles' );

function acfav_init_plugin() {
	if ( !function_exists('acf') ) {
		add_action( 'admin_notices', 'acfav_dependency_not_found' );
		return;
	}else{
		include( AcfAV_PATH . '/includes/usermeta.php' );
		include( AcfAV_PATH . '/fields/avatar.php' );
	}

	if ( is_admin() && !get_option( 'show_avatars' ) ) {
		add_action( 'admin_notices', 'acfav_avatars_disabled' );
	}
}

function acfav_dependency_not_found() {
	?>
	<div class="error">
		<p><strong>Limelight - ACF Avatar: Error</strong></p>
		<p>This plugin depends on "Advanced Custom Fields" (or the pro version) to be active. Please install or activate the required plugin.</p>
	</div>
	<?php
}

function acfav_avatars_disabled() {
	?>
	<div class="error">
		<p><strong>Limelight - ACF Avatar: Error</strong></p>
		<p>Avatars have been disabled, this plugin may not work as expected. Please enable avatars under <a href="<?php echo esc_attr(admin_url('options-discussion.php')); ?>">Settings &gt; Discussion</a>.</p>
	</div>
	<?php
}

function acfav_add_image_size() {
	$size = array(
		'avatar',
		75,
		75,
		true
	);

	$size = apply_filters( 'acf-avatar-image-size', $size );

	if ( $size ) add_image_size( $size[0], $size[1], $size[2], $size[3] );
}

function acfav_enqueue_scripts_and_styles() {
	$screen = get_current_screen();

	if ( $screen && ($screen->id == 'profile' || $screen->id == 'user-edit') ) {
		wp_enqueue_style( 'acf-avatar', AcfAV_URL . '/assets/acf-avatar.css', array(), '1.0' );
	}
}