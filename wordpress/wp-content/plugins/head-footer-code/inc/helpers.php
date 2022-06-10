<?php
/**
 * Various helpers for Head & Footer Code
 *
 * @package Head_Footer_Code
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Include back-end/front-end resources.
if ( is_admin() ) {
	require_once WPAU_HEAD_FOOTER_CODE_INC . 'settings.php';
	require_once WPAU_HEAD_FOOTER_CODE_INC . 'posts-custom-columns.php';
	require_once WPAU_HEAD_FOOTER_CODE_INC . 'class-auhfc-meta-box.php';
	require_once WPAU_HEAD_FOOTER_CODE_INC . 'auhfc-category-meta-box.php';
} else {
	require_once WPAU_HEAD_FOOTER_CODE_INC . 'front.php';
}

register_activation_hook( WPAU_HEAD_FOOTER_CODE_FILE, 'auhfc_activate' );
/**
 * Plugin Activation hook function to check for Minimum PHP and WordPress versions
 */
function auhfc_activate() {
	global $wp_version;
	$php_req = '5.6'; // Minimum version of PHP required for this plugin.
	$wp_req  = '4.9'; // Minimum version of WordPress required for this plugin.

	if ( version_compare( PHP_VERSION, $php_req, '<' ) ) {
		$flag = 'PHP';
	} elseif ( version_compare( $wp_version, $wp_req, '<' ) ) {
		$flag = 'WordPress';
	} else {
		return;
	}
	$version = 'PHP' === $flag ? $php_req : $wp_req;
	deactivate_plugins( WPAU_HEAD_FOOTER_CODE_FILE );

	wp_die(
		'<p>' . sprintf(
			/* translators: 1: Head & Footer Code, 2: PHP or WordPress, 3: min version of PHP or WordPress */
			esc_html__( 'The %1$s plugin requires %2$s version %3$s or greater.', 'head-footer-code' ),
			sprintf( '<strong>%s</strong>', esc_html__( 'Head & Footer Code', 'head-footer-code' ) ),
			$flag,
			$version
		) . '</p>',
		esc_html__( 'Plugin Activation Error', 'head-footer-code' ),
		array(
			'response'  => 200,
			'back_link' => true,
		)
	);

	// Trigger updater function.
	auhfc_maybe_update();
} // END function auhfc_activate()

// Regular update trigger.
add_action( 'plugins_loaded', 'auhfc_maybe_update' );
/**
 * Function to check and run if update has to be done
 */
function auhfc_maybe_update() {
	// Bail if this plugin data doesn't need updating.
	if ( get_option( 'auhfc_db_ver' ) >= WPAU_HEAD_FOOTER_CODE_DB_VER ) {
		return;
	}
	// Require update script.
	require_once( dirname( __FILE__ ) . '/update.php' );
	// Trigger update function.
	auhfc_update();
} // END function auhfc_maybe_update()

add_action( 'admin_enqueue_scripts', 'auhfc_admin_enqueue_scripts' );
/**
 * Enqueue admin styles and scripts to enable code editor in plugin settings and custom column on article listing
 * 
 * @param  string $hook Current page hook.
 */
function auhfc_admin_enqueue_scripts( $hook ) {
	// Admin Stylesheet.
	if ( in_array( $hook, array( 'edit.php', 'tools_page_head_footer_code' ), true ) ) {
		wp_enqueue_style(
			'head-footer-code-admin',
			plugin_dir_url( __FILE__ ) . '../assets/css/admin.min.css',
			array(),
			WPAU_HEAD_FOOTER_CODE_VER
		);
	}
	// Codemirror Assets.
	if ( 'tools_page_head_footer_code' === $hook ) {
		$cm_settings['codeEditor'] = wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
		wp_localize_script( 'code-editor', 'cm_settings', $cm_settings );
		wp_enqueue_style( 'wp-codemirror' );
		wp_enqueue_script( 'wp-codemirror' );
	}
	return;
} // END function auhfc_admin_enqueue_scripts( $hook )

/**
 * Provide global defaults
 *
 * @return array Arary of defined global values.
 */
