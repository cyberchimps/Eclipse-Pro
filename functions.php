<?php
/**
* Theme functions used by Eclipse.
*
* Authors: Tyler Cunningham, Trent Lapinski
* Copyright: © 2012
* {@link http://cyberchimps.com/ CyberChimps LLC}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package Eclipse.
* @since 1.0
*/


function url_filtered($fields){
	if(isset($fields['url']))
   		unset($fields['url']);
   	
  	return $fields;
}
add_filter('comment_form_default_fields', 'url_filtered');


/**
* Define global theme functions.
*/ 
	$themename = 'eclipse';
	$themenamefull = 'Eclipse Pro';
	$themeslug = 'ec';
	$root = get_template_directory_uri(); 
	$pagedocs = 'http://cyberchimps.com/question/using-the-eclipse-pro-page-options/';
	$sliderdocs = 'http://cyberchimps.com/question/eclipse-pro-content-slider/';

/**
* Basic theme setup.
*/ 
function ec_theme_setup() {
	global $content_width;
	if ( ! isset( $content_width ) ) $content_width = 608; //Set content width
	
	add_theme_support(
		'post-formats',
		array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat')
	);

	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 720, 240, true );
	add_theme_support('automatic-feed-links');
	add_editor_style();
}
add_action( 'after_setup_theme', 'ec_theme_setup' );

/**
* Redirect user to theme options page after activation.
*/ 
if ( is_admin() && isset($_GET['activated'] ) && $pagenow =="themes.php" ) {
	wp_redirect( 'themes.php?page=eclipse' );
}

/**
* Add link to theme options in Admin bar.
*/ 
function admin_link() {
	global $wp_admin_bar;

	$wp_admin_bar->add_menu( array( 'id' => 'eclipse', 'title' => 'Eclipse Pro Options', 'href' => admin_url('themes.php?page=eclipse')  ) ); 
}
add_action( 'admin_bar_menu', 'admin_link', 113 );

/**
* Custom markup for gallery posts in main blog index.
*/ 
function custom_gallery_post_format( $content ) {
	global $options, $themeslug, $post;
	$root = get_template_directory_uri(); 
	
	 ob_start(); 
		if ( has_post_thumbnail() && $featured_images == '1'  && !is_single()) {
 		 	echo '<div class="featured-image">';
 		 	echo '<a href="' . get_permalink($post->ID) . '" >';
 		 		the_post_thumbnail();
  			echo '</a>';
  			echo '</div>';
		}
		?>	
			<div class="row">
			<div class="three columns"><?php response_post_byline(); ?></div>
				<div class="entry nine columns">
					<h2 class="posts_title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
									
				<?php if (!is_single()): ?>
				<?php $images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
					if ( $images ) :
						$total_images = count( $images );
						$image = array_shift( $images );
						$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
				?>

				<figure class="gallery-thumb">
					<a href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
					<br /><br />
					This gallery contains <?php echo $total_images ; ?> images
					<?php endif;?>
				</figure><!-- .gallery-thumb -->
				<?php endif;?>
				
				<?php if (is_single()): ?>
					<?php the_content(); ?>
				<?php endif;?>
				
				<!--Begin @Core link pages hook-->
					<?php response_link_pages(); ?>
				<!--End @Core link pages hook-->
			
				<!--Begin @Core post edit link hook-->
					<?php response_edit_link(); ?>
				<!--End @Core post edit link hook-->
				</div><!--end entry-->
			</div><!--end row-->
			<?php	
		
		$content = ob_get_clean();	
		return $content; 
}
add_filter('response_post_formats_gallery_content', 'custom_gallery_post_format' ); 
	
/**
* Set custom post excerpt link text based on theme option.
*/ 
function new_excerpt_more($more) {

	global $themename, $themeslug, $options, $custom_excerpt, $post, $root;
    
    	if ($options->get($themeslug.'_excerpt_link_text') == '') {
    		$linktext = 'Continue Reading';
   		}
   		
   		elseif ($custom_excerpt == 'recent') {
    		$linktext = 'Continue Reading';
    	}
    	else {
    		$linktext = $options->get($themeslug.'_excerpt_link_text');
   		}

	return '&hellip;<div class="more-link"><br /><br /> <span class="continue-arrow"><img class="continue_img" src="'.$root.'/images/continue.png"></span><a href="'. get_permalink($post->ID) . '">  '.$linktext.'</a></div>';
}
add_filter('excerpt_more', 'new_excerpt_more');

