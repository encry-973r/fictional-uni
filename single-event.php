<?php

  get_header();

  while(have_posts()) {
    the_post(); 
    pageBanner(array(
      'title' => get_the_title()
      // 'subtitle' => 'A recap of our past events.'
    ));
    ?>

  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Events Home</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div> 
        
    <!-- to be displayed on parent and child team pages only. -->

    <?php
      // see if current page has child(ren) : is a parent page.
      $testList = get_pages(array(
        'child_of' => get_the_ID()
      ));

      if($theParent or $testList){ ?>

        <div class="page-links">
          <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent);  ?>"><?php echo get_the_title($theParent);   ?></a></h2>
          <ul class="min-list">
          <?php

            if($theParent){
              $findChildOf = $theParent;
            }else{
              $findChildOf = get_the_ID();
            }
              wp_list_pages( array(
                'title_li' => NULL,
                'child_of' => $findChildOf,
                'sort_column' => 'menu_order'
              ) );
          ?>
          </ul>
        </div>

      <?php }

    ?>  

    <div class="generic-content"><?php the_content(); ?></div>
    
    <?php
      $relatedPrograms = get_field('related_programs');

      if($relatedPrograms){
        echo '<hr class="section-break">';
        echo '<h2 class="heaadline headline--medium">Related Program(s)</h2>';
        echo '<ul class="link-list min-list">';

        foreach($relatedPrograms as $program){ ?>
          <li><a href="<?php echo get_the_permalink($program) ?>"><?php echo get_the_title($program) ?></a></li>
        <?php }

        echo '</ul>';
      
      } ?>
      
      

  </div>
    
  <?php }  get_footer(); ?>