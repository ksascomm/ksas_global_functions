<?php
/*
Plugin Name: KSAS Global Functions
Plugin URI: https://github.com/ksascomm/plugin_global_functions
Description: This plugin should be network activated. Provides functions to change "Posts" labels to "News", removes unnecessary classes from navigation menus, sets up walker for sidebar menus, links to Web Services in admin toolbar, sets available blocks, and removes unwanted widgets, 
Version: 2.1
Author: KSAS Communications
Author URI: mailto:ksasweb@jhu.edu
License: GPL2
*/

/*****************TABLE OF CONTENTS***************
	1.0 Remove Unwanted Widgets
	2.0 Change Posts Labels to News
	3.0 Theme Functions
		3.1 Obfuscate Email address email_munge($string);
		3.2 Create Title for <head> section
	4.0 Navigation and Menus
		4.1 Remove CSS classes from menu
		4.2 Walker class for tertiary/sidebar links
	5.0 Global Shortcodes
		5.1 Custom Menu
	6.0 Editor
		6.1 Restrict majority of blocks to non-admins
		6.2 Disable/Clean Inline Styles
		6.3 Allow uploads on custom post types
	7.0 Login Screen
	8.0 Toolbar Changes
		8.1 Remove comments node
		8.2 Add links to sites.krieger documentation
	9.0 Images Without Alt Text
		9.1 Add css box around alt-less images

/*****************1.0 REMOVE UNWANTED WIDGETS*****************************/

	function unregister_default_wp_widgets() {
		unregister_widget('WP_Widget_Pages');
		unregister_widget('WP_Widget_Calendar');
		unregister_widget('WP_Widget_Archives');
		unregister_widget('WP_Widget_Meta');
		unregister_widget('WP_Widget_Categories');
		unregister_widget('WP_Widget_Recent_Comments');
		unregister_widget('WP_Widget_RSS');
		unregister_widget('WP_Widget_Tag_Cloud');
	}
	add_action('widgets_init', 'unregister_default_wp_widgets', 1);

/*****************2.0 CHANGE POSTS LABELS TO NEWS*****************************/
	function change_post_menu_label() {
		global $menu;
		global $submenu;
		$menu[5][0] = 'News';
		$submenu['edit.php'][5][0] = 'News';
		$submenu['edit.php'][10][0] = 'Add News';
		$submenu['edit.php'][16][0] = 'News Tags';
		echo '';
	}
	function change_post_object_label() {
		global $wp_post_types;
		$labels = &$wp_post_types['post']->labels;
		$labels->name = 'News';
		$labels->singular_name = 'News';
		$labels->add_new = 'Add News';
		$labels->add_new_item = 'Add News';
		$labels->edit_item = 'Edit News';
		$labels->new_item = 'News';
		$labels->view_item = 'View News';
		$labels->search_items = 'Search News';
		$labels->not_found = 'No News found';
		$labels->not_found_in_trash = 'No News found in Trash';
	}
	add_action( 'init', 'change_post_object_label' );
	add_action( 'admin_menu', 'change_post_menu_label' );


/*****************3.0 THEME FUNCTIONS*****************************/
	//***3.1 Obfuscate Email Address
		function email_munge($string) {
			$ascii_string = '';
			foreach (str_split($string) as $char) 
			{ 
				$ascii_string .= '&#' . ord($char) . ';'; 
			}
			return $ascii_string;
		}
	
	//***3.2 Create Title for <head> section
		function create_page_title() {
			if ( is_front_page() )  { 
				$page_title = bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
			} 
			elseif ( is_home() ) { // blog page
				$page_title = single_post_title();
				$page_title .= print(' | ');
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
			} 
			elseif ( is_category() ) { 
				$page_title = single_cat_title();
				$page_title .= print(' | ');
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
			}
			elseif ( is_archive() ) { 
				$page_title = the_archive_title();
				$page_title .= print(' | ');
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
			}			
			elseif (is_single() ) { 
				$page_title = single_post_title(); 
				$page_title .= print(' | ');
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
			}
			elseif (is_page() ) { 
				$page_title = single_post_title();
				$page_title .= print(' | ');
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
			}
			elseif (is_404()) {
				$page_title = print('Page Not Found'); 
				$page_title .= print(' | ');
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
			}
			elseif (is_tax('bbtype')) {
				$page_title = single_tag_title();
				$page_title .= print(' | ');
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
			}
			else { 
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
				} 
			return $page_title;
		}

