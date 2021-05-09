<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Jungle
 * @subpackage Plugin_Jungle/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Jungle
 * @subpackage Plugin_Jungle/admin
 * @author     Your Name <email@example.com>
 */
class Plugin_Jungle_Admin_News {

    /**
	 * The URL for get posts data
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $URL    The string used to get posts data from CURL.
	 */
    const URL = 'https://jsonplaceholder.typicode.com/posts';

    const POST_TYPE = 'news';

    private function get_post_by_name( $name ) {
        $query = new WP_Query(array(
            "post_type" => self::POST_TYPE,
            "name" => $name
        ));
    
        return $query->have_posts() ? reset( $query->posts ) : null;
    }

    /**
     * Find a randomDate between $start_date and $end_date
     * 
     * @since    1.0.0
     */
    private function random_date( $start_date, $end_date )
    {
        $min = strtotime( $start_date );
        $max = strtotime( $end_date );

        $val = rand( $min, $max );

        return date( 'Y-m-d H:i:s', $val );
    }

    private function prepare_dates($number) {
        // For make better random date, generate an array and shuffle it.
        $current_date     = current_time( 'mysql' );
        $two_weeks_before = date( 'Y-m-d 00:00:00', strtotime('-2 weeks') );

        $dates = array();

        for ( $i = 0; $i < $number; $i++ ) {
            $dates[] = $this->random_date( $current_date, $two_weeks_before );
        }

        shuffle($dates);

        return $dates;
    }

    /**
     * Get all posts data from CURL.
     * 
     * @since    1.0.0
     */
    private function get_posts() {
        $response = wp_remote_get( self::URL );

        try {
            $posts = json_decode( $response['body'] );
        } catch ( Exception $ex ) {
            $posts = null;
        }

        return $posts;
    }

    /**
	 * Update users.
	 *
	 * @since    1.0.0
	 */
	public function cron_update_news() {
        $posts = $this->get_posts();   
        
        if ( !$posts ) {
            return;
        }

        $dates = $this->prepare_dates( count( $posts ) );

        foreach ($posts as $key => $post_data) {
            // Sanitize entries.
            $post_id      = (int) $post_data->id;
            $user_id      = (int) $post_data->userId;
            $post_title   = sanitize_text_field( $post_data->title );
            $post_name    = sanitize_title( $post_title );
            $post_content = $post_data->body;

            $post = $this->get_post_by_name( $post_name );

            if ( $post ) {
                $post->post_title   = $post_title;
                $post->post_name    = $post_name;
                $post->post_content = $post_content;
                $post->post_author  = $user_id;
                $post->post_date    = $dates[ $key ];
                $post_id = wp_update_post( $post );
            } else {

                $post_data = array(
                    'post_title'   => $post_title,
                    'post_name'    => $post_name,
                    'post_type'    => self::POST_TYPE,
                    'post_content' => $post_content,
                    'post_status'  => 'publish',
                    'post_author'  => $user_id,
                    'post_date'    => $dates[ $key ],
                );

                $post_id = wp_insert_post( $post_data, true );
            }

            if ( is_wp_error( $post_id ) ) {
                error_log( sprintf( 'An error occured when update the post #%d', $post_data->id ) );
            }
        }
    }
}
