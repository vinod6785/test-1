<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) { ?>
	<ul class="pado-columns-<?php echo $atts['columns']; ?>">
		<?php
		foreach ( $terms as $term ) {
		?>
			<li>
				<h2><?php echo $term->name; ?></h2>
				<div class="pado-description"><?php echo $term->description; ?></div>
			</li>
		<?php
		}
		?>
	</ul>

<?php } ?>