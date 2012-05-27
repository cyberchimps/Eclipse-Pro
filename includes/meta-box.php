<?php
/**
 * Create meta box for editing pages in WordPress
 *
 * Compatible with custom post types since WordPress 3.0
 * Support input types: text, textarea, checkbox, checkbox list, radio box, select, wysiwyg, file, image, date, time, color
 *
 * @author: Rilwis
 * @url: http://www.deluxeblogtips.com/2010/04/how-to-create-meta-box-wordpress-post.html
 * @usage: please read document at project homepage and meta-box-usage.php file
 * @version: 3.0.1
 */
 

/********************* BEGIN DEFINITION OF META BOXES ***********************/

add_action('init', 'initialize_the_meta_boxes');

function initialize_the_meta_boxes() {

	global  $themename, $themeslug, $themenamefull, $options; // call globals.
	
	// Call taxonomies for select options
	
	$portfolioterms = get_terms('portfolio_categories', 'hide_empty=0');
	$portfoliooptions = array();

		foreach($portfolioterms as $term) {
			$portfoliooptions[$term->slug] = $term->name;
		}

	
	$carouselterms = get_terms('carousel_categories', 'hide_empty=0');
	$carouseloptions = array();

		foreach($carouselterms as $term) {
			$carouseloptions[$term->slug] = $term->name;
		}

	$terms = get_terms('slide_categories', 'hide_empty=0');
	$slideroptions = array();

		foreach($terms as $term) {
			$slideroptions[$term->slug] = $term->name;
		}

	$terms2 = get_terms('category', 'hide_empty=0');
	$blogoptions = array();
	$blogoptions['all'] = "All";

		foreach($terms2 as $term) {
			$blogoptions[$term->slug] = $term->name;
		}
	
	// End taxonomy call
	
	$meta_boxes = array();
		
	$mb = new Chimps_Metabox('post_slide_options', $themenamefull.' Slider Options', array('pages' => array('post')));
	$mb
		->tab("Slider Options")
			->single_image($themeslug.'_slider_image', 'Slider Image', '')
			->text($themeslug.'_slider_text', 'Slider Text', 'Enter your slider text here')
			->checkbox($themeslug.'_slider_hidetitle', 'Title Bar', '', array('std' => 'on'))
			->sliderhelp('', 'Need Help?', '')
		->end();

	
	$mb = new Chimps_Metabox('Carousel', 'Featured Post Carousel', array('pages' => array($themeslug.'_carousel_images')));
	$mb
		->tab("Featured Post Carousel Options")
			->single_image($themeslug.'_post_image', 'Featured Post Image', '')
			->reorder('reorder_id', 'Reorder', 'Reorder Desc' )
		->end();
		
	$mb = new Chimps_Metabox('Portfolio', 'Portfolio Element', array('pages' => array($themeslug.'_portfolio_images')));
	$mb
		->tab("Portfolio Element")
			->single_image($themeslug.'_portfolio_image', 'Portfolio Image', '')
		->end();

	$mb = new Chimps_Metabox('slides', 'Feature Slides', array('pages' => array($themeslug.'_custom_slides')));
	$mb
		->tab("Feature Slide Options")
			->text($themeslug.'_slider_caption', 'Slide Caption', '')
			->text($themeslug.'_slider_url', 'Slide Link', '')
			->single_image($themeslug.'_slider_image', 'Slide Image', '')
			->checkbox($themeslug.'_slider_hidetitle', 'Slide Title Bar', '', array('std' => 'on'))
			->sliderhelp('', 'Need Help?', '')
			->reorder('reorder_id', 'Reorder Name', 'Reorder Desc' )
		->end();
	$mb = new Chimps_Metabox('pages', $themenamefull.' Page Options', array('pages' => array('page')));
	$mb
		->tab("Page Options")
			->image_select($themeslug.'_page_sidebar', 'Select Page Layout', '',  array('options' => array(TEMPLATE_URL . '/images/options/right.png' , TEMPLATE_URL . '/images/options/left.png', TEMPLATE_URL . '/images/options/rightleft.png', TEMPLATE_URL . '/images/options/tworight.png', TEMPLATE_URL . '/images/options/none.png')))
			->checkbox($themeslug.'_hide_page_title', 'Page Title', '', array('std' => 'on'))
			->section_order($themeslug.'_page_section_order', 'Page Elements', '', array('options' => array(
					'page_section' => 'Page',
					'breadcrumbs' => 'Breadcrumbs',
					'page_slider' => 'Feature Slider',
					'portfolio_element' => 'Portfolio',
					'recent_posts_element' => 'Recent Posts',
					'callout_section' => 'Callout',
					'twitterbar_section' => 'Twitter Bar',
					'product_element' => 'Product',
					'box_section' => 'Boxes',
					'carousel_section' => 'Carousel',			
					),
					'std' => 'page_section,breadcrumbs'
				))
			->pagehelp('', 'Need Help?', '')
		->tab($themenamefull." Slider Options")
			->select($themeslug.'_page_slider_type', 'Select Slider Type', '', array('options' => array('Custom Slides', 'Blog Posts')) )
			->select($themeslug.'_slider_category', 'Custom Slide Category', '', array('options' => $slideroptions) )
			->select($themeslug.'_slider_blog_category', 'Blog Post Category', '', array('options' => $blogoptions, 'all') )
			->text($themeslug.'_slider_blog_posts_number', 'Number of Featured Blog Posts', '', array('std' => '5'))
			->text($themeslug.'_slider_height', 'Slider Height', '', array('std' => '430'))
			->text($themeslug.'_slider_delay', 'Slider Delay Time (MS)', '', array('std' => '3500'))
			->select($themeslug.'_page_slider_animation', 'Slider Animation Type', '', array('options' => array('Horizontal-Push (default)', 'Fade', 'Horizontal-Slide', 'Vertical-Slide')) )
			->select($themeslug.'_page_slider_navigation_style', 'Slider Navigation Style', '', array('options' => array('Dots (default)', 'None')) )
			->select($themeslug.'_page_slider_caption_style', 'Slider Caption Style', '', array('options' => array('None (default)', 'Bottom', 'Left', 'Right')) )
			->checkbox($themeslug.'_hide_arrows', 'Navigation Arrows', '', array('std' => 'on'))
			->checkbox($themeslug.'_enable_wordthumb', 'WordThumb Image Resizing', '', array('std' => 'off'))
			->sliderhelp('', 'Need Help?', '')		
		->tab("Product Options")
			->select($themeslug.'_product_text_align', 'Text Align', '', array('options' => array('Left', 'Right')) )
			->text($themeslug.'_product_title', 'Product Title', '', array('std' => 'Product'))
			->textarea($themeslug.'_product_text', 'Proudct Text', '', array('std' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. '))
			->select($themeslug.'_product_type', 'Media Type', '', array('options' => array('Image', 'Video')) )
			->single_image($themeslug.'_product_image', 'Product Image', '', array('std' =>  TEMPLATE_URL . '/images/pro/product.jpg'))
			->textarea($themeslug.'_product_video', 'Video Embed', '')
			->checkbox($themeslug.'_product_link_toggle', 'Product Link', '', array('std' => 'on'))
			->text($themeslug.'_product_link_url', 'Link URL', '', array('std' => home_url()))
			->text($themeslug.'_product_link_text', 'Link URL', '', array('std' => 'Buy Now'))
		->tab("Callout Options")
			->textarea($themeslug.'_callout_text', 'Callout Text', '')
			->checkbox($themeslug.'_extra_callout_options', 'Custom Callout Options', '', array('std' => 'off'))
			->color($themeslug.'_custom_callout_text_color', 'Custom Text Color', '')
			->color($themeslug.'_custom_callout_link_color', 'Custom Link Color', '')
			->pagehelp('', 'Need help?', '')
		->tab("Box Options")
			->checkbox($themeslug.'_box_title_toggle', 'Title', '')
			->text($themeslug.'_box_title', '', '')
		->tab("Recent Posts Options")
			->checkbox($themeslug.'_recent_posts_title_toggle', 'Title', '')
			->text($themeslug.'_recent_posts_title', '', '')
			->select($themeslug.'_recent_posts_category', 'Post Category', '', array('options' => $blogoptions, 'all') )
			->checkbox($themeslug.'_recent_posts_images_toggle', 'Images', '')
		->tab("Carousel Options")
			->select($themeslug.'_carousel_category', 'Carousel Category', '', array('options' => $carouseloptions) )
			->text($themeslug.'_carousel_speed', 'Carousel Animation Speed (ms)', '', array('std' => '750'))
		->tab("Portfolio Options")
			->select($themeslug.'_portfolio_row_number', 'Images per row', '', array('options' => array('Four (default)', 'Three', 'Two')) )
			->select($themeslug.'_portfolio_category', 'Portfolio Category', '', array('options' => $portfoliooptions) )
			->checkbox($themeslug.'_portfolio_title_toggle', 'Portfolio Title', '')
			->text($themeslug.'_portfolio_title', 'Title', '', array('std' => 'Portfolio'))
		->tab("Twitter Options")
			->text($themeslug.'_twitter_handle', 'Twitter Handle', 'Enter your Twitter handle if using the Twitter bar')
			->checkbox($themeslug.'_twitter_reply', 'Show @ Replies', '')
		->tab("SEO Options")
			->text($themeslug.'_seo_title', 'SEO Title', '')
			->textarea($themeslug.'_seo_description', 'SEO Description', '')
			->textarea($themeslug.'_seo_keywords', 'SEO Keywords', '')
			->pagehelp('', 'Need help?', '')
		->end();

	foreach ($meta_boxes as $meta_box) {
		$my_box = new RW_Meta_Box_Taxonomy($meta_box);
	}

}
