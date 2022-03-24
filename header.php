<!DOCTYPE html>
<html>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
  </head>
  <script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
  <body>
    <header class="site-header">
    <div class="container">
      <h1 class="school-logo-text float-left"><a href="<?php echo site_url('/'); ?>"><strong>Fictional</strong> University</a></h1>
      <span class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></span>
      <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
      <div class="site-header__menu group">
        <nav class="main-navigation">
            <!-- <?php
                wp_nav_menu( array(
                    'theme-location' => 'headerMenuLocation'
                ) );
            ?> -->
          <ul class="min-list group">
            <li <?php if(is_page('about-us' )) echo 'class="current-menu-item"';  ?>><a href="<?php echo site_url('about-us'); ?>">About Us</a></li>

            <li <?php if(get_post_type() == 'program') echo 'class="current-menu-item"'; ?> ><a href="<?php echo get_post_type_archive_link('program'); ?>" >Programs</a></li>

            <li <?php if(get_post_type() == 'event' or is_page('past-events')) echo 'class="current-menu-item"'; ?>><a href="<?php echo get_post_type_archive_link('event'); ?>">Events</a></li>

            <li <?php if(get_post_type() == 'campus') echo 'class="current-menu-item"'; ?> ><a href="<?php echo get_post_type_archive_link('campus') ?>">Campuses</a></li>

            <li <?php if(get_post_type() == 'post') echo 'class="current-menu-item"';  ?>><a href="<?php echo site_url('blog'); ?>">Blog</a></li>
          </ul>
        </nav>

        <div class="site-header__util">
          <?php 
            if(is_user_logged_in()){ ?>   
                <a href="<?php echo esc_url(site_url('/my-notes')); ?>" class="btn btn--small btn--orange float-left push-right">My Notes</a>         
                <a href="<?php echo wp_logout_url(); ?>" class="btn btn--small  btn--dark-orange float-left btn--with-photo">Logout</a>
                  <span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span>
                  <span class="btn__text">Log Out</span>
                </a>
           <?php } else { ?>
              <a href="<?php echo wp_login_url(); ?>" class="btn btn--small btn--orange float-left push-right">Login</a>
              <a href="<?php echo wp_registration_url(); ?>" class="btn btn--small  btn--dark-orange float-left">Sign Up</a>
          <?php } ?>
          <a href="<?php echo esc_url(site_url('/search')); ?>" class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
        </div>

      </div>
    </div>
  </header>



