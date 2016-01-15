<?php
add_filter( 'get_avatar', 'acfav_get_avatar', 10, 5 );
add_filter( 'get_avatar_data', 'acfav_get_avatar_data', 10, 2 );

function acfav_get_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
	if ( !$id_or_email ) return $avatar;

	// Do not filter avatars on the discussion page
	if ( is_admin() ) {
		$screen = get_current_screen();
		if ( $screen->id == 'options-discussion' ) return $avatar;
	}

	$user = false;

	// Get user by id or email
	if ( is_numeric( $id_or_email ) ) {
		$id   = (int) $id_or_email;
		$user = get_user_by( 'id' , $id );
	} elseif ( is_object( $id_or_email ) ) {
		if ( ! empty( $id_or_email->user_id ) ) {
			$id   = (int) $id_or_email->user_id;
			$user = get_user_by( 'id' , $id );
		}
	} else {
		$user = get_user_by( 'email', $id_or_email );
	}

	if ( !$user || is_wp_error($user) ) return $avatar;

	$user_id = $user->ID;

	$image_id = get_user_meta($user_id, 'cfm_avatar', true);

	if ( ! $image_id ) return $avatar;

	// Get the file size and url
	$image_url  = wp_get_attachment_image_src( $image_id, 'avatar' ); // Set image size by name
	$avatar_url = $image_url[0];

	return '<img alt="' . $alt . '" src="' . $avatar_url . '" class="avatar avatar-' . $size . '" height="' . $size . '" width="' . $size . '"/>';
}


function acfav_get_avatar_data( $args, $id_or_email ) {
	if ( !$id_or_email ) return $args;

	// Do not filter avatars on the discussion page
	if ( is_admin() ) {
		$screen = get_current_screen();
		if ( $screen->id == 'options-discussion' ) return $args;
	}

	$user = false;

	// Get user by id or email
	if ( is_numeric( $id_or_email ) ) {
		$id   = (int) $id_or_email;
		$user = get_user_by( 'id' , $id );
	} elseif ( is_object( $id_or_email ) ) {
		if ( ! empty( $id_or_email->user_id ) ) {
			$id   = (int) $id_or_email->user_id;
			$user = get_user_by( 'id' , $id );
		}
	} else {
		$user = get_user_by( 'email', $id_or_email );
	}

	if ( !$user || is_wp_error($user) ) return $args;

	$user_id = $user->ID;

	$image_id = get_user_meta($user_id, 'avatar', true);

	if ( ! $image_id ) return $args;

	// Get the file size and url
	$image_url  = wp_get_attachment_image_src( $image_id, 'avatar' ); // Set image size by name

	if ( $image_url ) {
		$args['size'] = filesize( $image_url );
		$args['found_avatar'] = true;

		$args['url'] = $image_url[0];
		$args['width'] = $image_url[1];
		$args['height'] = $image_url[2];
	}

	return $args;
}