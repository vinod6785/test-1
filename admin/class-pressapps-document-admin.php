<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://pressapps.co
 * @since      1.0.0
 *
 * @package    Pressapps_Document
 * @subpackage Pressapps_Document/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pressapps_Document
 * @subpackage Pressapps_Document/admin
 * @author     PressApps
 */
class Pressapps_Document_Admin {

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
	
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		
		$skelet             = new Skelet("pado");
		$this->options      = $skelet->get("options");
		$this->plugin_name  = $plugin_name;
		$this->version      = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		global $pado;
		$current_screen = get_current_screen();

		if ( ( $current_screen->post_type === 'document' && $current_screen->taxonomy !== 'document_tags' ) && $pado->get( 'reorder' ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pressapps-document-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//wp_register_script( $this->plugin_name,                 plugin_dir_url( __FILE__ ) . 'js/pressapps-document-admin.js',              array( 'jquery' ), $this->version, false );
		global $pado;
		$current_screen = get_current_screen();

		if ( $current_screen->post_type === 'document' || $current_screen->base === 'pressapps_page_pressapps-document' ) {

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pressapps-document-admin.js', array(
				'jquery',
				'jquery-ui-sortable'
			), $this->version, false );

			//will pass value for reorder over the admin page for checking
			wp_localize_script( $this->plugin_name, 'PADO_admin',
				array(
					'reorder'                => $pado->get( 'reorder' ),
					'reset_confirmation'     => __( 'Are you sure you want to reset votes for this article?', 'pressapps-document' ),
					'reset_all_confirmation' => __( 'Are you sure you want to reset votes for all articles?', 'pressapps-document' ),
					'reset_success'          => __( 'Success! Votes are now reset', 'pressapps-document' )
				)
			);
		}

	}

    /**
     * Adds a link to the plugin settings page
     */
    public function settings_link( $links ) {

        $settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=' . $this->plugin_name ), __( 'Settings', 'pressapps-document' ) );

        array_unshift( $links, $settings_link );

        return $links;

    }

    /**
     * Adds links to the plugin links row
     */
    public function row_links( $links, $file ) {

        if ( strpos( $file, $this->plugin_name . '.php' ) !== false ) {

            $link = '<a href="http://pressapps.co/help/" target="_blank">' . __( 'Help', 'pressapps-document' ) . '</a>';

            array_push( $links, $link );

        }

        return $links;

    }

	public function register_cpt() {
		register_post_type( 'document',array(
			'description'           => __('Document Posts','pressapps-document'),
			'labels'                => array(
				'name'                  => __('Documents'                     ,'pressapps-document'),
				'singular_name'         => __('Document'                      ,'pressapps-document'),
				'add_new'               => __('Add New'                       ,'pressapps-document'),
				'add_new_item'          => __('Add New Document'              ,'pressapps-document'),
				'edit_item'             => __('Edit Document'                 ,'pressapps-document'),
				'new_item'              => __('New Document'                  ,'pressapps-document'),
				'view_item'             => __('View Document'                 ,'pressapps-document'),
				'search_items'          => __('Search Documents'              ,'pressapps-document'),
				'not_found'             => __('No Documents found'            ,'pressapps-document'),
				'not_found_in_trash'    => __('No Documents found in Trash'   ,'pressapps-document'),
				'all_items'             => __('All Documents'                 ,'pressapps-document'),
			),
			'public'                => true,
			'menu_position'         => 5,
			'rewrite'               => array('slug' => 'document'),
			'supports'              => array('title','editor', 'thumbnail'),
			'public'                => true,
			'show_ui'               => true,
			'publicly_queryable'    => true,
			'exclude_from_search'   => false
		));
	}

	public function register_taxonomy() {
		register_taxonomy( 'document_category',array( 'document' ),array(
			'hierarchical'  => false,
			'labels'        => array(
				'name'              => __( 'Categories'             ,'pressapps-document'),
				'singular_name'     => __( 'Category'               ,'pressapps-document'),
				'search_items'      => __( 'Search Categories'      ,'pressapps-document'),
				'all_items'         => __( 'All Categories'         ,'pressapps-document'),
				'parent_item'       => __( 'Parent Category'        ,'pressapps-document'),
				'parent_item_colon' => __( 'Parent Category:'       ,'pressapps-document'),
				'edit_item'         => __( 'Edit Category'          ,'pressapps-document'),
				'update_item'       => __( 'Update Category'        ,'pressapps-document'),
				'add_new_item'      => __( 'Add New Category'       ,'pressapps-document'),
				'new_item_name'     => __( 'New Category Name'      ,'pressapps-document'),
				'popular_items'     => NULL,
				'menu_name'         => __( 'Categories'             ,'pressapps-document')
			),
			'show_ui'       => true,
			'public'        => true,
			'query_var'     => true,
			'hierarchical'  => true,
			'rewrite'       => array( 'slug' => 'document_category' )
		));
	}

	/**
	 *
	 * Add the Additional column Values for the document_category Taxonomy
	 *
	 * @param string $out
	 * @param string $column
	 * @param int $term_id
	 * @return string
	 */
	public function manage_document_category_custom_column( $out, $column, $term_id ) {
		switch( $column ) {
			case 'shortcode':
				$temp = '[pado_document category=' . $term_id . ']';
				return $temp;
				break;
		}
	}

	/**
	 *
	 * Add the Additional column Values for the document Post Type
	 *
	 * @global type $post
	 * @param string $column
	 */

	public function manage_document_custom_column( $column, $post_id ) {

		switch( $column ) {
			case 'category':
				$terms = wp_get_object_terms( $post_id  ,'document_category' );
				foreach ( $terms as $term ) {
					$temp  = " <a href=\"" . esc_url( admin_url( 'edit-tags.php?action=edit&taxonomy=document_category&tag_ID=' . $term->term_id . '&post_type=document' ) ) . "\" ";
					$temp .= " class=\"row-title\">{$term->name}</a><br/>";
					echo $temp;
				}
				break;
			case 'document_slug':
				$post_slug = get_post($post_id)->post_name;
				echo $post_slug;
				break;

			case 'likes':
				$likes = get_post_meta($post_id, '_votes_likes', true);
				echo  is_numeric($likes) ? esc_attr( $likes ) : 0;
				break;

			case 'dislikes':
				$dislikes = get_post_meta($post_id, '_votes_dislikes', true);
				echo is_numeric($dislikes) ? esc_attr( $dislikes ) : 0;
				break;

			case 'reset':
				echo '<a href="#" class="pado-reset-vote button" data-reset-nonce="' . wp_create_nonce( 'reset_vote_' . $post_id ) . '" data-post-id="' . esc_attr( $post_id ) . '">' . __( 'Reset', 'pressapps-document' ) . '</a>';
				break;
		}
	}

	/**
	 * Initiate functions for the admin page.
	 *
	 * @since    1.0.0
	 */
	public function admin_init_action() {
		global $pagenow, $pado;

		if ( isset( $_GET['page'] ) && isset( $_GET['settings-updated'] ) ) {
			if ( $_GET['page'] == 'document-options' && $_GET['settings-updated'] == 'true' ) {
				flush_rewrite_rules( true );
			}
		}


		if ( ! $pado->get( 'reorder' ) ) {
			return;
		}

		if ( $pagenow == 'edit.php' ) {
			if ( isset( $_GET['post_type'] ) && 'document' == $_GET['post_type'] ) {

				add_filter( 'pre_get_posts', array( $this, 'order_reorder_list' ) );
			}
		} elseif ( $pagenow == 'edit-tags.php' ) {
			if ( isset( $_GET['post_type'] ) && 'document' == $_GET['post_type'] ) {

				add_filter( 'get_terms_orderby', array( $this, 'order_reorder_taxonomies_list' ), 10, 2 );
			}
		}
	}


	public function sortable_column( $columns ) {
		$columns['likes']       = 'like';
		$columns['dislikes']    = 'dislike';
		return $columns;
	}

	public function custom_orderby(  $query ) {
		if( ! is_admin() )
			return;

		$orderby = $query->get( 'orderby');

		if( 'like' == $orderby ) {
			$query->set('meta_key','_votes_likes');
			$query->set('orderby','meta_value_num');
		} elseif( 'dislike' == $orderby ) {
			$query->set('meta_key','_votes_dislikes');
			$query->set('orderby','meta_value_num');
		}
	}

	/**
	 * Category Based Filtering options
	 *
	 * @global string $typenow
	 */

	public function document_restrict_manage_posts() {
		global $typenow;

		if( $typenow == 'document' ) {
			?>
			<select name="document_category">
				<option value="0"><?php _e( 'Select Category','pressapps-document' ); ?></option>
				<?php
				$categories = get_terms( 'document_category' );
				if ( count( $categories ) > 0 ) {
					foreach ( $categories as $cat ) {
						if( isset( $_GET['document_category'] ) && $_GET['document_category'] == $cat->slug ) {
							echo "<option value={$cat->slug} selected=\"selected\">{$cat->name}</option>";
						} else {
							echo "<option value={$cat->slug} >{$cat->name}</option>";
						}
					}
				}
				?>
			</select>
			<?php
		}

	}

	/**
	 * Shortcode field for the Edit Taxonomy Page
	 *
	 * @param string $taxonomy
	 */

	public function document_category_edit_form_fields( $taxonomy ) {
		$tag_id = $_GET['tag_ID'];
		?>
		<tr>
			<th scope="row" valign="top"><label for="shortcode"><?php _e('Shortcode','pressapps-document');?></label></th>
			<td>[pado_document category=<?php echo $tag_id; ?>]</td>
		</tr>
		<?php
	}

	// add post-formats support
	public function post_type_support() {
		add_post_type_support( 'document', 'post-formats' );
	}

	/**
	 * Add the Additional Columns For the document_category Taxonomy
	 *
	 * @param array $columns
	 * @return array
	 */
	public function manage_edit_document_category_columns( $columns ) {

		$new_columns['cb']          = $columns['cb'];
		$new_columns['name']        = $columns['name'];
		$new_columns['shortcode']   = __("Shortcode",'pressapps-document');
		$new_columns['slug']        = $columns['slug'];
		$new_columns['posts']       = $columns['posts'];

		return $new_columns;
	}

	/**
	 *
	 * Rename the Columns for the document post type and adding new Columns
	 *
	 * @param array $columns
	 * @return array
	 */

	public function manage_edit_document_columns( $columns ) {
		global $pado;

		$new_columns['cb']              = $columns['cb'];
		$new_columns['title']           = __('Title','pressapps-document');
		$new_columns['category']        = __('Categories','pressapps-document');
		$new_columns['document_slug']   = __("Slug",'pressapps-document');
		$new_columns['date']            = $columns['date'];
		if ( $pado->get( 'voting' ) >= 1 ) {
			$new_columns['likes']           = __("Likes", 'pressapps-document');
			if ( $pado->get( 'dislike_btn' ) ) {
				$new_columns['dislikes'] = __("Dislikes", 'pressapps-document');
			}
			$new_columns['reset']    		= __( 'Reset', 'pressapps-document' );
		}
		return $new_columns;
	}

	/**
	 * Reset all votes on the document CPT
	 */
	public function reset_vote_all_admin() {

		if ( is_user_logged_in() ) {
			delete_user_meta( get_current_user_id(), 'vote_count' );
		} elseif ( isset( $_COOKIE['vote_count'] ) ) {
			setcookie( 'vote_count', '', time() - 3600, '/' );
		}

		//get all document CPT based on IDs
		$args = array(
			'post_type'      => 'document',
			'posts_per_page' => - 1,
			'fields'         => 'ids'
		);

		$vote_queries  = get_posts( $args );
		$total_queries = count( $vote_queries );
		$vote          = 1;

		foreach ( $vote_queries as $post_id ) {
			if ( $vote >= $total_queries ) {
				echo json_encode( array( 'success' => 'true' ) );
			}
			//remove all meta that is attached to post
			delete_post_meta( $post_id, '_votes_likes' );
			delete_post_meta( $post_id, '_votes_dislikes' );
			$vote ++;
		}
		die;
	}

	/**
	 * Ajax Call for resetting votes in admin page
	 */
	public function reset_vote_admin() {
		$post_id = intval( $_REQUEST['post_id'] );

		//will check if nonce was sent was valid
		check_ajax_referer( 'reset_vote_' . $post_id, 'reset_nonce' );

		$post_likes_update    = update_post_meta( $post_id, '_votes_likes', 0 );
		$post_dislikes_update = update_post_meta( $post_id, '_votes_dislikes', 0 );

		$cookie_vote_count = '';
		if ( isset( $_COOKIE['vote_count'] ) ) {
			$cookie_vote_count = @unserialize( base64_decode( $_COOKIE['vote_count'] ) );
		}

		//will check if user is logged - for voting that was set for loggedin users
		if ( is_user_logged_in() ) {
			$vote_count = (array) get_user_meta( get_current_user_id(), 'vote_count', true );
		} else {
			$vote_count = $cookie_vote_count;
		}

		//will look for the post if it exist on vote_count either through $_COOKIE or get_user_meta()
		$post_vote_key = array_search( $post_id, $vote_count );

		if ( $post_vote_key ) {
			//if we are able to find the key and will remove it
			unset( $vote_count[ $post_vote_key ] );

			if ( is_user_logged_in() ) {
				update_user_meta( get_current_user_id(), 'vote_count', $vote_count );
			} elseif ( isset( $_COOKIE['vote_count'] ) ) {
				setcookie( 'vote_count', $vote_count, time() + ( 10 * 365 * 24 * 60 * 60 ), '/' );
			}
		}

		echo json_encode( compact( 'post_likes_update', 'post_dislikes_update', 'post_id' ) );
		die;
	}

	/**
	 * Attached to the pre_get_posts hook for the admin.
	 *
	 * @since    1.0.0
	 *
	 * @param $query
	 *
	 * @return mixed
	 */
	public function order_reorder_list( $query ) {
		if ( is_admin() && $query->query_vars['post_type'] === 'document') {
			$query->set( 'orderby', 'menu_order' );
			$query->set( 'order', 'ASC' );

			return $query;
		}
	}

	/**
	 * Filter hook for ordering taxonomy list.
	 *
	 * @since    1.0.0
	 *
	 * @param $orderby
	 * @param $args
	 *
	 * @return string
	 */
	public function order_reorder_taxonomies_list( $orderby, $args ) {
		$orderby = "t.term_group";

		return $orderby;
	}

	/**
	 * Attached to the pre_get_posts hook for the admin.
	 *
	 * @since    1.0.0
	 *
	 * @param $query
	 */
	public function pre_get_posts_action( $query ) {
		global $pado;

		if ( ( ! $pado->get( 'reorder' ) ) ) {
			return;
		}

		if ( ( is_admin() && $query->query_vars['post_type'] === 'document' ) &&
			( $query->is_post_type_archive( 'document' )
			  || $query->is_tax( 'document_category' )
			  || ( $query->is_search() && ( isset( $_REQUEST['post_type'] ) ? ( $_REQUEST['post_type'] == 'document' ) : false ) )
			) && $query->is_main_query()
		) {
			$query->set( 'orderby', 'menu_order' );
			$query->set( 'order', 'ASC' );
		}

	}

	function order_save_order() {

		global $wpdb;

		$action             = $_POST['action'];
		$posts_array        = $_POST['post'];
		$listing_counter    = 1;

		foreach ($posts_array as $post_id) {

			$wpdb->update(
				$wpdb->posts,
				array('menu_order'  => $listing_counter),
				array('ID'          => $post_id)
			);

			$listing_counter++;
		}

		die();
	}

	function order_save_taxonomies_order() {
		global $wpdb;

		$action             = $_POST['action'];
		$tags_array         = $_POST['tag'];
		$listing_counter    = 1;

		foreach ($tags_array as $tag_id) {

			$wpdb->update(
				$wpdb->terms,
				array('term_group'  => $listing_counter),
				array('term_id'     => $tag_id)
			);

			$listing_counter++;
		}

		die();
	}
}
