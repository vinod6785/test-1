<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://pressapps.co
 * @since      1.0.0
 *
 * @package    Pressapps_Document
 * @subpackage Pressapps_Document/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pressapps_Document
 * @subpackage Pressapps_Document/includes
 * @author     PressApps
 */
class Pressapps_Document {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pressapps_Document_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $options;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$skelet             = new Skelet("pado");
		$this->options      = $skelet->get("options");
		$this->plugin_name  = 'pressapps-document';
		$this->version      = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pressapps_Document_Loader. Orchestrates the hooks of the plugin.
	 * - Pressapps_Document_i18n. Defines internationalization functionality.
	 * - Pressapps_Document_Admin. Defines all hooks for the admin area.
	 * - Pressapps_Document_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pressapps-document-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pressapps-document-i18n.php';

		/**
		 * The class responsible for defining all functions that act as a helper.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pressapps-document-helper.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pressapps-document-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-pressapps-document-public.php';




		$this->loader = new Pressapps_Document_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pressapps_Document_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Pressapps_Document_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Pressapps_Document_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts',                     $plugin_admin,  'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts',                     $plugin_admin,  'enqueue_scripts' );
        $this->loader->add_action( 'plugin_row_meta',                           $plugin_admin,  'row_links', 10, 2 );

		$this->loader->add_action( 'manage_document_category_custom_column',    $plugin_admin,  'manage_document_category_custom_column', 10, 3 );
		$this->loader->add_action( 'manage_document_posts_custom_column' ,      $plugin_admin,  'manage_document_custom_column', 10, 2 );
		$this->loader->add_action( 'restrict_manage_posts',                     $plugin_admin,  'document_restrict_manage_posts' );
		$this->loader->add_action( 'document_category_edit_form_fields',        $plugin_admin,  'document_category_edit_form_fields');
		$this->loader->add_action( 'init',                                      $plugin_admin,  'post_type_support' );
		$this->loader->add_action( 'init',                                      $plugin_admin,  'register_cpt' );
		$this->loader->add_action( 'init',                                      $plugin_admin,  'register_taxonomy' );
		$this->loader->add_action( 'admin_init', 								$plugin_admin,  'admin_init_action' );

		// Ajax for ordering post
		$this->loader->add_action( 'wp_ajax_pado_order_update_posts', 			$plugin_admin,  'order_save_order' );
		$this->loader->add_action( 'wp_ajax_nopriv_pado_order_update_posts', 	$plugin_admin,  'order_save_order' );
		$this->loader->add_action( 'wp_ajax_pado_order_update_taxonomies', 		$plugin_admin,  'order_save_taxonomies_order' );
		$this->loader->add_action( 'wp_ajax_nopriv_pado_order_update_taxonomies', $plugin_admin,'order_save_taxonomies_order' );
		$this->loader->add_action( 'wp_ajax_pado_reset_vote_admin', 			$plugin_admin,  'reset_vote_admin' );
		$this->loader->add_action( 'wp_ajax_nopriv_pado_reset_vote_admin', 		$plugin_admin,  'reset_vote_admin' );
		$this->loader->add_action( 'wp_ajax_pado_reset_vote_all_admin', 		$plugin_admin,  'reset_vote_all_admin' );
		$this->loader->add_action( 'wp_ajax_nopriv_pado_reset_vote_all_admin', 	$plugin_admin,  'reset_vote_all_admin' );

		$this->loader->add_filter( 'manage_edit-document_category_columns',     $plugin_admin,  'manage_edit_document_category_columns' );
		$this->loader->add_filter( 'manage_edit-document_columns',              $plugin_admin,  'manage_edit_document_columns' );
		$this->loader->add_filter( 'manage_edit-document_sortable_columns',     $plugin_admin,  'sortable_column' );
		$this->loader->add_filter( 'pre_get_posts',                             $plugin_admin,  'custom_orderby' );

		$this->loader->add_action( 'plugin_action_links_' . $this->get_plugin_name()."/".$this->get_plugin_name().".php", $plugin_admin, 'settings_link' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Pressapps_Document_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'dynamic_styles');
		$this->loader->add_action( 'init',				 $plugin_public, 'register_shortcodes' );
		$this->loader->add_action( 'init',               $plugin_public, 'docs_vote' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pressapps_Document_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
