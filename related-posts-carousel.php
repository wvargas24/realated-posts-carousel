<?php
/**
* Plugin Name: Related Posts Carousel
* Plugin URI: https://www.geekbears.com/
* Description: This is the related posts carousel plugin.
* Version: 1.0
* Author: Wuilly Vargas
* Author URI: mailto:wuilly.vargas22@gmail.com
**/


/* ---------------------------------------------------------------------------
 * Function to load script and css files
 * --------------------------------------------------------------------------- */
function load_rpc_files(){
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);
    wp_enqueue_style('owl-style', plugin_dir_url( __FILE__ ) . 'assets/css/owl.carousel.min.css');
    wp_enqueue_style('rpc-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), '6.5');
    wp_enqueue_script( 'owl-script',  plugin_dir_url( __FILE__ ) . 'assets/js/owl.carousel.min.js');
    wp_enqueue_script( 'rpc-script',  plugin_dir_url( __FILE__ ) . 'assets/js/rpc-script.js', array('jquery')); // jQuery will be included automatically
    wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ); // setting ajaxurl
}
add_action( 'wp_enqueue_scripts','load_rpc_files' );

/* ---------------------------------------------------------------------------
 * Function to cut text strings
 * --------------------------------------------------------------------------- */
function wv_trim_text($text, $limit=100){   
    $text = trim($text);
    $text = strip_tags($text);
    $size = strlen($text);
    $result = '';
    if($size <= $limit){
        return $text;
    }else{
        $text = substr($text, 0, $limit);
        $words = explode(' ', $text);
        $result = implode(' ', $words);
        $result .= '...';
    }   
    return $result;
}

/* ---------------------------------------------------------------------------
 * [rpc_posts_load_carousel]
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'rpc_posts_load_carousel' ) ){
    function rpc_posts_load_carousel( $attr, $content = null )
    {
        ob_start();
        extract(shortcode_atts(array(
            'count'             => 10,
            'excerpt'           => '1',
        ), $attr));
		
		$orig_post = $post;
        global $post;
        $tags = wp_get_post_tags($post->ID);
		
		$tag_ids = array();
		foreach($tags as $individual_tag){
			$tag_ids[] = $individual_tag->term_id;
		}
		$args = array(
			'post_type'             => 'post',
			'posts_per_page'        => intval($count),
			'no_found_rows'         => 1,
			'post_status'           => 'publish',
			'ignore_sticky_posts'   => 0,
			'tag__in' => $tag_ids,
			'post__not_in' => array($post->ID),
		);
        
        $query = new WP_Query( $args );?>  
  
        <?php if ($query->have_posts()) :?>
			<h3>Related posts</h3>
            <div class="rpc-list owl-carousel owl-theme">
                <?php 
                    while( $query->have_posts() ){
                        $query->the_post();
                        require('partials/content-loop.php');                        
                    }                
                    wp_reset_postdata();
                ?>                        
            </div>
		<?php else: ?>
			<p>Not posts found</p>
        <?php endif; ?>
            
        <?php 
        return ob_get_clean();
    }
}
add_shortcode( 'rpc_posts_load_carousel', 'rpc_posts_load_carousel' );