<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

/**
 * Framework page settings
 */
$settings = array(
	'header_title' => __( 'Document', 'pressapps-document' ),
	'menu_title'   => __( 'Document', 'pressapps-document' ),
	'menu_type'    => 'add_submenu_page',
	'menu_slug'    => 'pressapps-document',
	'ajax_save'    => false,
);


/**
 * sections and fields option
 * @var array
 */
$options = array();

/*
 *  Styling options tab and fields settings
 */
$options[] = array(
	'name'   => 'general',
	'title'  => __( 'General', 'pressapps-document' ),
	'icon'   => 'fa fa-cogs',
	'fields' => array(
		array(
			'title'   => __( 'Reorder', 'pressapps-document' ),
			'id'      => 'reorder',
			'type'    => 'switcher',
			'default' => false
		),
		array(
			'title'   => __( 'Voting', 'pressapps-document' ),
			'id'      => 'voting',
			'type'    => 'radio',
			'options' => array(
				'0' => __( 'Disabled', 'pressapps-document' ),
				'1' => __( 'Public Voting', 'pressapps-document' ),
				'2' => __( 'Logged In Users Only', 'pressapps-document' )
			),
			'default' => '0',
		),
		array(
			'title'      => __( 'Dislike Button', 'pressapps-document' ),
			'id'         => 'dislike_btn',
			'type'       => 'switcher',
			'default'    => true,
			'dependency' => array( 'pado_voting_0', '!=', 'true' )
		),
		array(
			'title'   => __( 'Vote Icon', 'pressapps-document' ),
			'id'      => 'icon',
			'type'    => 'switcher',
			'default' => false
		),
		array(
			'id'         => 'vote_up',
			'type'       => 'icon',
			'title'      => __( 'Vote Up Icon', 'pressapps-document' ),
			'default'    => 'si-thumbs-up2',
			'dependency' => array( 'pado_icon', '==', 'true' )
		),
		array(
			'id'         => 'vote_down',
			'type'       => 'icon',
			'title'      => __( 'Vote Down Icon', 'pressapps-document' ),
			'default'    => 'si-thumbs-down2',
			'dependency' => array( 'pado_icon', '==', 'true' )
		),
		array(
			'id'           => 'vote_reset_all',
			'type'         => 'button',
			'title'        => __( 'Reset All Votes', 'pressapps-document' ),
			'button_title' => __( 'Reset All Votes', 'pressapps-document' ),
			'dependency'   => array( 'pado_voting_0', '!=', 'true' )
		),
		array(
			'id'      => 'color',
			'type'    => 'color_picker',
			'title'   => __( 'Color', 'pressapps-document' ),
			'default' => '#03A9F4',
		),
		array(
			'id'    => 'custom_css',
			'type'  => 'textarea',
			'title' => __( 'Custom CSS', 'pressapps-document' ),
		),
	),
);

SkeletFramework::instance( $settings, $options );
