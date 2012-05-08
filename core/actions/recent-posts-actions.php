<?php

add_action( 'business_recent_posts_element', 'business_recent_posts_element_content' );

function business_recent_posts_element_content() {

	global $wp_query;
	$args = array_merge( $wp_query->query, array( 'showposts' => 4 ) );
	query_posts( $args );
?>
<div class="row">
	<div id="recent_posts_wrap" class="twelve columns">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<div class="three columns">
		
			<h2 class="posts_title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
	
			<?php
				if ( has_post_thumbnail()) {
 		 			echo '<div class="featured-image">';
 		 			echo '<a href="' . get_permalink($post->ID) . '" >';
 		 			the_post_thumbnail();
  					echo '</a>';
  					echo '</div>';
				}
			?>	
			
			<?php the_excerpt(); ?>
	
		</div>
	
	<?php endwhile; ?>
		
	<?php else : ?>

		<h2>Not Found</h2>

	<?php endif; ?>
	
	</div>
</div>


<?php
wp_reset_query();
}



?>