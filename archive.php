<?php
    get_header();  
    pageBanner(array(
        'title' => get_the_archive_title(),
        'subtitle' => get_the_archive_description()
    ));
?>



<div class="container container--narrow page-section">
    <?php
        while(have_posts()){
            the_post(); ?>
             <div class="post-item">
                <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>


                <div class="metabox">
                    <p>Posted by <?php echo get_the_author_posts_link(); ?> on <?php the_time('M-j-y'); ?> in <?php echo get_the_category_list(', ' ); ?></p>
                </div>

                <div class="generic-content">
                    <?php the_excerpt(); ?>
                    <a href="<?php the_permalink(); ?>" style="color:white; text-decoration:none;" class="btn btn--blue">Continue reading &raquo</a>
                </div>
            </div>
        <?php }
    ?>
</div>

<?php
    get_footer();
?>