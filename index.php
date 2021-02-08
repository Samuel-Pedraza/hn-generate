<?php
/**
 * Plugin Name: Hacker News Generator
 * Version: 1.0
 * Description: Generate a fake version of Hacker News - Randomly Generate Titles
 * 
 * 
 * 
 */

include plugin_dir_path( __FILE__ ) . '/lib/markov.php';
include plugin_dir_path( __FILE__ ) . '/lib/generate_content.php';

class InitialSetup {
	public function __construct(){
    $this->unload_emojis();
		$this->unload_excess_links();

    add_filter('the_generator', array($this, 'remove_version'));
    add_filter('template_include', array($this, 'set_homepage'));
    
    add_action('wp_enqueue_scripts', array($this, 'manage_styles'), 50);
	}

  public function set_homepage(){
    if (is_front_page()) {
      return plugin_dir_path(__FILE__) . '/views/hn-front-page.php';
    }
    
    return $template;
  }

  // remove all existing styles, then add our own
	public function manage_styles(){   
    global $wp_styles;
    foreach( $wp_styles->queue as $handle ){
      wp_dequeue_style($handle);
      wp_deregister_script($handle);
    }

    wp_register_style( 'reset_style', plugins_url('/views/styles/reset.css', __FILE__), false, '1.0.0', 'all');
    wp_register_style( 'main_style', plugins_url('/views/styles/main.css', __FILE__), false, '1.0.0', 'all');  

    wp_enqueue_style( 'reset_style');
		wp_enqueue_style( 'main_style');
	}

	public function unload_emojis(){
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}

	public function unload_excess_links(){
		remove_action('wp_head', 'rsd_link'); 
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'feed_links', 2); 
		remove_action('wp_head', 'feed_links_extra', 3); 
		remove_action('wp_head', 'index_rel_link'); 
		remove_action('wp_head', 'start_post_rel_link', 10, 0); 
		remove_action('wp_head', 'parent_post_rel_link', 10, 0); 
		remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); 
		remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );
	}

	public function remove_version(){
		return '';
	}
}

$initial_setup = new InitialSetup;

$hn = new GenerateHNContent;
$hn->create_posts_recurring_cron();