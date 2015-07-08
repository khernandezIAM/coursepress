<?php

class CoursePress_Helper_JavaScript {

	public static function init() {

		// These don't work here because of core using wp_print_styles()
		//add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		//add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

		add_action( 'admin_footer', array( __CLASS__, 'enqueue_scripts' ) );
		//add_action( 'wp_footer', array( __CLASS__, 'enqueue_scripts' ) );
	}

	public static function enqueue_scripts() {

		$script = CoursePress_Core::$plugin_lib_url . 'scripts/CoursePress.js';

		wp_enqueue_script( 'coursepress_object', $script, array( 'jquery' ), CoursePress_Core::$version );

		// Create a dummy editor to by used by the CoursePress JS object
		ob_start();
		wp_editor( 'CONTENT', 'EDITORID', array( 'wpautop' => false) );
		$dummy_editor = ob_get_clean();

		wp_localize_script( 'coursepress_object', '_coursepress', array(
			'_dummy_editor' => $dummy_editor,
		) );

	}
}