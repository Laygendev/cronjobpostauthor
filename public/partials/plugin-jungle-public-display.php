<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://jimmylatour.fr
 * @since      1.0.0
 *
 * @package    Plugin_Jungle
 * @subpackage Plugin_Jungle/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="row mt-5">

    <h2 class="font-weight-bold col-12 col-md-6 mb-4"><?php _e( 'Last news', 'plugin-jungle' ); ?></h2>

    <form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="POST" class="col-12 col-md-6 form-news-author text-right">
        <?php wp_nonce_field( 'pj_news_by_author_id' ); ?>

        <label for="author_id"><?php _e( 'Filter by author :', 'plugin-jungle' ); ?></label>

        <select id="author_id" name="author_id">
            <option value="0"><?php _e( 'All authors', 'plugin-jungle' ); ?></option>

            <?php
            foreach ( $users as $user ) :
                ?>
                <option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
                <?php
            endforeach;
            ?>
        </select>

        <img class="loader d-none" src="<?php echo admin_url('images/loading.gif' ); ?>" alt="<?php _e('Loading', 'plugin-jungle') ; ?>" />
    </form>


    <div class="news-content row">
        <?php require_once plugin_dir_path( __FILE__ ) . '/plugin-jungle-public-display-news.php'; ?>
    </div>
</div>