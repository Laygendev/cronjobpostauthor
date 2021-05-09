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
class Plugin_Jungle_Admin_Users {

    /**
	 * The URL for get users data
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $URL    The string used to get users data from CURL.
	 */
    const URL = 'https://jsonplaceholder.typicode.com/users';

    /**
     * Get all users data from CURL.
     * 
     * @since    1.0.0
     */
    private function get_users() {
        $response = wp_remote_get( self::URL );

        try {
            $users = json_decode( $response['body'] );
        } catch ( Exception $ex ) {
            $users = null;
        }

        return $users;
    }

    /**
     * Uses regex that accepts any word character or hyphen in last name
     * 
     * @since    1.0.0
     */
    private function split_name( $name ) {
        $name       = trim( $name );
        $last_name  = ( strpos( $name, ' ' ) === false) ? '' : preg_replace( '#.*\s([\w-]*)$#', '$1', $name );
        $first_name = trim( preg_replace( '#'.preg_quote( $last_name,'#' ) . '#', '', $name ) );

        return array( $first_name, $last_name );
    }

    /**
	 * Update users.
	 *
	 * @since    1.0.0
	 */
	public function cron_update_users() {
        $users = $this->get_users();        

        if ( !$users ) {
            return;
        }

        foreach ($users as $user_data) {
            // Sanitize entries.
            $user_id  = (int) $user_data->id;
            $name     = sanitize_text_field( $user_data->name );
            $email    = sanitize_email( $user_data->email );
            $username = sanitize_text_field( $user_data->username );
            $website  = esc_url_raw( $user_data->website );

            $address_street    = sanitize_text_field( $user_data->address->street );
            $address_suite     = sanitize_text_field( $user_data->address->suite );
            $address_city      = sanitize_text_field( $user_data->address->city );
            $address_zipcode   = sanitize_text_field( $user_data->address->zipcode );
            $address_latitude  = sanitize_text_field( $user_data->address->geo->lat );
            $address_longitude = sanitize_text_field( $user_data->address->geo->lng );

            $coordinate_phone = sanitize_text_field( $user_data->phone );

            $company_name         = sanitize_text_field( $user_data->company->name );
            $company_catch_phrase = sanitize_text_field( $user_data->company->catchPhrase );
            $company_bs           = sanitize_text_field( $user_data->company->bs );

            $splitted_name = $this->split_name( $name );

            $user = new WP_User( $user_id );

            $user->user_email = $email;
            $user->user_login = $username;
            $user->user_url   = $website;
            $user->first_name = ! empty( $splitted_name[0] ) ? $splitted_name[0] : '';
            $user->last_name  = ! empty( $splitted_name[1] ) ? $splitted_name[1] : '';

            if ($user->ID == 0) {
                // Is new user.
                $user->ID        = (int) $user_data->id;
                $user->user_pass = wp_generate_password();

                $user_id = wp_insert_user( $user );
            } else {
                $user_id = wp_update_user( $user );
            }

            if ( is_wp_error( $user_id ) ) {
                error_log( sprintf( 'An error occured when update the user #%d', $user_data->id ) );
            }

            // If is not the admin.
            if ( $user_id != 1 ) {
                $user->set_role( 'author' );
            }

            // ACF Fields
            // Address
            update_user_meta( $user_id, 'address_street', $address_street );
            update_user_meta( $user_id, 'address_suite', $address_suite );
            update_user_meta( $user_id, 'address_city', $address_city );
            update_user_meta( $user_id, 'address_zipcode', $address_zipcode );
            update_user_meta( $user_id, 'address_latitude', $address_latitude );
            update_user_meta( $user_id, 'address_longitude', $address_longitude );

            // Coordinate
            update_user_meta( $user_id, 'coordinate_phone', $coordinate_phone );
            
            // Company
            update_user_meta( $user_id, 'company_name', $company_name );
            update_user_meta( $user_id, 'company_catch_phrase', $company_catch_phrase );
            update_user_meta( $user_id, 'company_bs', $company_bs );
        }
    }
}
