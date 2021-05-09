<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Jungle
 * @subpackage Plugin_Jungle/public/partials
 */

foreach ($posts as $post) :
    ?>
    <div class="col-12 col-md-6 col-lg-4 text-center mb-3">
        <div class="p-3 shadow">
            <h3 class="post-title"><?php echo $post->post_title; ?></h3>
            <p class="justify-content"><?php echo $post->post_content; ?></p>

            <p class="post-meta"><?php _e( sprintf( 'Posted by <i>%s</i>', $post->author->display_name ) ); ?></p>

            <a class="btn btn-primary" href="<?php echo $post->guid; ?>">Read more</a>
        </div>
    </div>
    <?php
endforeach;
