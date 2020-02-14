<?php 
/*
 * Template Name: TS Template
 */

?>

<?php

get_header(); ?>
<!-- Bootstrap core CSS -->
<link href="<?=PRD_PLUGIN_URL_ADMIN?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?=PRD_PLUGIN_URL_ADMIN?>css/style.css" rel="stylesheet">
<div class="container-fluid">
    
    <?php
    if ( have_posts() ) :

             while ( have_posts() ) :
                    the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" class="post post-large">  
                        <div class="post-content">  
                            <?php the_content(); ?>
                        </div>
                    </article>
        <?php
            endwhile;
    endif;
    ?> 
    
</div><!-- #primary -->
<?php
get_footer(); ?>
<script type="text/javascript" src="<?=PRD_PLUGIN_URL_ADMIN?>js/bootstrap.min.js"></script>
