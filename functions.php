<?php

require get_theme_file_path( '/inc/search-route.php' );
require get_theme_file_path( '/inc/like-route.php' );

function university_custom_rest(){
  register_rest_field( 'post', 'authorName', array(
    'get_callback' => function(){
      return get_the_author();
    }
  ) );

  register_rest_field( 'note', 'userNoteCount', array(
    'get_callback' => function(){
      return count_user_posts(get_current_user_id(), 'note');
    }
  ) );

};

add_action('rest_api_init', 'university_custom_rest');

// PageBanner function
function pageBanner($args = NULL){ 
  
  // PageBanner logic goes here
  if(!$args['title']){
    $args['title'] = get_the_title();
  }

  if(!$args['subtitle']){
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  if(!$args['photo']){
   
    if(get_field('page_banner_background_image')){
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
   }else{
    $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
   }

  }

  ?>

  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>  
  </div>

<?php }

function university_files() {
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  
  wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyAJSpCJVh2_qgs5WnQ4RSKJp_yHQ1zNK9M', NULL, '1.0', true);
  wp_enqueue_script('axios', '//cdn.jsdelivr.net/npm/axios/dist/axios.min.js', NULL, '1.0', true);
  wp_enqueue_script('glidejs', '//cdn.jsdelivr.net/npm/@glidejs/glide', NULL, '1.0', true);

  wp_enqueue_script('main-university-js', 'http://localhost:3000/bundled.js' , NULL, '1.0', true);
  // wp_enqueue_script('main-university-js', get_theme_file_uri('/scripts.js'), array('jquery'), '1.0', true);
  wp_enqueue_style('university_main_styles', get_stylesheet_uri());

  wp_localize_script('main-university-js', 'universityData', array(
    'root_url' => get_site_url(),
    'nonce' => wp_create_nonce('wp_rest')
  ));

}

add_action('wp_enqueue_scripts', 'university_files');

function university_features(){
    
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size( 'professorPortrait', 480, 650, true);
    add_image_size( 'pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');


function university_adjust_queries($query){

    // Program post type
    if(!is_admin() and is_post_type_archive('campus') and $query->is_main_query()){
      $query->set('posts_per_page', -1);
    } 

    
  // Program post type
  if(!is_admin() and is_post_type_archive('program') and $query->is_main_query()){
    $query->set('orderby', 'title');    
    $query->set('order', 'ASC');
    $query->set('posts_per_page', -1);
  } 


  // Event post type
  if(!is_admin() and is_post_type_archive('event') and $query->is_main_query()){
    $today = date('Ymd');
    // $query->set('posts_per_page', 1);
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', array(
      array(
        'key' => 'event_date',
        'compare' => '>=',
        'value' => $today,
        'type' => 'numeric'
      )
    ));
  }
}

add_action('pre_get_posts', 'university_adjust_queries');


function universityMapKey($api){
  $api['key'] = 'xxxxxxxxxxxxxxxxxxxxxxxxxx';
  return $api;
}

add_filter( 'acf/fields/google_map/api', 'universityMapKey');



// Customize login screen.
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl(){
  return esc_url(site_url('/'));
}

add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle(){
  return get_bloginfo('name');
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS(){
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.e1c25131f06fb86aa94a.css'));
}

// force note post to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr){

  if($data['post_type'] == 'note'){

    if(count_user_posts( get_current_user_id(), 'note') > 5 and !$postarr['ID']){
      // print_r($postarr);
      // print_r($data);
      die('Your note creation limit exceeded.');

    }

    $data['post_title'] = sanitize_text_field($data['post_title']);
    $data['post_content'] = sanitize_textarea_field($data['post_content']);
  }

  if($data['post_type'] == 'note' and $data['post_status'] != 'trash'){
    $data['post_status'] = 'private';
  }

  return $data;

}
























