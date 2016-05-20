<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://pressapps.co
 * @since      1.0.0
 *
 * @package    Pressapps_Document
 * @subpackage Pressapps_Document/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pressapps_Document
 * @subpackage Pressapps_Document/public
 * @author     PressApps
 */
class Pressapps_Document_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$skelet             = new Skelet("pado");
		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		global $skelet_path;
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pressapps-document-public.css', array(), $this->version, 'all' );
		wp_register_style( 'sk-icons', $skelet_path["uri"] .'/assets/css/sk-icons.css', array(), '1.0.0', 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pressapps-document-public.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->plugin_name , "PADO",
			array (
				'base_url'     => esc_url( home_url() ),
			)
		);

	}

    /**
     * Registers all shortcodes at once
     */
    public function register_shortcodes() {
    	/**
    	 * Register shortcode eg:
    	 * add_shortcode( 'voting_butons', array( $this, 'shortcode_voting_butons' ) );
    	 */
	    add_shortcode( 'document',          array( $this, 'shortcode_document' ) );
	    add_shortcode( 'pado_document',     array( $this, 'shortcode_document' ) );
	    add_shortcode( 'pado_documents',    array( $this, 'shortcode_cat_documents' ) );

    }

	/**
	 * Add the Shortcode for the document part with the following options
	 *
	 * @param array $atts
	 * @return string
	 */
	public function shortcode_document( $atts = array() ) {

		return $this->get_display_document( $atts );
	}

	protected function get_display_document( $args = array() ) {
		global $pressapps_document_data, $pado;

		$default = array (
			'category'      => -1,
			'top_offset'    => 30,
			'offset'        => 30,
			'sidebar_width' => 30,
			'template'		=> 'default',
			'counter' 		=> 0,
		    'language'      => '' // for WPML support - will allow user to specify a language to be shown and default is empty
		);

		$args = shortcode_atts( $default, $args );

		if ( $pado->get('reorder') == 1 ) {
			$qry_args = array(
				'post_type'     => 'document',
				'numberposts'   => -1,
				'orderby'       => 'menu_order',
				'order'         => 'ASC',
			);
		} else {
			$qry_args = array(
				'post_type'     => 'document',
				'numberposts'   => -1,
			);
		}

		if( isset( $args['category'] ) && $args['category'] != -1 ) {
			$qry_args['tax_query']   = array(
				array (
				'taxonomy'  => 'document_category',
				'field'     => 'id',
				'terms'     => $args['category'],
				),
			);
			if ( $pado->get('reorder') == 1 ) {
				$pressapps_terms    = get_terms('document_category',array(
					'parent'      => $args['category'],
					'orderby'       => 'term_group',
					'order'         => 'ASC'
				));
			} else {
				$pressapps_terms    = get_terms('document_category',array(
					'parent'      => $args['category'],
				));
			}
		} else {
			if ( $pado->get('reorder') == 1 ) {
				$pressapps_terms    = get_terms('document_category' ,array(
					'orderby'       => 'term_group',
					'order'         => 'ASC',
					'parent'		=> '0',
				) );
			} else {
				$pressapps_terms = get_terms('document_category' ,array(
					'parent'		=> '0',
				) );
			}
		}

		if ( count( $pressapps_terms ) > 0 ) {
			foreach( $pressapps_terms as $term ) {
				$pressapps_terms_documents[$term->term_id] = get_posts(array_merge($qry_args,
					array('tax_query'     => array(
						array(
							'taxonomy'  => 'document_category',
							'field'     => 'id',
							'terms'     => $term->term_id,
						)
					)
					)));

			}

			$pressapps_document_data = array(
				'dispaly_terms' => true,
				'terms'         => $pressapps_terms,
				'document'      => $pressapps_terms_documents,
				'language'      => $args['language'] // attribute that was passed on shortcode for WPML support
			);

		} else {

			$pressapps_documents = get_posts($qry_args);

			$pressapps_document_data = array(
				'dispaly_terms' => false,
				'document'      => $pressapps_documents,
				'language'      => $args['language'] // attribute that was passed on shortcode for WPML support
			);
		}

		$filename = plugin_dir_path( __FILE__ ) . 'partials/pressapps-document-' . $args['template'] . '.php';

		ob_start();
		include_once $filename;


		if( is_admin() ) {
			wp_enqueue_style( $this->plugin_name . '_admin', plugin_dir_url( __FILE__ ) . '../admin/css/pressapps-document-admin.css', array(), $this->version, 'all' );
		}

		wp_enqueue_style( 'sk-icons' );
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );

		wp_reset_query();
		return ob_get_clean();

	}

	/**
	 *
	 * Disply the document HTML generated based on the template file of document.
	 *
	 * @param array $args
	 */
	public function the_display_document( $args = array() ) {
		echo $this->get_display_document($args);
	}


	public function shortcode_cat_documents( $atts = array() ) {
		return $this->get_display_cat_documents( $atts );
	}

	protected function get_display_cat_documents( $atts ) {
		global $pressapps_document_data, $pado;

		$default = array (
			'columns'      => 3,
		);

		$atts = shortcode_atts( $default, $atts );

		$args = array (
			'hide_empty'        => false,
			'number'            => '',
			'fields'            => 'all',
			'slug'              => '',
			'parent'            => '0',
		);

		if ( $pado->get('reorder') == 1 ) {
			$args['orderby']    = 'term_group';
			$args['order']      = 'ASC';
		}

		$terms = get_terms('document_category', $args);

		$filename = plugin_dir_path( __FILE__ ) . "partials/pressapps-document-category.php";

		ob_start();
		include $filename;


		if( is_admin() ) {
			wp_enqueue_style( $this->plugin_name . '_admin', plugin_dir_url( __FILE__ ) . '../admin/css/pressapps-document-admin.css', array(), $this->version, 'all' );
		}

		wp_enqueue_style( 'sk-icons' );
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );

		return ob_get_clean();

	}

	public function the_display_documents( $atts = array() ) {
		echo $this->get_display_documents($atts);
	}

	/**
	 * Post format Icon
	 * @return string
	 */
	public function document_icon() {
		if (get_post_format() == 'video') {
			return 'si-film4';
		} elseif (get_post_format() == 'image') {
			return 'si-image2';
		} elseif (get_post_format() == 'link') {
			return 'si-link2';
		} elseif (get_post_format() == 'audio') {
			return 'si-music5';
		} elseif (get_post_format() == 'quote') {
			return 'si-quotes-right';
		} elseif (get_post_format() == 'chat') {
			return 'si-bubble2';
		} elseif (get_post_format() == 'gallery') {
			return 'si-grid6';
		} elseif (get_post_format() == 'status') {
			return 'si-bubble9';
		} else {
			return 'si-file-text2';
		}
	}

	/*-----------------------------------------------------------------------------------*/
	/* Voting */
	/*-----------------------------------------------------------------------------------*/

	public function docs_votes( $is_ajax = false ) {

		global $post, $pado;
		$votes_like         = (int) get_post_meta($post->ID, '_votes_likes', true);
		$votes_dislike      = (int) get_post_meta($post->ID, '_votes_dislikes', true);
		$voted_like         = sprintf(_n('%s person found this helpful', '%s people found this helpful', $votes_like, 'pressapps-document'), $votes_like);
		$voted_dislike      = sprintf(_n('%s person did not find this helpful', '%s people did not find this helpful', $votes_dislike, 'pressapps-document'), $votes_dislike);
		$vote_like_link     = __("I found this helpful", 'pressapps-document');
		$vote_dislike_link  = __("I did not find this helpful", 'pressapps-document');
		$cookie_vote_count  = '';

		if ( $pado->get('icon') != '' ) {
			$like_icon      = sprintf('<i class="%s"></i> ', $pado->get('vote_up') != '' ? $pado->get('vote_up') : 'ski-hand-like' );
			if ( $pado->get( 'dislike_btn' ) ){
				$dislike_icon   = sprintf('<i class="%s"></i> ', $pado->get('vote_down') != '' ? $pado->get('vote_down') : 'ski-hand-unlike' );
			}
		} else {
			$like_icon      = '';
			$dislike_icon   = '';
		}

		if(isset($_COOKIE['vote_count'])){
			$cookie_vote_count = @unserialize(base64_decode($_COOKIE['vote_count']));
		}

		if(!is_array($cookie_vote_count) && isset($cookie_vote_count)){
			$cookie_vote_count = array();
		}

		echo (($is_ajax)?'':'<div class="pado-votes">');

		if ( is_user_logged_in() || $pado->get('voting') == 1 ) :

			if(is_user_logged_in())
				$vote_count = (array) get_user_meta(get_current_user_id(), 'vote_count', true);
			else
				$vote_count = $cookie_vote_count;

			if ( !in_array( $post->ID, $vote_count ) ) :

				echo '<p data-toggle="tooltip" title="' . $vote_like_link .     '" class="pado-likes"><a class="pado-like-btn" href="javascript:" post_id="'  . $post->ID . '">' . $like_icon .'<span class="count">' . $votes_like . '</span></a></p>';

				if ( $pado->get( 'dislike_btn' ) ):
					echo '<p data-toggle="tooltip" title="' . $vote_dislike_link .  '" class="pado-dislikes"><a class="pado-dislike-btn" href="javascript:" post_id="' . $post->ID . '">' . $dislike_icon . '<span class="count">' . $votes_dislike . '</span></a></p>';
				endif;
			else :
				// already voted
				echo '<p data-toggle="tooltip" title="' . $voted_like .     '" class="pado-likes">' . $like_icon .'<span class="count">' . $votes_like . '</span></p> ';

				if ( $pado->get( 'dislike_btn' ) ):
					echo '<p data-toggle="tooltip" title="' . $voted_dislike .  '" class="pado-dislikes">' . $dislike_icon . '<span class="count">' . $votes_dislike . '</span></p> ';
				endif;
			endif;

		else :
			// not logged in
			echo '<p data-toggle="tooltip" title="' . $voted_like .     '" class="pado-likes">' . $like_icon .'<span class="count">' . $votes_like . '</span></p> ';

			if ( $pado->get( 'dislike_btn' ) ):
				echo '<p data-toggle="tooltip" title="' . $voted_dislike .  '" class="pado-dislikes">' . $dislike_icon . '<span class="count">' . $votes_dislike . '</span></p> ';
			endif;
		endif;

		echo ( ($is_ajax) ? '' : '</div>' );

	}

	public function docs_vote() {
		global $post, $pado;

		if (is_user_logged_in()) {

			$vote_count = (array) get_user_meta(get_current_user_id(), 'vote_count', true);

			if (isset( $_GET['vote_like'] ) && $_GET['vote_like']>0) :

				$post_id = (int) $_GET['vote_like'];
				$the_post = get_post($post_id);

				if ($the_post && !in_array( $post_id, $vote_count )) :
					$vote_count[] = $post_id;
					update_user_meta(get_current_user_id(), 'vote_count', $vote_count);
					$post_votes = (int) get_post_meta($post_id, '_votes_likes', true);
					$post_votes++;
					update_post_meta($post_id, '_votes_likes', $post_votes);
					$post = get_post($post_id);
					$this->docs_votes(true);
					die('');
				endif;

			elseif (isset( $_GET['vote_dislike'] ) && $_GET['vote_dislike']>0) :

				$post_id = (int) $_GET['vote_dislike'];
				$the_post = get_post($post_id);

				if ($the_post && !in_array( $post_id, $vote_count )) :
					$vote_count[] = $post_id;
					update_user_meta(get_current_user_id(), 'vote_count', $vote_count);
					$post_votes = (int) get_post_meta($post_id, '_votes_dislikes', true);
					$post_votes++;
					update_post_meta($post_id, '_votes_dislikes', $post_votes);
					$post = get_post($post_id);
					$this->docs_votes(true);
					die('');

				endif;

			endif;

		} elseif ( !is_user_logged_in() && $pado->get('voting') == 1 ) {

			// ADD VOTING FOR NON LOGGED IN USERS USING COOKIE TO STOP REPEAT VOTING ON AN ARTICLE
			$vote_count = '';

			if( isset( $_COOKIE['vote_count'] ) ) {
				$vote_count = @unserialize(base64_decode($_COOKIE['vote_count']));
			}

			if( !is_array($vote_count) && isset($vote_count) ) {
				$vote_count = array();
			}

			if ( isset( $_GET['vote_like'] ) && $_GET['vote_like'] > 0 ) :

				$post_id = (int) $_GET['vote_like'];
				$the_post = get_post($post_id);

				if ( $the_post && !in_array( $post_id, $vote_count ) ) :
					$vote_count[] = $post_id;
					$_COOKIE['vote_count']  = base64_encode(serialize($vote_count));
					setcookie('vote_count', $_COOKIE['vote_count'] , time()+(10*365*24*60*60),'/');
					$post_votes = (int) get_post_meta($post_id, '_votes_likes', true);
					$post_votes++;
					update_post_meta($post_id, '_votes_likes', $post_votes);
					$post = get_post($post_id);
					$this->docs_votes(true);
					die('');
				endif;

			elseif (isset( $_GET['vote_dislike'] ) && $_GET['vote_dislike'] > 0 ) :

				$post_id = (int) $_GET['vote_dislike'];
				$the_post = get_post($post_id);

				if ( $the_post && !in_array( $post_id, $vote_count ) ) :
					$vote_count[] = $post_id;
					$_COOKIE['vote_count']  = base64_encode(serialize($vote_count));
					setcookie('vote_count', $_COOKIE['vote_count'] , time()+(10*365*24*60*60),'/');
					$post_votes = (int) get_post_meta($post_id, '_votes_dislikes', true);
					$post_votes++;
					update_post_meta($post_id, '_votes_dislikes', $post_votes);
					$post = get_post($post_id);
					$this->docs_votes(true);
					die('');

				endif;

			endif;

		} elseif ( !is_user_logged_in() && $pado->get('voting') == 2 ) {

			return;

		}

	}

	/**
	 * Add inline styles in header, set in options settings
	 *
	 * @return callback custom css
	 *
	 */
	public function dynamic_styles() {

		global $pado;
		$css = '';

		$color = $pado->get('color');

		if ( !empty( $color ) ) {
			$color = sanitize_text_field( $pado->get('color') );
			$css .= '.pado-default .sidebar_doc_title a, .pado-default .sidebar_doc_title a:visited, .pado-section-heading, .pado-back-top a:hover, .pado-sharing-link:hover i { color: ' . $color . "}\n" ;
			$css .= '.pado-default .sidebar_doc_title:hover, .pado-default .pado-section-heading:before, .pado-default .sidebar_doc_active { background-color: ' . $color . "}\n" ;
			// Light template
			$css .= '.pado-light #pado-sidebar .sidebar_cat_title:hover a, .pado-light #pado-sidebar .open_arrow .pado_sidebar_cat_title, .pado-light .pado-section-heading a:hover { color: ' . $color . "}\n" ;
			$css .= '.pado-light .sidebar_doc_title:hover, .pado-light .sidebar_doc_active { border-color: ' . $color . "}\n" ;
			$css .= '.pado-light .pado-section-heading { border-bottom: solid 1px ' . $color . ' }';
		}

		// Custom CSS
		if ( $pado->get( 'custom_css' ) ) {
			$css .= $pado->get( 'custom_css' );
		}

		wp_add_inline_style( $this->plugin_name, wp_kses( $css, array( '\"', "\'" ) ) );

	}
}