/**
* Set custom post excerpt length based on theme option.
*/ 
function new_excerpt_length($length) {

	global $themename, $themeslug, $custom_excerpt, $options;
	
		if ($options->get($themeslug.'_excerpt_length') == '') {
    		$length = '55';
    	}
    	
    	elseif ($custom_excerpt == 'recent') {
    		$length = '15';
    	}
    	else {
    		$length = $options->get($themeslug.'_excerpt_length');
    	}
    	
	return $length;
}
add_filter('excerpt_length', 'new_excerpt_length');

/**
* Attach CSS3PIE behavior to elements
*/   
function render_ie_pie() { ?>
	
	<style type="text/css" media="screen">
		#wrapper input, textarea, #twitterbar, input[type=submit], input[type=reset], #imenu, .searchform, .post_container, .postformats, .postbar, .post-edit-link, .widget-container, .widget-title, .footer-widget-title, .comments_container, ol.commentlist li.even, ol.commentlist li.odd, .slider_nav, ul.metabox-tabs li, .tab-content, .list_item, .section-info, #of_container #header, .menu ul li a, .submit input, #of_container textarea, #of_container input, #of_container select, #of_container .screenshot img, #of_container .of_admin_bar, #of_container .subsection > h3, .subsection, #of_container #content .outersection .section, #carousel_list, #calloutwrap, #calloutbutton, .box1, .box2, .box3, .es-carousel-wrapper
  		
  	{
  		behavior: url('<?php echo get_template_directory_uri();  ?>/core/library/pie/PIE.htc');
	}
	</style>
<?php
}

add_action('wp_head', 'render_ie_pie', 8);

/**
* Custom post types for Slider, Carousel.
*/ 
function create_post_type() {

	global $themename, $themeslug, $options, $root;
	
	register_post_type( $themeslug.'_custom_slides',
		array(
			'labels' => array(
				'name' => __( 'Feature Slides' ),
				'singular_name' => __( 'Slides' )
			),
			'public' => true,
			'show_ui' => true, 
			'supports' => array('custom-fields', 'title'),
			'taxonomies' => array( 'slide_categories'),
			'has_archive' => true,
			'menu_icon' => "$root/images/pro/slider.png",
			'rewrite' => array('slug' => 'slides')
		)
	);
	
	register_post_type( $themeslug.'_carousel_images',
		array(
			'labels' => array(
				'name' => __( 'Image Carousel' ),
				'singular_name' => __( 'Carousel' )
			),
			'public' => true,
			'show_ui' => true, 
			'supports' => array('custom-fields', 'title' ),
			'taxonomies' => array( 'carousel_categories'),
			'has_archive' => true,
			'menu_icon' => "$root/images/pro/carousel.png",
			'rewrite' => array('slug' => 'carousel_images')
		)
	);
	
	register_post_type( $themeslug.'_portfolio_images',
		array(
			'labels' => array(
				'name' => __( 'Portfolio' ),
				'singular_name' => __( 'Images' )
			),
			'public' => true,
			'show_ui' => true, 
			'supports' => array('custom-fields', 'title'),
			'taxonomies' => array( 'portfolio_categories'),
			'has_archive' => true,
			'menu_icon' => "$root/images/pro/portfolio.png",
			'rewrite' => array('slug' => 'portfolio_images')
		)
	);

}
add_action( 'init', 'create_post_type' );

/**
* Custom taxonomies for Slider, Carousel.
*/ 
function custom_taxonomies() {

	global $themename, $themeslug, $options;
	
	register_taxonomy(
		'slide_categories',		
		$themeslug.'_custom_slides',		
		array(
			'hierarchical' => true,
			'label' => 'Slide Categories',	
			'query_var' => true,	
			'rewrite' => array( 'slug' => 'slide_categories' ),	
		)
	);
	register_taxonomy(
		'carousel_categories',		
		$themeslug.'_carousel_categories',		
		array(
			'hierarchical' => true,
			'label' => 'Carousel Categories',	
			'query_var' => true,	
			'rewrite' => array( 'slug' => 'carousel_categories' ),	
		)
	);
	register_taxonomy(
		'portfolio_categories',		
		$themeslug.'_portfolio_categories',		
		array(
			'hierarchical' => true,
			'label' => 'Portfolio Categories',	
			'query_var' => true,	
			'rewrite' => array( 'slug' => 'portfolio_categories' ),	
		)
	);
}
add_action('init', 'custom_taxonomies', 0);