/*******************4.0 NAVIGATION & MENU FUNCTIONS & HELPERS******************/
	//***4.1 remove unneccessary classes for navigation menus
		function ksasaca_css_attributes_filter($var) {
			 $newnavclasses = is_array($var) ? array_intersect($var, array(
	                'current_page_item',
	                'current_page_parent',
	                'current_page_ancestor',
	                'first',
	                'last',
	                'vertical',
	                'horizontal',
	                'children',
	                'logo',
	                'external',
	                'hide',
	                'hide-for-small',
	                'show-for-small',
	                'purple',
	                'green',
	                'yellow',
	                'blue',
	                'orange',
	                'home',
	                'exclude'
			 )) : '';
			 return $newnavclasses;
		}
		add_filter('nav_menu_css_class', 'ksasaca_css_attributes_filter', 100, 1);
		add_filter('page_css_class', 'ksasaca_css_attributes_filter', 100, 1);	
		
	//***4.2 Menu Walker for Tertiary/Sidebar links. Limits sidebar links to just that parent section.
		add_filter( 'wp_nav_menu_objects', 'submenu_limit', 10, 2 );

		function submenu_limit( $items, $args ) {

		    if ( empty($args->submenu) )
		        return $items;

		    $filter_object_list = wp_filter_object_list( $items, array( 'title' => $args->submenu ), 'and', 'ID' );
		    $parent_id = array_pop( $filter_object_list );
		    $children  = submenu_get_children_ids( $parent_id, $items );

		    foreach ( $items as $key => $item ) {

		        if ( ! in_array( $item->ID, $children ) )
		            unset($items[$key]);
		    }

		    return $items;
		}

		function submenu_get_children_ids( $id, $items ) {

		    $ids = wp_filter_object_list( $items, array( 'menu_item_parent' => $id ), 'and', 'ID' );

		    foreach ( $ids as $id ) {

		        $ids = array_merge( $ids, submenu_get_children_ids( $id, $items ) );
		    }

		    return $ids;
		}
		

/*******************5.0 SHORTCODES & WYSIWYG******************/
	//***5.1 Custom Menu Shortcode
	function ksas_custom_menu_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( 
			array(  
				'menu'            => '', 
				'container'       => 'div', 
				'container_class' => '', 
				'container_id'    => '', 
				'menu_class'      => 'menu', 
				'menu_id'         => '',
				'echo'            => true,
				'fallback_cb'     => 'wp_page_menu',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'depth'           => 0,
				'walker'          => '',
				'submenu'		  => '',	
				'theme_location'  => ''
			), $atts )
		); 
	 
		return wp_nav_menu( 
			array( 
				'menu'            => $menu, 
				'container'       => $container, 
				'container_class' => $container_class, 
				'container_id'    => $container_id, 
				'menu_class'      => $menu_class, 
				'menu_id'         => $menu_id,
				'echo'            => false,
				'fallback_cb'     => $fallback_cb,
				'before'          => $before,
				'after'           => $after,
				'link_before'     => $link_before,
				'link_after'      => $link_after,
				'depth'           => $depth,
				'walker'          => $walker,
				'submenu'		  => $submenu,
				'theme_location'  => $theme_location
			) 
		);
	}
	
	add_shortcode( "custommenu", "ksas_custom_menu_shortcode" );

