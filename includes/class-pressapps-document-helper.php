<?php
/**
 * Helper Class
 *
 * @link       http://pressapps.co
 * @since      1.0.0
 *
 * @package    Pressapps_Document_Helper
 * @subpackage Pressapps_Document_Helper/includes
 */

/**
 * Helper Class
 *
 * This class defines all functions that act as helper.
 *
 * @since      1.0.0
 * @package    Pressapps_Document_Helper
 * @subpackage Pressapps_Document_Helper/includes
 * @author     PressApps
 */
class Pressapps_Document_Helper {

	/**
	 * Check if WPML is enabled and the current post is the language being passed on shortcode attribute.
	 *
	 * @param $shortcode_lang - shortcode attribute that will be passed
	 *
	 * @return bool
	 */
	public static function wpml_is_not_language( $shortcode_lang ) {
		global $post;

		if ( function_exists( 'icl_object_id' ) && ! empty( $shortcode_lang ) ) {

			if ( function_exists( 'wpml_get_language_information' ) ) {

				//get post language information
				$lang_info = wpml_get_language_information( $post->ID );

				//will return the statement and will check if true or false
				return $lang_info['language_code'] !== strtolower( $shortcode_lang );

			} else {
				return false;
			}

		} else {
			return false;
		}
	}

}
