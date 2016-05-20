<?php
global $pressapps_document_data, $post, $pado;

if ( count( $pressapps_document_data['document'] ) == 0 ) {
    _e( 'No Document Found', 'pressapps-document' );

    return;
}
?>
<script type="text/javascript">
    var pado_top_offset = <?php echo (int) $args['top_offset']; ?>;
    var pado_offset = <?php echo (int) $args['offset']; ?>;
    var pado_sidebar_width = <?php echo (int) $args['sidebar_width']; ?>;
</script>
<div id="pado-main" class="pado-default<?php echo( $args['counter'] ? ' pado-counter' : '' ); ?>">
    <div id="pado-sidebar">

        <?php

        if ( isset( $pressapps_document_data['terms'] ) ) {

            $c = 0;
            $p = 0;

            foreach ( $pressapps_document_data['terms'] as $terms ) {
                if ( count( $pressapps_document_data['document'][ $terms->term_id ] ) > 0 ) {
                    $c ++;
                    ?>
                    <ul>
                        <li class="sidebar_cat_title pado-cat-<?php echo $terms->term_id; ?>">
                            <i class="si-list"></i><a class="pado_sidebar_cat_title" href="#cat-<?php echo $c; ?>"><?php echo $terms->name; ?></a>
                        </li>

                        <?php
                        foreach ( $pressapps_document_data['document'][ $terms->term_id ] as $document ) {
                            $p++;

                            // if post language is not same with shortcode attribute will skip post
                            /*
							if ( Pressapps_Document_Helper::wpml_is_not_language( $pressapps_document_data['language'] ) ) {
								continue;
							}
							*/
                            $has_term = wp_get_object_terms( $document->ID, 'document_category', array( 'fields' => 'ids' ) );
                            $sidebar_heading_class = empty( $has_term ) ? 'sidebar_doc_title pado-document-' . $p . ' pado-post-nocat' : 'sidebar_doc_title pado-document-' . $p;
                            ?>

                            <li class="<?php echo esc_attr( $sidebar_heading_class ); ?>">
                                <a href="#document-<?php echo $p; ?>">
                                    <i class="<?php echo $this->document_icon(); ?>"></i> <?php echo $document->post_title; ?>
                                </a>
                            </li>


                            <?php
                        } ?>
                    </ul>

                    <?php
                }
            }
        } else {
            ?>
            <ul>
                <?php
                $p = 0;
                foreach ( $pressapps_document_data['document'] as $document ) {
                    $p ++;
                    // if post language is not same with shortcode attribute will skip post
                    /*
					if ( Pressapps_Document_Helper::wpml_is_not_language( $pressapps_document_data['language'] ) ) {
						continue;
					}
					*/
                    $has_term = wp_get_object_terms( $document->ID, 'document_category', array( 'fields' => 'ids' ) );
                    $sidebar_heading_class = empty( $has_term ) ? 'sidebar_doc_title pado-document-' . $p . ' pado-post-nocat' : 'sidebar_doc_title pado-document-' . $p;
                    ?>

                    <li class="<?php echo esc_attr( $sidebar_heading_class );?>">
                        <a href="#document-<?php echo $p; ?>">
                            <i class="<?php echo $this->document_icon(); ?>"></i> <?php echo $document->post_title; ?>
                        </a>
                    </li>

                    <?php
                }
                ?>
            </ul>
            <?php
        } ?>

    </div>

    <div id="pado-content">
        <?php
        if ( isset( $pressapps_document_data['terms'] ) ) {
            ?>
            <?php
            $c = 0;
            $p = 0;
            foreach ( $pressapps_document_data['terms'] as $terms ) {
                if ( count( $pressapps_document_data['document'][ $terms->term_id ] ) > 0 ) {
                    $c ++;
                    ?>
                    <div id="pado-cat-<?php echo $terms->term_id; ?>" class="pado-section-enter">
                        <h2 class="pado-section-heading" id="cat-<?php echo $c; ?>"><?php echo $terms->name; ?></h2>
                        <?php
                        foreach ( $pressapps_document_data['document'][ $terms->term_id ] as $document ) {
                            $p ++;
                            // if post language is not same with shortcode attribute will skip post
                            /*
	                        if ( Pressapps_Document_Helper::wpml_is_not_language( $pressapps_document_data['language'] ) ) {
		                        continue;
	                        }
                            */
                            $has_term = wp_get_object_terms( $document->ID, 'document_category', array( 'fields' => 'ids' ) );
                            $post_heading_class = empty( $has_term ) ? 'pado-post-heading pado-post-nocat' : 'pado-post-heading';
                            ?>
                            <article id="document-<?php echo $p; ?>" data-count="<?php echo $p; ?>" class="document type-pressapps_document status-publish clearfix">
                                <h3 class="<?php echo esc_attr( $post_heading_class ); ?>">
                                    <a href="#document-<?php echo $p; ?>" class="pado-sharing-link" title="<?php _e( 'Link to this page section', 'pressapps-document' ); ?>">
                                        <i class="si-link2"></i>
                                    </a>
                                    <a name="<?php echo $document->ID; ?>"></a><?php echo $document->post_title; ?>
                                </h3>
                                <?php if ( has_post_thumbnail( $document->ID ) ) { ?>
                                    <div class="document-featured">
                                        <?php echo get_the_post_thumbnail( $document->ID ); ?>
                                    </div>
                                <?php } ?>
                                <div class="document-content">
                                    <?php echo $document->post_content; ?>
                                </div>
                                <?php
                                if ( $pado->get( 'voting' ) != 0 ) {
                                    $this->docs_votes();
                                }
                                ?>
                                <p class="pado-back-top">
                                    <a href="#top">
                                        <i class="si-arrow-up4"></i> <?php _e( 'Back To Top', 'pressapps-document' ); ?>
                                    </a>
                                </p>
                            </article>
                            <?php
                        } ?>
                    </div>
                <?php }
            }
            ?>
            <?php

        } else {
            $p = 0;
            ?>
            <span class="pado-single-cat"></span>
            <?php
            foreach ( $pressapps_document_data['document'] as $document ) {
                $p ++;
                // if post language is not same with shortcode attribute will skip post
                /*
	            if ( Pressapps_Document_Helper::wpml_is_not_language( $pressapps_document_data['language'] ) ) {
		            continue;
	            }
                */
                $has_term = wp_get_object_terms( $document->ID, 'document_category', array( 'fields' => 'ids' ) );
                $post_heading_class = empty( $has_term ) ? 'pado-post-heading pado-post-nocat' : 'pado-post-heading';
                ?>
                <article id="document-<?php echo $p; ?>" class="document type-pressapps_document status-publish clearfix">
                    <h3 class="<?php echo esc_attr( $post_heading_class ); ?>">
                        <a href="#document-<?php echo $p; ?>" class="pado-sharing-link" title="<?php _e( 'Link to this page section', 'pressapps-document' ); ?>">
                            <i class="si-link3"></i>
                        </a>
                        <a name="<?php echo $document->ID; ?>"></a><?php echo $document->post_title; ?>
                    </h3>
                    <?php if ( has_post_thumbnail( $document->ID ) ) { ?>
                        <div class="document-featured">
                            <?php echo get_the_post_thumbnail( $document->ID ); ?>
                        </div>
                    <?php } ?>
                    <div class="document-content">
                        <?php echo $document->post_content; ?>
                    </div>
                    <?php
                    if ( $pado->get( 'voting' ) != 0 ) {
                        $this->docs_votes();
                    }
                    ?>
                    <p class="pado-back-top">
                        <a href="#top">
                            <i class="si-arrow-up4"></i> <?php _e( 'Back To Top', 'pressapps-document' ); ?>
                        </a>
                    </p>
                </article>
                <?php
            }
        }
        ?>
    </div>

</div>
