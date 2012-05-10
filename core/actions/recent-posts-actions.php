<?php

add_action( 'response_recent_posts_element', 'response_recent_posts_element_content' );

function response_recent_posts_element_content() {

	global $options, $themeslug, $wp_query, $custom_excerpt, $post;
	$custom_excerpt = 'recent';
	
	if (is_page()){
		$title = get_post_meta($post->ID, 'recent_posts_title' , true);;
		$toggle = get_post_meta($post->ID, 'recent_posts_title_toggle' , true);;
		$recent_posts_image = get_post_meta($post->ID, 'recent_posts_images_toggle' , true);;
	} else {
		$title = $options->get($themeslug.'_recent_posts_title');
		$toggle = $options->get($themeslug.'_recent_posts_title_toggle');
		$recent_posts_image = $options->get($themeslug.'_recent_posts_images_toggle');
	}
		
	$args = array_merge( $wp_query->query, array( 'showposts' => 4, 'ignore_sticky_posts' => 1  ));
	query_posts( $args );
?>
<div class="container">
<div class="row">
	<?php if ($toggle == '1' OR $toggle == 'on'): ?>
		<h4 style="color:white; margin-top: 10px; margin-bottom:15px; font-weight: bold;"><?php echo $title; ?></h4>
	<?php endif; ?>
	<div id="recent_posts_wrap">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<div id="recent-posts-container" class="three columns">
		
			<h5 style="font-size: 15px;color: #fff;"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h5>
			<h6 style="color:#646464;font-size: 11px;padding-bottom: 10px;"><?php the_time( 'd/m/y');?> - <?php the_category(', ') ?> - <?php comments_popup_link( __('No Comments', 'response' ), __('1 Comment', 'response' ), __('% Comments' , 'response' )); //need a filer here ?></h6>
			<?php
				if ( has_post_thumbnail() && $recent_posts_image == '1' OR has_post_thumbnail() && $recent_posts_image == 'on' ) {
 		 			echo '<div class="recent-posts-image">';
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