/**
* Assign default category for Slider, Carousel posts.
*/ 
function custom_taxonomy_default( $post_id, $post ) {

	global $themename, $themeslug, $options;	

	if( 'publish' === $post->post_status ) {

		$defaults = array(

			'slide_categories' => array( 'default' ), 'carousel_categories' => array( 'default' ), 'portfolio_categories' => array( 'default' ),

			);

		$taxonomies = get_object_taxonomies( $post->post_type );

		foreach( (array) $taxonomies as $taxonomy ) {

			$terms = wp_get_post_terms( $post_id, $taxonomy );

			if( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {

				wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );

			}
		}
	}
}

add_action( 'save_post', 'custom_taxonomy_default', 100, 2 );

/**
* Edit columns for portfolio post type.
*/ 
add_filter('manage_edit-ec_portfolio_images_columns', 'portfolio_edit_columns');
add_action('manage_ec_portfolio_images_posts_custom_column',  'portfolio_columns_display', 10, 2);

function portfolio_edit_columns($portfolio_columns){
    $portfolio_columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => _x('Title', 'column name'),
        "image" => __('Image'),
        "category" => __('Categories'),
        "author" => __('Author'),
        "date" => __('Date'),
    );
   
    return $portfolio_columns;
}
function portfolio_columns_display($portfolio_columns, $post_id){
	global $themeslug, $post;
	$cat = get_the_terms($post->ID, 'portfolio_categories');
	
    switch ($portfolio_columns)
    {
        case "image":
        	$images = get_post_meta($post->ID, $themeslug.'_portfolio_image' , true);
        	echo '<img src="';
        	echo $images;
        	echo '"style="height: 50px; width: 50px;">';
        break;
        
        case "category":
        	if ( !empty( $cat ) ) {
                $out = array();
                foreach ( $cat as $c )
                    $out[] = "<a href='edit.php?portfolio_categories=$c->slug'> " . esc_html(sanitize_term_field('name', $c->name, $c->term_id, 'portfolio_categories', 'display')) . "</a>";
                echo join( ', ', $out );
            } else {
                _e('No Category.');  //No Taxonomy term defined
            }
        break;
	}
}

/**
* Edit columns for slider post type.
*/ 
add_filter('manage_edit-ec_custom_slides_columns', 'slider_edit_columns');
add_action('manage_ec_custom_slides_posts_custom_column',  'slides_columns_display', 10, 2);

function slider_edit_columns($portfolio_columns){
    $portfolio_columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => _x('Title', 'column name'),
        "image" => __('Image'),
        "category" => __('Categories'),
        "author" => __('Author'),
        "date" => __('Date'),
    );
   
    return $portfolio_columns;
}
function slides_columns_display($portfolio_columns, $post_id){
	global $themeslug, $post;
	$cat = get_the_terms($post->ID, 'slide_categories');
	$images = get_post_meta($post->ID, $themeslug.'_slider_image' , true);
	
    switch ($portfolio_columns)
    {
        case "image":
        	if ( !empty( $images ) ) {
        		echo '<img src="';
        		echo $images;
        		echo '"style="height: 50px; width: 50px;">';
        	}
        break;
        
        case "category":
        	if ( !empty( $cat ) ) {
                $out = array();
                foreach ( $cat as $c )
                    $out[] = "<a href='edit.php?slide_categories=$c->slug'> " . esc_html(sanitize_term_field('name', $c->name, $c->term_id, 'slide_categories', 'display')) . "</a>";
                echo join( ', ', $out );
            } else {
                _e('No Category.');  //No Taxonomy term defined
            }
        break;
	}
}

/**
* Edit columns for slider post type.
*/ 
add_filter('manage_edit-ec_carousel_columns', 'carousel_edit_columns');
add_action('manage_ec_carousel_posts_custom_column',  'carousel_columns_display', 10, 2);

