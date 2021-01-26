<?php
class WP_Travel_Helpers_Response_Codes{
    public static function get_success_codes() {
        $codes = array(
            // Trips
            'WP_TRAVEL_UPDATED_TRIP' => array(
                'message' => __( 'Successfully updated trip.', 'text-domain' ),
            ),

            // Trip Pricings
            'WP_TRAVEL_REMOVED_TRIP_PRICING' => array(
                'message' => __( 'Successfully removed trip pricing.', 'text-domain' ),
            ),

            // Categories
            'WP_TRAVEL_REMOVED_TRIP_PRICING_CATEGORIES' => array(
                'message' => __( 'Successfully removed trip pricing categories.', 'text-domain' ),
            ),
            'WP_TRAVEL_REMOVED_TRIP_PRICING_CATEGORY' => array(
                'message' => __( 'Successfully removed trip pricing category.', 'text-domain' ),
            ),

            // Dates
            'WP_TRAVEL_TRIP_DATES' => array(
                'message' => __( 'Successfully listed trip dates.', 'text-domain' ),
            ),
            'WP_TRAVEL_REMOVED_TRIP_DATES' => array(
                'message' => __( 'Successfully removed trip dates.', 'text-domain' ),
            ),
            'WP_TRAVEL_ADDED_TRIP_DATE' => array(
                'message' => __( 'Successfully added trip date.', 'text-domain' ),
            ),
            'WP_TRAVEL_REMOVED_TRIP_DATE'            => array(
				'message' => __( 'Removed trip date successfully.', 'text-domain' ),
			),
            // Excluded Dates & Time
            'WP_TRAVEL_TRIP_EXCLUDED_DATES_TIMES' => array(
                'message' => __( 'Successfully listed trip dates & times.', 'text-domain' ),
            ),
            'WP_TRAVEL_UPDATED_TRIP_EXCLUDED_DATES_TIMES' => array(
                'message' => __( 'Successfully updated trip dates & times.', 'text-domain' ),
            ),
            'WP_TRAVEL_REMOVED_TRIP_DATES' => array(
                'message' => __( 'Successfully removed trip dates.', 'text-domain' ),
            ),
            'WP_TRAVEL_ADDED_TRIP_DATE' => array(
                'message' => __( 'Successfully added trip date.', 'text-domain' ),
            ),

            // Cart
            'WP_TRAVEL_ADDED_TO_CART' =>array(
                'message' => __( 'Successfully added trip to cart.', 'text-domain' ),
            ),

            // Search
            'WP_TRAVEL_FILTER_RESULTS' => array(
                'message' => __( 'Successfully listed filter result.', 'text-domain' ),
            ),

            // Media
            'WP_TRAVEL_ATTACHMENT_DATA' => array(
                'message' => __( 'Attachment data.', 'text-domain' ),
            ),
            'WP_TRAVEL_TRIP_TABS' => array(
                'message' => __( 'Trip Tab loaded successfully.', 'text-domain' ),
            ),
            'WP_TRAVEL_SETTINGS' => array(
                'message' => __( 'Settings loaded successfully.', 'text-domain' ),
            ),
            'WP_TRAVEL_UPDATED_SETTINGS' => array(
                'message' => __( 'Settings updated successfully.', 'text-domain' ),
            ),
            'WP_TRAVEL_TRIP_IDS' => array(
                'message' => __( 'Trip ID loaded successfully.', 'text-domain' ),
            ),
            'WP_TRAVEL_LICENSE_ACTIVATION' => array(
                'message' => __( 'License activation.', 'text-domain' ),
            ),
            'WP_TRAVEL_LICENSE_DEACTIVATION' => array(
                'message' => __( 'License deactivation.', 'text-domain' ),
            ),
        );

        return apply_filters( 'wp_travel_success_codes', $codes );
    }

    public static function get_success_response( $code, $data = array() ) {
        $codes = self::get_success_codes();
        if ( ! empty( $codes[ $code ] ) ) {
            $defaults = array(
                'code' => $code,
                'message' =>  $codes[ $code ]['message']
            );
            return wp_parse_args( $data, $defaults );
        }
    }
}