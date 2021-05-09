<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jimmylatour.fr
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
 * @author     Jimmy L <latour.jimmy@gmail.com>
 */
class Plugin_Jungle_Public_News {

    /**
	 * Init callback action for register news post type.
	 *
	 * @since    1.0.0
	 */
    public function init() {
        $args = array(
			'public'       => true,
			'label'        => __( 'News', 'plugin-jungle' ),
			'menu_icon'    => 'dashicons-sticky',
			'show_in_rest' => true,
			'supports'     => array(
				'title',
				'editor',
				'author',
			),
		);

		register_post_type( 'news', $args );
    }

	/**
     * Display the home page, called by a shortcode. 
     * 
     * @since    1.0.0
     */
	public function display() {
		$posts = get_posts(array(
			'post_type'      => 'news',
			'posts_per_page' => -1,
		));

		$users = array();

		if ( !empty( $posts ) ) {
			foreach ( $posts as &$post ) {
				$user = new WP_User( $post->post_author );
				$post->author = $user;
				$users[ $post->post_author ] = $user;
			}
		}

		ob_start();
		require_once plugin_dir_path( __FILE__ ) . '/partials/plugin-jungle-public-display.php';
		$content = ob_get_clean();

		return $content;
	}

	/**
     * Ajax method, load news by author id and return the view.
     * 
     * @since    1.0.0
     */
	public function load_by_author_id() {
		check_ajax_referer( 'pj_news_by_author_id' );

		$author_id = ! empty( $_POST['author_id'] ) ? (int) $_POST['author_id'] : 0;

		$data = array(
			'post_type'      => 'news',
			'posts_per_page' => -1,
		);

		if ( !empty( $author_id ) ) {
			$data['author'] = $author_id;
		}

		$posts = get_posts($data);

		if ( !empty( $posts ) ) {
			foreach ( $posts as &$post ) {
				$post->author = new WP_User( $post->post_author );
			}
		}

		ob_start();
		require_once plugin_dir_path( __FILE__ ) . '/partials/plugin-jungle-public-display-news.php';
		$content = ob_get_clean();

		wp_send_json_success( array(
			'content' => $content,
		) );
	}
}
