<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Global skelet shortcodes variable
 */
  global $skelet_shortcodes;

/**
 * Feedback Survey Shortcode options and settings
 */
$skelet_shortcodes[]     = sk_shortcode_apply_prefix(array(
    'title'      => 'DOCUMENT',
    'shortcodes' => array(
        array(
            'name'      => 'document',
            'title'     => __( 'Insert Document',  'pressapps-document' ),
            'fields'    => array (
                array (
                    'id'        => 'template',
                    'type'      => 'select',
                    'title'     => __( 'Template',         'pressapps-document' ),
                    'options'   => array (
                        'default'   => __( 'Default',      'pressapps-document' ),
                        'light'     => __( 'Light', 'pressapps-document' ),
                    ),
                    'default'   => 'default',
                ),
                array(
                    'id'             => 'category',
                    'type'           => 'select',
                    'title'          => __( 'Document Category', 'pressapps-document' ),
                    'options'        => 'categories',
                    'query_args'     => array(
                        'type'         => 'document',
                        'taxonomy'     => 'document_category',
                    ),
                    'default_option' => 'All Categories',
                ),
                array (
                    'id'        => 'offset',
                    'type'      => 'number',
                    'title'     => __( 'Sidebar & Article Offset',    'pressapps-document' ),
                    'default'   => '30',
                ),
                array (
                    'id'        => 'top_offset',
                    'type'      => 'number',
                    'title'     => __( 'Scroll to Top Offset',    'pressapps-document' ),
                    'default'   => '30',
                ),
                array (
                    'id'        => 'sidebar_width',
                    'type'      => 'number',
                    'title'     => __( 'Sidebar Width %',    'pressapps-document' ),
                    'default'   => '30',
                ),
                array (
                    'id'        => 'counter',
                    'type'      => 'switcher',
                    'title'     => __( 'Counter',    'pressapps-document' ),
                    'default'   => '1',
                ),
                /*
                array (
                    'id'        => 'language',
                    'type'      => 'text',
                    'title'     => __( 'Language Code', 'pressapps-document' ),
                    'info'      => __( 'e.g. de_DE', 'pressapps-document' ),
                ),
                */
            ),
        ),
    ),
));