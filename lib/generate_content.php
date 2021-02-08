<?php 
class GenerateHNContent {
  public function __construct(){
    add_action('init', array($this, 'create_post'));
    
    add_filter('cron_schedules', array($this, 'set_cron_fast'));
  }

  public function fetch_new_stories(){
      
    $url = "https://hacker-news.firebaseio.com/v0/newstories.json";
    
    $response = wp_remote_get($url);
    $data = json_decode($response['body']);
    $top_ten_posts = array_slice($data, 0, 10);
    return $top_ten_posts;
    
  }

  public function fetch_story_titles()
  {
    $stories_ids = $this->fetch_new_stories();
    $stories_titles = array();

    foreach($stories_ids as $story_id){
      $response = wp_remote_get("https://hacker-news.firebaseio.com/v0/item/" . $story_id . ".json");
      $story = json_decode($response['body']);
      array_push($stories_titles, $story->title);
    }

    return $stories_titles;
  }

  public function generate_story_title(){

    $story_titles = $this->fetch_story_titles();

    $text = implode(" ", $story_titles);
    $order = 5;
    $length = rand(10, 65);

    $markov_table = generate_markov_table($text, $order);
    $markov = generate_markov_text($length, $markov_table, $order);
    return htmlentities($markov);

  }

  public function create_post(){

    $generated_title = $this->generate_story_title();

    $post_data = array(
      'post_title'    => $generated_title,
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'post'
    );
    
    wp_insert_post($post_data);
  }

  public function set_cron_fast($schedules){
    $schedules['three_minutes'] = array(
      'interval' => 180,
      'display'  => esc_html__( 'Every 3 minutes' ), 
    );
    
    return $schedules;
  }

  public function create_posts_recurring_cron()
  {

    wp_schedule_event(time(), 'three_minutes', array($this, 'create_post'));
  }

}