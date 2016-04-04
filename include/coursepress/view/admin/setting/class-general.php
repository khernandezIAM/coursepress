<?php

require_once dirname( __FILE__ ) . '/class-settings.php';

class CoursePress_View_Admin_Setting_General extends CoursePress_View_Admin_Setting_Setting {

	public static function init() {

		add_filter( 'coursepress_settings_tabs', array( __CLASS__, 'add_tabs' ) );
		add_action( 'coursepress_settings_process_general', array( __CLASS__, 'process_form' ), 10, 2 );
		add_filter( 'coursepress_settings_render_tab_general', array( __CLASS__, 'return_content' ), 10, 3 );
	}

	public static function add_tabs( $tabs ) {

		self::$slug = 'general';

		$tabs[ self::$slug ] = array(
			'title' => __( 'General Settings', 'CP_TD' ),
			'description' => __( 'Configure the general settings for CoursePress.', 'CP_TD' ),
			'order' => 0,// first tab
		);

		return $tabs;

	}

	public static function return_content( $content, $slug, $tab ) {

		$my_course_prefix = __( 'my-course', 'CP_TD' );
		$my_course_prefix = sanitize_text_field( CoursePress_Core::get_setting( 'slugs/course', 'courses' ) ) . '/'. $my_course_prefix;
		$page_dropdowns = array();

		$pages_args = array(
			'selected' => CoursePress_Core::get_setting( 'pages/enrollment', 0 ),
			'echo' => 0,
			'show_option_none' => __( 'Use virtual page', 'CP_TD' ),
			'option_none_value' => 0,
			'name' => 'coursepress_settings[pages][enrollment]',
		);
		$page_dropdowns['enrollment'] = wp_dropdown_pages( $pages_args );

		$content = '<!-- SLUGS -->
			<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Slugs', 'CP_TD' ) . '</span></h3>
			<p class="description">' . sprintf( __( 'A slug is a few words that describe a post or a page. Slugs are usually a URL friendly version of the post title ( which has been automatically generated by WordPress ), but a slug can be anything you like. Slugs are meant to be used with %s as they help describe what the content at the URL is. Post slug substitutes the %s placeholder in a custom permalink structure.', 'CP_TD' ), '<a href="options-permalink.php">permalinks</a>', '<strong>"%posttitle%"</strong>' ) . '</p>';

		$content .= self::page_start( $slug, $tab );
		$content .= self::table_start();

		$home_url = trailingslashit( esc_url( home_url() ) );
		$my_course_url = $home_url . trailingslashit( esc_html( $my_course_prefix ) );

		$content .= self::row(
			__( 'Courses Slug', 'CP_TD' ),
			esc_html( $home_url ) . '&nbsp;<input type="text" name="coursepress_settings[slugs][course]" id="course_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/course', 'courses' ) ).'" />&nbsp;/',
			esc_html( 'Your course URL will look like: ', 'CP_TD' ) . esc_html( $home_url ) . esc_html( CoursePress_Core::get_setting( 'slugs/course', 'courses' ) ) . esc_html( '/my-course/', 'CP_TD' )
		);

		$content .= self::row(
			__( 'Course Category Slug', 'CP_TD' ),
			esc_html( $home_url . trailingslashit( esc_html( CoursePress_Core::get_setting( 'slugs/course', 'courses' ) ) ) ) . '&nbsp;<input type="text" name="coursepress_settings[slugs][category]" id="category_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/category', 'course_category' ) ) . '" />&nbsp;/',
			esc_html__( 'Your course category URL will look like: ', 'CP_TD' ) . $home_url . esc_html( CoursePress_Core::get_setting( 'slugs/course', 'courses' ) . '/' . CoursePress_Core::get_setting( 'slugs/category', 'course_category' ) ) . esc_html__( '/your-category/', 'CP_TD' )
		);

		$content .= self::row(
			__( 'Units Slug', 'CP_TD' ),
			$my_course_url . '&nbsp;<input type="text" name="coursepress_settings[slugs][units]" id="units_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/units', 'units' ) ) . '" />&nbsp;/'
		);

		$content .= self::row(
			__( 'Course Notifications Slug', 'CP_TD' ),
			$my_course_url . '&nbsp;<input type="text" name="coursepress_settings[slugs][notifications]" id="notifications_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/notifications', 'notifications' ) ) . '" />&nbsp;/'
		);

		$content .= self::row(
			__( 'Course Discussions Slug', 'CP_TD' ),
			$my_course_url . '&nbsp;<input type="text" name="coursepress_settings[slugs][discussions]" id="discussions_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/discussions', 'discussion' ) ) . '" />&nbsp;/'
		);

		$content .= self::row(
			__( 'Course New Discussion Slug', 'CP_TD' ),
			$my_course_url . trailingslashit( esc_attr( CoursePress_Core::get_setting( 'slugs/discussions', 'discussion' ) ) ) .'&nbsp;<input type="text" name="coursepress_settings[slugs][discussions_new]" id="discussions_new_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/discussions_new', 'add_new_discussion' ) ) . '" />&nbsp;/'
		);

		$content .= self::row(
			__( 'Course Grades Slug', 'CP_TD' ),
			$my_course_url . '&nbsp;<input type="text" name="coursepress_settings[slugs][grades]" id="grades_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/grades', 'grades' ) ) . '" />&nbsp;/'
		);

		$content .= self::row(
			__( 'Course Workbook Slug', 'CP_TD' ),
			trailingslashit( esc_url( home_url() ) ) . trailingslashit( esc_html( $my_course_prefix ) ) . '&nbsp;<input type="text" name="coursepress_settings[slugs][workbook]" id="workbook_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/workbook', 'workbook' ) ) . '" />&nbsp;/'
		);

		$content .= self::row(
			__( 'Enrollment Process Slug', 'CP_TD' ),
			trailingslashit( esc_url( home_url() ) ) . '&nbsp;<input type="text" name="coursepress_settings[slugs][enrollment]" id="enrollment_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/enrollment', 'enrollment_process' ) ) . '" />&nbsp;/'
		);

		$content .= self::row(
			__( 'Enrollment Process Page', 'CP_TD' ),
			$page_dropdowns['enrollment'],
			sprintf( __( 'Select page where you have %s shortcode or any other set of %s. Please note that slug for the page set above will not be used if "Use virtual page" is not selected.', 'CP_TD' ), '<strong>[cp_pages page="enrollment_process"]</strong>', '<a target="_blank" href="' . admin_url( 'admin.php?page=' . $_GET['page'] . '&tab=shortcodes' ) . '">' . __( 'shortcodes', 'CP_TD' ) . '</a>' )
		);

		$content .= self::row(
			__( 'Instructor Profile Slug', 'CP_TD' ),
			trailingslashit( esc_url( home_url() ) ) . '&nbsp;<input type="text" name="coursepress_settings[slugs][instructor_profile]" id="instructor_profile_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/instructor_profile', 'instructor' ) ) . '" />&nbsp;/'
		);

		if ( function_exists( 'messaging_init' ) ) {

			$content .= self::row(
				__( 'Messaging: Inbox Slug', 'CP_TD' ),
				trailingslashit( esc_url( home_url() ) ) . '&nbsp;<input type="text" name="coursepress_settings[slugs][inbox]" id="inbox_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/inbox', 'student-inbox' ) ) . '" />&nbsp;/'
			);

			$content .= self::row(
				__( 'Sent Messages Slug', 'CP_TD' ).
				trailingslashit( esc_url( home_url() ) ) . '&nbsp;<input type="text" name="coursepress_settings[slugs][sent_messages]" id="sent_messages" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/sent_messages', 'student-sent-messages' ) ) . '" />&nbsp;/'
			);

			$content .= self::row(
				__( 'New Messages Slug', 'CP_TD' ),
				trailingslashit( esc_url( home_url() ) ) . '&nbsp;<input type="text" name="coursepress_settings[slugs][new_messages]" id="new_messages_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/new_messages', 'student-new-message' ) ) . '" />&nbsp;/'
			);
		}

		$content .= self::table_end();

		$content .= '
				<!-- THEME MENU ITEMS -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Theme Menu Items', 'CP_TD' ) . '</span></h3>';
		$content .= self::table_start();

		$checked = cp_is_true( CoursePress_Core::get_setting( 'general/show_coursepress_menu', 1 ) ) ? 'checked' : '';
		$content .= self::row(
			__( 'Display Menu Items', 'CP_TD' ),
			'<input type="checkbox" name="coursepress_settings[general][show_coursepress_menu]" ' . $checked  . ' />',
			__( 'Attach default CoursePress menu items ( Courses, Student Dashboard, Log Out ) to the <strong>Primary Menu</strong>. Items can also be added from Appearance &gt; Menus and the CoursePress panel.', 'CP_TD' )
		);

		if ( current_user_can( 'manage_options' ) ) {
			$menu_error = true;
			$locations = get_theme_mod( 'nav_menu_locations' );
			if ( is_array( $locations ) ) {
				foreach ( $locations as $location => $value ) {
					if ( $value > 0 ) {
						$menu_error = false; // at least one is defined
					}
				}
			}
			if ( $menu_error ) {

				$content .= self::row(
					'&nbsp;',
					'<span class="settings-error">' . __( 'Please add at least one menu and select its theme location in order to show CoursePress menu items automatically.', 'CP_TD' ) . '</span>'
				);

			}
		}

		$content .= self::table_end();

		$content .= '
				<!-- LOGIN FORM -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Login Form', 'CP_TD' ) . '</span></h3>';
		$content .= self::table_start();

		$checked = cp_is_true( CoursePress_Core::get_setting( 'general/use_custom_login', 1 ) ) ? 'checked' : '';
		$content .= self::row(
			__( 'Use Custom Login Form', 'CP_TD' ),
			'<input type="checkbox" name="coursepress_settings[general][use_custom_login]" ' . $checked  . ' />',
			__( 'Uses a custom Login Form to keep students on the front-end of your site.', 'CP_TD' )
		);

		$content .= self::table_end();

		$content .= ' <!-- WP LOGING REDIRECTION -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'WordPress Login Redirect', 'CP_TD' ) . '</span></h3>';
		$content .= self::table_start();

		$checked = cp_is_true( CoursePress_Core::get_setting( 'general/redirect_after_login', 1 ) ) ? 'checked' : '';
		$content .= self::row(
			__( 'Redirect After Login', 'CP_TD' ),
			'<input type="checkbox" name="coursepress_settings[general][redirect_after_login]" ' . $checked  . ' />',
			__( 'Redirect students to their Dashboard upon login via wp-login form.', 'CP_TD' )
		);

		$content .= self::table_end();

		$content .= ' <!-- PRIVACY -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Privacy', 'CP_TD' ) . '</span></h3>';
		$content .= self::table_start();

		$checked = cp_is_true( CoursePress_Core::get_setting( 'instructor/show_username', 1 ) ) ? 'checked' : '';
		$content .= self::row(
			__( 'Show Instructor Username in URL', 'CP_TD' ),
			'<input type="checkbox" name="coursepress_settings[instructor][show_username]" ' . $checked  . ' />',
			__( 'If checked, instructors username will be shown in the url. Otherwise, hashed (MD5) version will be shown.', 'CP_TD' )
		);

		$content .= self::table_end();

		$content .= '
				<!-- COURSE DETAILS PAGE -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Course Details Page', 'CP_TD' ) . '</span></h3>
				<p class="description">' . __( 'Media to use when viewing course details.', 'CP_TD' ) . '</p>';
		$content .= self::table_start();

		$selected_type = CoursePress_Core::get_setting( 'course/details_media_type', 'default' );
		$content .= self::row(
			__( 'Media Type', 'CP_TD' ),
			'<select name="coursepress_settings[course][details_media_type]" class="widefat" id="course_details_media_type"><option value="default" ' . selected( $selected_type, 'default', false ) .'>' . __( 'Priority Mode (default)', 'CP_TD' ) . '</option><option value="video" ' . selected( $selected_type, 'video', false ) .'>' . __( 'Featured Video', 'CP_TD' ) . '</option><option value="image" ' . selected( $selected_type, 'image', false ) .'>' . __( 'List Image', 'CP_TD' ) . '</option></select>',
			__( '"Priority" - Use the media type below, with the other type as a fallback.', 'CP_TD' )
		);

		$selected_priority = CoursePress_Core::get_setting( 'course/details_media_priority', 'default' );
		$content .= self::row(
			__( 'Priority', 'CP_TD' ),
			'<select name="coursepress_settings[course][details_media_priority]" class="widefat" id="course_details_media_priority"><option value="video" ' . selected( $selected_priority, 'video', false ) .'>' . __( 'Featured Video (image fallback)', 'CP_TD' ) . '</option><option value="image" ' . selected( $selected_priority, 'image', false ) .'>' . __( 'List Image (video fallback)', 'CP_TD' ) . '</option></select>',
			__( 'Example: Using "video", the featured video will be used if available. The listing image is a fallback.', 'CP_TD' )
		);
		$content .= self::table_end();

		$content .= '
				<!-- COURSE LISTINGS -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Course Listings', 'CP_TD' ) . '</span></h3>
				<p class="description">' . __( 'Media to use when viewing course listings (e.g. Courses page or Instructor page).', 'CP_TD' ) . '</p>';
		$content .= self::table_start();

		$selected_type = CoursePress_Core::get_setting( 'course/listing_media_type', 'default' );
		$content .= self::row(
			__( 'Media Type', 'CP_TD' ),
			'<select name="coursepress_settings[course][listing_media_type]" class="widefat" id="course_listing_media_type"><option value="default" ' . selected( $selected_type, 'default', false ) .'>' . __( 'Priority Mode (default)', 'CP_TD' ) . '</option><option value="video" ' . selected( $selected_type, 'video', false ) .'>' . __( 'Featured Video', 'CP_TD' ) . '</option><option value="image" ' . selected( $selected_type, 'image', false ) .'>' . __( 'List Image', 'CP_TD' ) . '</option></select>',
			__( '"Priority" - Use the media type below, with the other type as a fallback.', 'CP_TD' )
		);

		$selected_priority = CoursePress_Core::get_setting( 'course/listing_media_priority', 'default' );
		$content .= self::row(
			__( 'Priority', 'CP_TD' ),
			'<select name="coursepress_settings[course][listing_media_priority]" class="widefat" id="course_listing_media_priority"><option value="video" ' . selected( $selected_priority, 'video', false ) .'>' . __( 'Featured Video (image fallback)', 'CP_TD' ) . '</option><option value="image" ' . selected( $selected_priority, 'image', false ) .'>' . __( 'List Image (video fallback)', 'CP_TD' ) . '</option></select>',
			__( 'Example: Using "video", the featured video will be used if available. The listing image is a fallback.', 'CP_TD' )
		);

		$content .= self::table_end();

		$content .= '

				<!-- COURSE IMAGES -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Course Images', 'CP_TD' ) . '</span></h3>
				<p class="description">' . __( 'Size for (newly uploaded) course images.', 'CP_TD' ) . '</p>';
		$content .= self::table_start();
		$content .= self::row(
			__( 'Image Width', 'CP_TD' ),
			'<input type="text" name="coursepress_settings[course][image_width]" value="' . esc_attr( CoursePress_Core::get_setting( 'course/image_width', 235 ) ) . '"/>'
		);
		$content .= self::row(
			__( 'Image Height', 'CP_TD' ),
			'<input type="text" name="coursepress_settings[course][image_height]" value="' . esc_attr( CoursePress_Core::get_setting( 'course/image_height', 225 ) ) . '"/>'
		);
		$content .= self::table_end();

		$content .= '
				<!-- COURSE ORDER -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Course Order', 'CP_TD' ) . '</span></h3>
				<p class="description">' . __( 'Order of courses in admin and on front. If you choose "Post Order Number", you will have option to reorder courses from within the Courses admin page.', 'CP_TD' ) . '</p>';
		$content .= self::table_start();

		$selected_order = CoursePress_Core::get_setting( 'course/order_by', 'post_date' );
		$content .= self::row(
			__( 'Order by', 'CP_TD' ),
			' <select name="coursepress_settings[course][order_by]" class="widefat" id="course_order_by"><option value="post_date" ' . selected( $selected_order, 'post_date', false ) .'>' . __( 'Post Date', 'CP_TD' ) . '</option><option value="course_order" ' . selected( $selected_order, 'course_order', false ) .'>' . __( 'Post Order Number', 'CP_TD' ) . '</option></select>'
		);

		$selected_dir = CoursePress_Core::get_setting( 'course/order_by_direction', 'DESC' );
		$content .= self::row(
			__( 'Direction', 'CP_TD' ),
			'<select name="coursepress_settings[course][order_by_direction]" class="widefat" id="course_order_by_direction"><option value="DESC" ' . selected( $selected_dir, 'DESC', false ) .'>' . __( 'Descending', 'CP_TD' ) . '</option><option value="ASC" ' . selected( $selected_dir, 'ASC', false ) .'>' . __( 'Ascending', 'CP_TD' ) . '</option></select>'
		);

		$content .= self::table_end();

		$content .= '
				<!-- REPORTS -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Reports', 'CP_TD' ) . '</span></h3>
				<p class="description">' . __( 'Select font which will be used in the PDF reports.', 'CP_TD' ) . '</p>';
		$content .= self::table_start();

		$reports_font = CoursePress_Core::get_setting( 'reports/font', 'helvetica' );
		$reports_font = empty( $reports_font ) ? 'helvetica' : $reports_font;
		$fonts = CoursePress_Helper_PDF::fonts();
		$font_content = '<select name="coursepress_settings[reports][font]" class="widefat" id="course_order_by_direction">';
		foreach ( $fonts as $font_php => $font_name ) {
			if ( ! empty( $font_name ) ) {
				$font = str_replace( '.php', '', $font_php );
				$font_content .= sprintf(
					'<option value="%s" %s>%s</option>',
					esc_attr( $font ),
					selected( $reports_font, $font, false ),
					esc_html( $font_name )
				);
			}
		}
		$font_content .= ' </select>';
		$content .= self::row(
			__( 'Font', 'CP_TD' ),
			$font_content
		);

		$content .= self::table_end();

		$content .= '
				<!-- schema.org -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html . '</span></h3>';
		$content .= self::table_start();

		/**
		 * schema.org
		 */
		$content .= self::row(
			__( 'schema.org', 'CP_TD' ),
			sprintf(
				'<Label><input type="checkbox" name="coursepress_settings[general][add_structure_data]" %s /> %s</Label>',
				cp_is_true( CoursePress_Core::get_setting( 'general/add_structure_data', 1 ) ) ? 'checked' : '',
				esc_html__( 'Add microdata syntax.', 'CP_TD' )
			),
			esc_html__( 'Add structure data to courses.', 'CP_TD' )
		);

		$content .= self::table_end();

		return $content;

	}
}