function carousel_edit_columns($portfolio_columns){
    $portfolio_columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => _x('Title', 'column name'),
        "image" => __('Image'),
        "category" => __('Categories'),
        "author" => __('Author'),
        "date" => __('Date'),
    );
   
    return $portfolio_columns;
}
function carousel_columns_display($portfolio_columns, $post_id){
	global $themeslug, $post;
	$cat = get_the_terms($post->ID, 'carousel_categories');
	$images = get_post_meta($post->ID, $themeslug.'_post_image' , true);
	
    switch ($portfolio_columns)
    {
        case "image":
        	if ( !empty( $images ) ) {
        		echo '<img src="';
        		echo $images;
        		echo '"style="height: 50px; width: 50px;">';
        	}
        break;
        
        case "category":
        	if ( !empty( $cat ) ) {
                $out = array();
                foreach ( $cat as $c )
                    $out[] = "<a href='edit.php?carousel_categories=$c->slug'> " . esc_html(sanitize_term_field('name', $c->name, $c->term_id, 'carousel_categories', 'display')) . "</a>";
                echo join( ', ', $out );
            } else {
                _e('No Category.');  //No Taxonomy term defined
            }
        break;
	}
}

/**
* Add TypeKit support based on theme option.
*/ 
function typekit_support() {
	global $themename, $themeslug, $options;
	
	$embed = $options->get($themeslug.'_typekit');
	
	echo stripslashes($embed);

}
add_action('wp_head', 'typekit_support');

/**
* Add TypeKit support based on theme option.
*/ 
function google_analytics() {
	global $themename, $themeslug, $options;
	
	echo stripslashes ($options->get($themeslug.'_ga_code'));

}
add_action('wp_head', 'google_analytics');

function eclipse_lazy_load() {
	global $root;
    $placeholder = "$root/images/grey.gif";
    echo <<<EOF
<script type="text/javascript">
	jQuery(document).ready(function($){
  	jQuery("img").not("#orbitDemo img, .es-carousel img, #credit img").lazyload({
    	effect:"fadeIn",
    	placeholder: "$placeholder"
  	});
});
</script>
EOF;
}
add_action('wp_head', 'eclipse_lazy_load');
	
/**
* Register custom menus for header, footer.
*/ 
function register_menus() {
	register_nav_menus(
	array( 'header-menu' => __( 'Header Menu' ))
  );
}
add_action( 'init', 'register_menus' );
	
/**
* Menu fallback if custom menu not used.
*/ 
function menu_fallback() {
	global $post; ?>
	
	<ul id="nav_menu">
		<?php wp_list_pages( 'title_li=&sort_column=menu_order&depth=3'); ?>
	</ul><?php
}
/**
* Register widgets.
*/ 
function ifp_widgets_init() {
    register_sidebar(array(
    	'name' => 'Full Sidebar',
    	'id'   => 'sidebar-widgets',
    	'description'   => 'These are widgets for the sidebar.',
    	'before_widget' => '<div id="%1$s" class="widget-container">',
    	'after_widget'  => '</div>',
    	'before_title'  => '<h2 class="widget-title">',
    	'after_title'   => '</h2>'
    ));
    register_sidebar(array(
    	'name' => 'Left Half Sidebar',
    	'id'   => 'sidebar-left',
    	'description'   => 'These are widgets for the left sidebar.',
    	'before_widget' => '<div id="%1$s" class="widget-container">',
    	'after_widget'  => '</div>',
    	'before_title'  => '<h2 class="widget-title">',
    	'after_title'   => '</h2>'
    ));    	
    register_sidebar(array(
    	'name' => 'Right Half Sidebar',
    	'id'   => 'sidebar-right',
    	'description'   => 'These are widgets for the right sidebar.',
    	'before_widget' => '<div id="%1$s" class="widget-container">',
    	'after_widget'  => '</div>',
    	'before_title'  => '<h2 class="widget-title">',
    	'after_title'   => '</h2>'
   	));
    	
    register_sidebar(array(
		'name' => 'Box 1',
		'id' => 'box-1',
		'description' => 'This is the first widget of the four-box section',
		'before_widget' => '<div id="box1" class="three columns">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="box-widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Box 2',
		'id' => 'box-2',
		'description' => 'This is the second widget of the four-box section',
		'before_widget' => '<div id="box2" class="three columns">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="box-widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Box 3',
		'id' => 'box-3',
		'description' => 'This is the third widget of the four-box section',
		'before_widget' => '<div id="box3" class="three columns">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="box-widget-title">',
		'after_title' => '</h3>',
	));
		register_sidebar(array(
		'name' => 'Box 4',
		'id' => 'box-4',
		'description' => 'This is the fourth widget of the four-box section',
		'before_widget' => '<div id="box4" class="three columns">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="box-widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Footer',
		'id' => 'footer-widgets',
		'description' => 'These are the footer widgets',
		'before_widget' => '<div class="three columns footer-widgets">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="footer-widget-title">',
		'after_title' => '</h3>',
	));
}
add_action ('widgets_init', 'ifp_widgets_init');

