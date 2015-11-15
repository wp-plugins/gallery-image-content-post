<?php
/*
Plugin Name: Gallery Image Content Post
Plugin URI: http://pencidesign.com/
Description: Auto add the image gallery or single image lightbox for your content
Version: 1.2
Author: PenciDesign
Author URI: http://pencidesign.com/
License: GPLv2 or later
Text Domain: gallery-image-content

Copyright @2015  PenciDesign  (email: pencidesign@gmail.com)
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Define
 */
define( 'GALLERY_IMAGE_CONTENT_DIR', plugin_dir_path( __FILE__ ) );
define( 'GALLERY_IMAGE_CONTENT_URL', plugin_dir_url( __FILE__ ) );
define( 'GIC', 'gallery-image-content' );
define( 'GIC_OP', 'gallery_image_content' );

/**
 * Kang_Gallery_Image_Content Class
 *
 * This is main class of plugin
 * Auto add the image gallery or single image zoom for your content when this plugin activated
 *
 * @author KanG
 * @since  1.0
 */
if ( ! class_exists( 'Kang_Gallery_Image_Content' ) ) :

	class Kang_Gallery_Image_Content {
		/**
		 * Global plugin version
		 */
		static $version = '1.1';

		/**
		 * Kang_Gallery_Image_Content Constructor.
		 *
		 * @access public
		 * @return void
		 * @author KanG
		 * @since  1.0
		 */
		public function __construct() {
			// register action when plugin is activated
			register_activation_hook( __FILE__, array( $this, 'active_plugin' ) );

			// load plugin text domain for translations
			add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );

			// enqueue main style for front-end
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// register admin options
			add_action( 'admin_init', array( $this, 'admin_options' ) );

			// add plugin options page
			add_action( 'admin_menu', array( $this, 'add_options_page' ) );

			// add settings link to plugins page
			add_filter( 'plugin_action_links', array( $this, 'add_settings_links' ), 10, 2 );

			// add filter class to body_class
			add_filter( 'body_class', array( $this, 'filter_body_class' ) );

			// add filter class to image content post
			add_filter( 'the_content', array( $this, 'filter_image_attr' ) );
		}

		/**
		 * Active this plugin
		 * Set default values for general options
		 *
		 * @access public
		 * @return void
		 * @since  1.0
		 */
		public function active_plugin() {
			$defaults = array(
				'type'       => 'gallery',
				'effect'     => 'zoom',
				'show_title' => 'false',
			);

			add_option( GIC_OP, $defaults );
		}

		/**
		 * Transition ready
		 *
		 * @access public
		 * @return void
		 * @since  1.0
		 */
		public function load_text_domain() {
			load_plugin_textdomain( GIC, false, GALLERY_IMAGE_CONTENT_DIR . '/languages/' );
		}

		/**
		 * Enqueue style for front-end
		 * Enqueue lib and main javascript
		 *
		 * @access public
		 * @return void
		 * @since  1.0
		 */
		public function enqueue_scripts() {
			wp_enqueue_style( 'gallery-image-content', GALLERY_IMAGE_CONTENT_URL . 'css/gallery-image-content.css', false, self::$version );
			wp_enqueue_script( 'jquery-magnific-popup', GALLERY_IMAGE_CONTENT_URL . 'js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.0.0', true );
			wp_enqueue_script( 'gallery-image-content', GALLERY_IMAGE_CONTENT_URL . 'js/gallery-image-content.js', array( 'jquery' ), self::$version, true );
		}

		/**
		 * Whitelist plugin options
		 *
		 * @access public
		 * @return void
		 * @since  1.0
		 */
		public function admin_options() {
			register_setting( GIC_OP, GIC_OP, array( $this, 'validate_options' ) );
		}

		/**
		 * Sanitize and validate options
		 *
		 * @access public
		 *
		 * @param  array $input
		 *
		 * @return array
		 * @since  1.0
		 */
		public function validate_options( $input ) {

			$options  = array();
			$defaults = array(
				'type'       => 'gallery',
				'effect'     => 'zoom',
				'show_title' => 'false',
			);

			foreach ( $defaults as $name => $val ) {
				$options[$name] = isset( $input[$name] ) ? $input[$name] : $val;
			}

			return $options;
		}

		/**
		 * Add options page of plugin
		 *
		 * @access public
		 * @return void
		 * @since  1.0
		 */
		public function add_options_page() {
			add_options_page( __( 'Gallery Image Content Options', GIC ), __( 'Gallery Image Content', GIC ), 'manage_options', 'gallery-image-content', array(
				$this,
				'plugin_form'
			) );
		}

		/**
		 * Render the Plugin options form
		 *
		 * @access public
		 * @return void
		 * @since  1.0
		 */
		public function plugin_form() {
			include( 'inc/plugin-form.php' );
		}

		/**
		 * Applied to the list of links to display on the plugins page
		 *
		 * @access public
		 *
		 * @param  array $actions
		 * @param  string $plugin_file
		 * @return array
		 * @since  1.1
		 */
		public function add_settings_links( $actions, $plugin_file ) {

			if ( ! isset( $plugin ) )
				$plugin = plugin_basename( __FILE__ );
			if ( $plugin == $plugin_file ) {

				$settings     = array( 'settings' => '<a href="' . admin_url( 'options-general.php?page=gallery-image-content' ) . '">' . __( 'Settings', GIC ) . '</a>' );
				$more_link    = array( 'more' => '<a href="http://themeforest.net/user/pencidesign/portfolio" target="_blank">' . __( 'Need A Theme', GIC ) . '</a>' );

				$actions = array_merge( $settings, $actions );
				$actions = array_merge( $more_link, $actions );

			}

			return $actions;
		}

		/**
		 * Add filter class to body tag
		 * Hook to body_class filter
		 * Get your options to add new class to body
		 *
		 * @access public
		 *
		 * @param array $classes
		 *
		 * @return array new $classes
		 * @since  1.0
		 */
		public function filter_body_class( $classes ) {
			// Get your options
			$options = get_option( GIC_OP );
			$type    = ! empty ( $options['type'] ) ? $options['type'] : 'gallery';
			$effect  = ! empty ( $options['effect'] ) ? $options['effect'] : 'zoom';

			if ( $type != 'gallery' )
				$classes[] = 'single-lightbox';

			if ( $effect == 'zoom' )
				$classes[] = 'gallery-image-zoom-effect';

			return $classes;
		}

		/**
		 * Add filter class to image content post
		 * Hook to get_image_tag_class filter
		 *
		 * @access public
		 *
		 * @param html $content
		 *
		 * @return new $content
		 * @since  1.0
		 */
		public function filter_image_attr( $content ) {
			global $post;
			// Get your options
			$options    = get_option( GIC_OP );
			$type       = ! empty ( $options['show_title'] ) ? $options['show_title'] : 'false';
			$title_attr = '';
			if ( $type == 'true' ): $title_attr = ' title="' . $post->post_title . '"'; endif;

			$pattern     = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)><img/i";
			$replacement = '<a$1href=$2$3.$4$5 rel="gallery-image-content"' . $title_attr . '$6><img';
			$content     = preg_replace( $pattern, $replacement, $content );

			return $content;
		}
	}

endif; // End class

new Kang_Gallery_Image_Content();