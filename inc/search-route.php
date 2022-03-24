<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch(){
    register_rest_route( 'university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'universitySearchResults'
    ) );
}

function universitySearchResults($data){

    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
        's' => sanitize_text_field($data['term'])
    ));

    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()
    );

    while($mainQuery->have_posts()){
        $mainQuery->the_post();

        if(get_post_type() == 'post' OR get_post_type() == 'page'){
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            ));
        }

        if(get_post_type() == 'professor'){
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'photo' => get_the_post_thumbnail_url(0, 'professorLandscape' ),
                'programTaught' => get_field('related_programs')
            ));
        }

        if(get_post_type() == 'program'){

            $relateCampuses = get_field('related_campuses');

            if($relateCampuses){
                foreach($relateCampuses as $campus){
                    array_push($results['campuses'], array(
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus)
                    ));
                }
            }

            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_id()
            ));
        }

        if(get_post_type() == 'event'){

            $eventDate = new DateTime(get_field('event_date'));
            $excerpt = null;

            if(has_excerpt()){
                $excerpt = get_the_excerpt();
            }else{
                $excerpt = wp_trim_words(get_the_content(), 18);
            }  

            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' =>  $eventDate->format('M'),
                'day' =>  $eventDate->format('d'),
                'excerpt' =>  $excerpt
            ));
        }

        if(get_post_type() == 'campus'){
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }

    }

    if($results['programs']){
        $programsMetaQuery = array('relation' => 'OR');

        foreach($results['programs'] as $item){
            array_push($programsMetaQuery, array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' .$item['id']. '"'
            ));
        }

        $programRelationshipQuery = new WP_Query(array(
            'post_type' => array('professor', 'event'),
            'meta_query' => $programsMetaQuery
        ));

        while($programRelationshipQuery->have_posts()){
            $programRelationshipQuery->the_post();

            // event push block
            if(get_post_type() == 'event'){

                $eventDate = new DateTime(get_field('event_date'));
                $excerpt = null;
    
                if(has_excerpt()){
                    $excerpt = get_the_excerpt();
                }else{
                    $excerpt = wp_trim_words(get_the_content(), 18);
                }  
    
                array_push($results['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' =>  $eventDate->format('M'),
                    'day' =>  $eventDate->format('d'),
                    'excerpt' =>  $excerpt
                ));
            }

            // professor push block
            if(get_post_type() == 'professor'){
                array_push($results['professors'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'photo' => get_the_post_thumbnail_url(0, 'professorLandscape' ),
                    'programTaught' => get_field('related_programs')
                ));
            }
        }

        $results['professors'] = array_unique($results['professors'], SORT_REGULAR);
        $results['events'] = array_unique($results['events'], SORT_REGULAR);

    }

    return $results;
}