function ec_custom_pagination($pages = '', $range = 4)
{
     $showitems = ($range * 2)+1;  
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   
 
     if(1 != $pages)
     {
         echo '<div class="pagination"><span>'.__( 'Page', 'core' ).' '.$paged.' of '.$pages.'</span>';
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo '<a href="'.get_pagenum_link(1).'">'.__( '&laquo; First', 'response' ).'</a>';
         if($paged > 1 && $showitems < $pages) echo '<a href="'.get_pagenum_link($paged - 1).'">'.__( '&lsaquo; Previous', 'response' ).'</a>';
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo '<a href="'.get_pagenum_link($paged + 1).'"">'.__( 'Next &rsaquo;', 'response').'</a>';
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo '<a href="'.get_pagenum_link($pages).'">'.__( 'Last &raquo;', 'response' ).'</a>';
         echo "</div>\n";
     }
}
/**
* Initialize response Core Framework and Pro Extension.
*/ 
require_once ( get_template_directory() . '/core/core-init.php' );
require_once ( get_template_directory() . '/core/pro/pro-init.php' );

/**
* Call additional files required by theme.
*/ 
require_once ( get_template_directory() . '/includes/classy-options-init.php' ); // Theme options markup.
require_once ( get_template_directory() . '/includes/options-functions.php' ); // Custom functions based on theme options.
require_once ( get_template_directory() . '/includes/meta-box.php' ); // Meta options markup.
require_once ( get_template_directory() . '/includes/update.php' ); // Notify user of theme update on "Updates" page in Dashboard.

require_once ( get_template_directory() . '/core/widgets/recent-post.php' ); // Widget include
require_once ( get_template_directory() . '/core/widgets/popular-post.php' ); // Widget include

// Presstrends
function presstrends() {

// PressTrends Account API Key
$api_key = 'zwhgyc1lnt56hki8cpwobb47bblas4er226b';

// Start of Metrics
global $wpdb;
$data = get_transient( 'presstrends_data' );
if (!$data || $data == ''){
$api_base = 'http://api.presstrends.io/index.php/api/sites/update/api/';
$url = $api_base . $api_key . '/';
$data = array();
$count_posts = wp_count_posts();
$count_pages = wp_count_posts('page');
$comments_count = wp_count_comments();
$theme_data = get_theme_data(get_stylesheet_directory() . '/style.css');
$plugin_count = count(get_option('active_plugins'));
$all_plugins = get_plugins();
foreach($all_plugins as $plugin_file => $plugin_data) {
$plugin_name .= $plugin_data['Name'];
$plugin_name .= '&';}
$posts_with_comments = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_type='post' AND comment_count > 0");
$comments_to_posts = number_format(($posts_with_comments / $count_posts->publish) * 100, 0, '.', '');
$pingback_result = $wpdb->get_var('SELECT COUNT(comment_ID) FROM '.$wpdb->comments.' WHERE comment_type = "pingback"');
$data['url'] = stripslashes(str_replace(array('http://', '/', ':' ), '', site_url()));
$data['posts'] = $count_posts->publish;
$data['pages'] = $count_pages->publish;
$data['comments'] = $comments_count->total_comments;
$data['approved'] = $comments_count->approved;
$data['spam'] = $comments_count->spam;
$data['pingbacks'] = $pingback_result;
$data['post_conversion'] = $comments_to_posts;
$data['theme_version'] = $theme_data['Version'];
$data['theme_name'] = $theme_data['Name'];
$data['site_name'] = str_replace( ' ', '', get_bloginfo( 'name' ));
$data['plugins'] = $plugin_count;
$data['plugin'] = urlencode($plugin_name);
$data['wpversion'] = get_bloginfo('version');
foreach ( $data as $k => $v ) {
$url .= $k . '/' . $v . '/';}
$response = wp_remote_get( $url );
set_transient('presstrends_data', $data, 60*60*24);}
}

// PressTrends WordPress Action
add_action('admin_init', 'presstrends');
/**
* End
*/

?>