function auhfc_settings() {

	$defaults                = array(
		'sitewide' => array(
			'head'           => '',
			'body'           => '',
			'footer'         => '',
			'priority_h'     => 10,
			'priority_b'     => 10,
			'priority_f'     => 10,
			'do_shortcode_h' => 'n',
			'do_shortcode_b' => 'n',
			'do_shortcode_f' => 'n',
		),
		'homepage' => array(
			'head'     => '',
			'body'     => '',
			'footer'   => '',
			'behavior' => 'append',
		),
		'article'  => array(
			'post_types' => array(),
		),
	);
	$auhfc_settings_sitewide = get_option( 'auhfc_settings_sitewide', $defaults['sitewide'] );
	$defaults['sitewide']    = wp_parse_args( $auhfc_settings_sitewide, $defaults['sitewide'] );
	$auhfc_settings_homepage = get_option( 'auhfc_settings_homepage', $defaults['homepage'] );
	$defaults['homepage']    = wp_parse_args( $auhfc_settings_homepage, $defaults['homepage'] );
	$auhfc_settings_article  = get_option( 'auhfc_settings_article', $defaults['article'] );
	$defaults['article']     = wp_parse_args( $auhfc_settings_article, $defaults['article'] );

	return $defaults;
} // END function auhfc_settings()

/**
 * Get values of metabox fields
 *
 * @param  string $field_name Post meta field key.
 * @param  string $post_id    Post ID (optional).
 * @return string             Post meta field value.
 */
function auhfc_get_meta( $field_name = '', $post_id = null ) {

	if ( empty( $field_name ) ) {
		return false;
	}

	if ( empty( $post_id ) || intval( $post_id ) !== $post_id ) {
		if ( is_admin() ) {
			global $post;

			// If $post has not an object, return false.
			if ( empty( $post ) || ! is_object( $post ) ) {
				return false;
			}

			$post_id = $post->ID;
		} else {
			if ( is_singular() ) {
				global $wp_the_query;
				$post_id = $wp_the_query->get_queried_object_id();
			} else {
				$post_id = false;
			}
		}
	} else {
		$post_id = (int) $post_id;
	}

	if ( empty( $post_id ) ) {
		return false;
	}

	$field = get_post_meta( $post_id, '_auhfc', true );

	if ( ! empty( $field ) && is_array( $field ) && ! empty( $field[ $field_name ] ) ) {
		return stripslashes_deep( $field[ $field_name ] );
	} elseif ( 'behavior' === $field_name ) {
		return 'append';
	} else {
		return false;
	}
} // END function auhfc_get_meta( $field_name )

/**
 * Return debugging string if WP_DEBUG constant is true.
 * 
 * @param  string $scope    Scope of output (s - SITE WIDE, a - ARTICLE SPECIFIC, h - HOMEPAGE).
 * @param  string $location Location of output (h - HEAD, b - BODY, f - FOOTER).
 * @param  string $message  Output message.
 * @param  string $code     Code for output.
 * @return string           Composed string.
 */
function auhfc_out( $scope = null, $location = null, $message = null, $code = null ) {
	if ( ! WP_DEBUG ) {
		return $code;
	}
	if ( null === $scope || null === $location || null === $message ) {
		return;
	}
	switch ( $scope ) {
		case 'h':
			$scope = 'Homepage';
			break;
		case 's':
			$scope = 'Site-wide';
			break;
		case 'a':
			$scope = 'Article specific';
			break;
		case 'c':
			$scope = 'Category specific';
			break;
		default:
			$scope = 'Unknown';
	}
	switch ( $location ) {
		case 'h':
			$location = 'HEAD';
			break;
		case 'b':
			$location = 'BODY';
			break;
		case 'f':
			$location = 'FOOTER';
			break;
		default:
			$location = 'UNKNOWN';
			break;
	}
	return sprintf(
		'<!-- Head & Footer Code: %1$s %2$s section start (%3$s) -->%5$s %4$s%5$s<!-- Head & Footer Code: %1$s %2$s section end (%3$s) -->%5$s',
		$scope,            // 1
		$location,         // 2
		trim( $message ),  // 3
		trim( $code ),     // 4
		"\n"               // 5
	);
} // END function auhfc_out( $scope = null, $location = null, $message = null, $code = null )

/**
 * Function to get Post Type
 */