/*******************6.0 Editor******************/	
	//6.1 Restrict majority of blocks to non-admins
	add_filter( 'allowed_block_types', 'restrict_blocks');

	function restrict_blocks( $allowed_blocks ) {
	 if( !is_super_admin() ) 
	     $allowed_blocks = array(
	         'core/block',
	         'core/image',
	         'core/paragraph',
	         'core/heading',
	         'core/list',
	         'core/quote',
	         'core/file',
	         'core/button',
	         'core/separator',
	         'core-embed/youtube',
	         'core-embed/vimeo',
	         'core/shortcode',
	         'formidable/simple-form',
	         'ksas-callouts/ksas-callout-block'
	     );
	     return $allowed_blocks;
	 }	

	//6.2 Disable/Clean Inline Styles 	

		add_filter( 'the_content', 'clean_post_content' );
		function clean_post_content($content) {

		    // Remove inline styling
		    //$content = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $content);
		    $content = preg_replace('/(<[span>]+) style=".*?"/i', '$1', $content);
			$content = preg_replace("/font-family\:.+?;/i", "", $content);
			$content = preg_replace("/color\:.+?;/i", "", $content);

		    // Remove font tag
		    $content = preg_replace('/<font[^>]+>/', '', $content);

		    // Remove empty tags
		    $post_cleaners = array('<p></p>' => '', '<p> </p>' => '', '<p>&nbsp;</p>' => '', '<span></span>' => '', '<span> </span>' => '', '<span>&nbsp;</span>' => '', '<span>' => '', '</span>' => '', '<font>' => '', '</font>' => '');
		    $content = strtr($content, $post_cleaners);

		    return $content;
		}
	
	//6.3 Allow file uploads on custom post types
	function ecpt_export_ui_scripts()
	{
	    global $ecpt_options;
	?> 
	    <script type = "text/javascript" >
	    jQuery(document).ready(function($) {

	        if ($('.form-table .ecpt_upload_field').length > 0) {
	            // Media Uploader
	            window.formfield = '';

	            $('.ecpt_upload_image_button').live('click', function() {
	                var send_attachment_bkp = wp.media.editor.send.attachment;
	                var button = $(this);

	                wp.media.editor.send.attachment = function(props, attachment) {

	                    $(button).prev().prev().attr('src', attachment.url);
	                    $(button).prev().val(attachment.url);

	                    wp.media.editor.send.attachment = send_attachment_bkp;
	                }

	                wp.media.editor.open(button);

	                return false;
	            });
	            window.original_send_to_editor = window.send_to_editor;
	            window.send_to_editor = function(html) {
	                if (window.formfield) {
	                    imgurl = $('a', '<div>' + html + '</div>').attr('href');
	                    window.formfield.val(imgurl);
	                    tb_remove();
	                } else {
	                    window.original_send_to_editor(html);
	                }
	                window.formfield = '';
	                window.imagefield = false;
	            }
	        }
	        // add new repeatable field
	        $(".ecpt_add_new_field").on('click', function() {
	            var field = $(this).closest('td').find("div.ecpt_repeatable_wrapper:last").clone(true);
	            var fieldLocation = $(this).closest('td').find('div.ecpt_repeatable_wrapper:last');
	            // get the hidden field that has the name value
	            var name_field = $("input.ecpt_repeatable_field_name", ".ecpt_field_type_repeatable:first");
	            // set the base of the new field name
	            var name = $(name_field).attr("id");
	            // set the new field val to blank
	            $('input', field).val("");

	            // set up a count var
	            var count = 0;
	            $('.ecpt_repeatable_field').each(function() {
	                count = count + 1;
	            });
	            name = name + '[' + count + ']';
	            $('input', field).attr("name", name);
	            $('input', field).attr("id", name);
	            field.insertAfter(fieldLocation, $(this).closest('td'));

	            return false;
	        });

	        // add new repeatable upload field
	        $(".ecpt_add_new_upload_field").on('click', function() {
	            var container = $(this).closest('tr');
	            var field = $(this).closest('td').find("div.ecpt_repeatable_upload_wrapper:last").clone(true);
	            var fieldLocation = $(this).closest('td').find('div.ecpt_repeatable_upload_wrapper:last');
	            // get the hidden field that has the name value
	            var name_field = $("input.ecpt_repeatable_upload_field_name", container);
	            // set the base of the new field name
	            var name = $(name_field).attr("id");
	            // set the new field val to blank
	            $('input[type="text"]', field).val("");

	            // set up a count var
	            var count = 0;
	            $('.ecpt_repeatable_upload_field', container).each(function() {
	                count = count + 1;
	            });
	            name = name + '[' + count + ']';
	            $('input', field).attr("name", name);
	            $('input', field).attr("id", name);
	            field.insertAfter(fieldLocation, $(this).closest('td'));

	            return false;
	        });

	        // remove repeatable field
	        $('.ecpt_remove_repeatable').on('click', function(e) {
	            e.preventDefault();
	            var field = $(this).parent();
	            $('input', field).val("");
	            field.remove();
	            return false;
	        });

	    }); </script>
	            
	    <?php }
			if ((isset($_GET['post']) && (isset($_GET['action']) && $_GET['action'] == 'edit')) || (strstr($_SERVER['REQUEST_URI'], 'wp-admin/post-new.php'))) {
			    add_action('admin_head', 'ecpt_export_ui_scripts');
			}
