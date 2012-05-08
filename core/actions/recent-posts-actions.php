<?php

add_action( 'business_recent_posts_element', 'business_recent_posts_element_content' );

function business_recent_posts_element_content() {

	global $wp_query;
	$args = array_merge( $wp_query->query, array( 'showposts' => 4, 'ignore_sticky_posts' => 1  ));
	query_posts( $args );
?>
<div class="container">
<div class="row">
	<h4 style="color:white;">Recent Blog Posts</h4><br />
	<div id="recent_posts_wrap">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<div class="three columns">
		
			<h5><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h5>
			<h6 style="color:white;"><?php the_time( 'd/m/y');?> - <?php the_category(', ') ?> - <?php comments_popup_link( __('No Comments', 'business' ), __('1 Comment', 'business' ), __('% Comments' , 'business' )); //need a filer here ?></h6>
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
</div>

<?php
wp_reset_query();
}



?>