function auhfc_get_post_type() {
	$auhfc_post_type = 'not singular';
	// Get post type.
	if ( is_singular() ) {
		global $wp_the_query;
		$auhfc_query = $wp_the_query->get_queried_object();
		if ( is_object( $auhfc_query ) ) {
			$auhfc_post_type = $auhfc_query->post_type;
		}
	}
	return $auhfc_post_type;
} // END function auhfc_get_post_type()

/**
 * Function to check if homepage uses Blog mode
 */
function auhfc_is_homepage_blog_posts() {
	if ( is_home() && 'posts' === get_option( 'show_on_front', false ) ) {
		return true;
	}
	return false;
} // END function auhfc_is_homepage_blog_posts()

/**
 * Function to print note for head section
 */
function auhfc_head_note() {
	return '<p class="notice"><strong>' . esc_html__( 'IMPORTANT!', 'head-footer-code' ) . '</strong> ' . sprintf(
		/* translators: 1: italicized 'unseen elements', 2: <script>, 3: <style>, 4: italicized sentence 'could break layouts or lead to unexpected situations' */
		esc_html__( 'Usage of this hook should be reserved for output of %1$s like %2$s and %3$s tags or additional metadata. It should not be used to add arbitrary HTML content to a page that %4$s.', 'head-footer-code' ),
		'<em>' . esc_html__( 'unseen elements', 'head-footer-code' ) . '</em>',
		auhfc_html2code( '<script>' ),
		auhfc_html2code( '<style>' ),
		'<em>' . esc_html__( 'could break layouts or lead to unexpected situations', 'head-footer-code' ) . '</em>'
	) . '</p>';
}

/**
 * Function to print note for body section
 */
function auhfc_body_note() {
	return '<p class="notice"><strong>' . esc_html__( 'IMPORTANT!', 'head-footer-code' ) . '</strong> ' . sprintf(
		/* translators: %s will be replaced with a link to wp_body_open page on WordPress.org */
		esc_html__( 'Make sure that your active theme support %s hook.', 'head-footer-code' ),
		'<a href="https://developer.wordpress.org/reference/hooks/wp_body_open/" target="_hook">wp_body_open</a>'
	) . '</p>';
}

/**
 * Function to convert code to HTML special chars
 *
 * @param string $text RAW content.
 */
function auhfc_html2code( $text ) {
	return '<code>' . htmlspecialchars( $text ) . '</code>';
} // END function auhfc_html2code( $text )

/**
 * Determine should we print site-wide code
 * or it should be replaced with homepage/article/category code.
 *
 * @param  string  $behavior       Behavior for article specific code (replace/append).
 * @param  string  $code           Article specific custom code.
 * @param  string  $post_type      Post type of current article.
 * @param  array   $post_types     Array of post types where article specific code is enabled.
 * @param  boolean $is_category    Indicate if current displayed page is category or not.
 * @return boolean                 Boolean that determine should site-wide code be printed (true) or not (false).
 */
function auhfc_print_sitewide(
	$behavior = 'append',
	$code = '',
	$post_type = null,
	$post_types = array(),
	$is_category = false
) {

	// On homepage print site wide if...
	$is_homepage_blog_posts = auhfc_is_homepage_blog_posts();
	if ( $is_homepage_blog_posts ) {
		// ... homepage behavior is not replace, or...
		// ... homepage behavior is replace but homepage code is empty.
		if (
			'replace' !== $behavior ||
			( 'replace' === $behavior && empty( $code ) )
		) { return true; }
	}
	// On category page print site wide if...
	else if ( $is_category ) {
		// ... behavior is not replace, or...
		// ... behavior is replace but category content is empty.
		if ( 
			'replace' !== $behavior ||
			( 'replace' === $behavior && empty( $code ) )
		) {
			return true;
		}
	}
	// On Blog Post or Custom Post Type ...
	else if (
		// ... article behavior is not replace, or...
		// ... article behavior is replace but current Post Type is not in allowed Post Types, or...
		// ... article behavior is replace and current Post Type is in allowed Post Types but article code is empty.
		'replace' !== $behavior ||
		( 'replace' === $behavior && ! in_array( $post_type, $post_types ) ) ||
		( 'replace' === $behavior && in_array( $post_type, $post_types ) && empty( $code ) )
	) {
		return true;
	}

	return false;
}