/*******************7.0 Login Screen******************/	

//show screen if user is attempting to login by bypassing JHED
function wps_login_message( $message ) {
    if ( empty($message) ){
        return "<p class='message'><strong>NOTE:</strong> We are currently reconfiguring the JHED Authentication plugin. <br><br>Please <strong>DO NOT</strong> login with your JHED or click <em>Lost Your Password?</em> in the fields below. You will be able to log in with your JHED shortly. <br><br>If you need immediate edits to your website, please use our <a href='http://sites.krieger.jhu.edu/forms/request-service/'>web service request form</a> or email <a href='mailto:ksasweb@jhu.edu'>ksasweb@jhu.edu</a>.<br><br>Thank you for your patience. </p>";
    } else {
        return $message;
    }
}
add_filter( 'login_message', 'wps_login_message' );


/*******************8.0 Toolbar Changes******************/	

	// 8.1 Remove comments node
	function my_admin_bar_render() {
	    global $wp_admin_bar;
	    $wp_admin_bar->remove_menu('comments');
	}
	add_action( 'wp_before_admin_bar_render', 'my_admin_bar_render' );

	// 8.2 Add links to sites.krieger documentation

	function custom_toolbar_link($wp_admin_bar) {
		$args = array(
			'id' => 'webservices',
			'title' => __('<img src="'.get_bloginfo('wpurl').'/wp-content/themes/ksas_cross_divisional_18/dist/assets/images/shield.png" style="height:20px;vertical-align:middle;margin-right:5px" alt="JHU Shield" title="KSAS Web Services" />Krieger Web Services &#9662;' ),
			'href' => 'http://sites.krieger.jhu.edu', 
			'meta' => array(
				'target' => '_blank',
				'class' => 'webservices', 
				'title' => 'KSAS Web Services'
				)
		);
		$wp_admin_bar->add_node($args);

	// first child link 
		
		$args = array(
			'id' => 'website-guidelines',
			'title' => 'Website Guidelines', 
			'href' => 'https://sites.krieger.jhu.edu/guides/',
			'parent' => 'webservices',
			'meta' => array(
				'target' => '_blank',
				'class' => 'website-guidelines', 
				'title' => 'Website Guidelines'
				)
		);
		$wp_admin_bar->add_node($args);

	// second child link
		$args = array(
			'id' => 'writing-web',
			'title' => 'Writing for the Web', 
			'href' => 'https://sites.krieger.jhu.edu/guides/web-writing/',
			'parent' => 'webservices', 
			'meta' => array(
				'target' => '_blank',
				'class' => 'guides', 
				'title' => 'Writing for the Web'
				)
		);
		$wp_admin_bar->add_node($args);

	// third child link
		$args = array(
			'id' => 'request-support',
			'title' => 'Request Support', 
			'href' => 'https://sites.krieger.jhu.edu/request-service/',
			'parent' => 'webservices', 
			'meta' => array(
				'target' => '_blank',
				'class' => 'request-support', 
				'title' => 'Request Support'
				)
		);
		$wp_admin_bar->add_node($args);	

	}

	add_action('admin_bar_menu', 'custom_toolbar_link', 999);

/*******************9.0 Images Without Alt Text******************/
	//9.1 Add css box around alt-less images
	function add_css_head() {
	   if ( is_user_logged_in() ) : ?>
	      <style>
				img[alt=""], img:not([alt]) {
					border: 4px red dashed !important;
				}
				#slb_viewer_wrap .slb_theme_slb_baseline .slb_template_tag_item_content> img:not([alt]) {
					border: none !important;
				}
	      </style>
	   <?php endif;
	   if ( !is_user_logged_in() ) : ?>
	   	<style>
	   		img.wpa-image-missing-alt.size-full.none {
	   			display: none;
	   		}
	   	</style>
	   <?php endif;
	}
	add_action('wp_head', 'add_css_head');

	?>