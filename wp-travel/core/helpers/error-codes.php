<?php
class WP_Travel_Helpers_Error_Codes {
	public static function get_error_codes( $args ) {
		$error_codes = array(
			'WP_TRAVEL_INVALID_NONCE'                     => array(
				'message' => __( 'Invalid nonce.', 'text-domain' ),
			),
			'WP_TRAVEL_INVALID_PERMISSION'                => array(
				'message' => __( 'Invalid permission.', 'text-domain' ),
			),

			'WP_TRAVEL_NO_TRIP_ID'                        => array(
				'message' => __( 'Invalid trip id.', 'text-domain' ),
			),
			'WP_TRAVEL_NO_PRICINGS'                       => array(
				'message' => __( 'No pricings found for the trip.', 'text-domain' ),
			),
			'WP_TRAVEL_EMPTY_CART'                        => array(
				'message' => __( 'Cart is empty.', 'text-domain' ),
			),
			'WP_TRAVEL_NO_PRICING_ID'                     => array(
				'message' => __( 'Pricing id not found.', 'text-domain' ),
			),
			'WP_TRAVEL_NO_DATE'                     => array(
				'message' => __( 'Please add date.', 'text-domain' ),
			),
			'WP_TRAVEL_NO_PAX'                     => array(
				'message' => __( 'Please add pax.', 'text-domain' ),
			),
			'WP_TRAVEL_NO_TRIP_PRICING_CATEGORIES'        => array(
				'message' => __( 'No trip pricing categories found.', 'text-domain' ),
			),
			'WP_TRAVEL_NO_PRICING_CATEGORY_ID'            => array(
				'message' => __( 'No Pricing category id.', 'text-domain' ),
			),
			'WP_TRAVEL_NO_PRICING_CATEGORY'               => array(
				'message' => __( 'No Pricing category found.', 'text-domain' ),
			),
			'WP_TRAVEL_ERROR_ADDING_PRICING_CATEGORY'     => array(
				'message' => __( 'Error adding pricing category.', 'text-domain' ),
			),
			'WP_TRAVEL_ERROR_DELETING_PRICING_CATEGORIES' => array(
				'message' => __( 'Error deleting pricing categories.', 'text-domain' ),
			),
			'WP_TRAVEL_ERROR_UPDATING_PRICING_CATEGORY'   => array(
				'message' => __( 'Error updating pricing category.', 'text-domain' ),
			),
			'WP_TRAVEL_ERROR_DELETING_PRICING_CATEGORY'   => array(
				'message' => __( 'Error deleting pricing category.', 'text-domain' ),
			),
			'WP_TRAVEL_ERROR_SAVING_PRICING'              => array(
				'message' => __( 'Error saving Pricing.', 'text-domain' ),
			),
			'WP_TRAVEL_ERROR_DELETING_PRICING'            => array(
				'message' => __( 'Error deleting Pricing.', 'text-domain' ),
			),

			// Trip Dates
			'WP_TRAVEL_ERROR_DELETING_TRIP_DATES'         => array(
				'message' => __( 'Error deleting trip dates.', 'text-domain' ),
			),
			'WP_TRAVEL_NO_TRIP_DATES'                     => array(
				'message' => __( 'No trip dates.', 'text-domain' ),
			),
			'WP_TRAVEL_NO_TRIP_DATE'                      => array(
				'message' => __( 'No trip date.', 'text-domain' ),
			),
			'WP_TRAVEL_ERROR_ADDING_TRIP_DATE'            => array(
				'message' => __( 'Error adding trip date.', 'text-domain' ),
			),
			'WP_TRAVEL_NO_DATE_ID'            => array(
				'message' => __( 'No trip date id', 'text-domain' ),
			),
			'WP_TRAVEL_ERROR_DELETING_DATE'            => array(
				'message' => __( 'Error deleting trip date.', 'text-domain' ),
			),
			

			// Trip Excluded Dates & Time.
			'WP_TRAVEL_ERROR_DELETING_TRIP_DATES'         => array(
				'message' => __( 'Error deleting trip dates.', 'text-domain' ),
			),
			'WP_TRAVEL_NO_TRIP_EXCLUDED_DATE_TIME'        => array(
				'message' => __( 'No exclude trip date.', 'text-domain' ),
			),
			'WP_TRAVEL_NO_TRIP_EXCLUDED_DATES_TIMES'      => array(
				'message' => __( 'No trip exclude dates & time found.', 'text-domain' ),
			),
			'WP_TRAVEL_ERROR_ADDING_TRIP_DATE'            => array(
				'message' => __( 'Error adding trip date.', 'text-domain' ),
			),

			// Trip Extras.
			'WP_TRAVEL_NO_TRIP_EXTRAS'                    => array(
				'message' => __( 'No trip extras found.', 'text-domain' ),
			),

			// WP Travel Search.
			'WP_TRAVEL_NO_TRIPS'                          => array(
				'message' => __( 'Trips not found.', 'text-domain' ),
			),

			// Trip pricing category taxonomy
			'WP_TRAVEL_NO_TRIP_PRICING_CATEGORIES_TERM'   => array(
				'message' => __( 'No trip pricing category term found.', 'text-domain' ),
			),

            // Coupon Response Codes.
            'WP_TRAVEL_INVALID_COUPON' => array(
                'message' => __( 'The coupon code is invalid.', 'text-domain' ),
            ),

            // Media response Codes.
            'WP_TRAVEL_NO_ATTACHMENT_ID' => array(
                'message' => __( 'The Attachment is invalid.', 'text-domain' ),
            ),

            'WP_TRAVEL_ATTACHMENT_NOT_FOUND' => array(
                'message' => __( 'The Attachment not found.', 'text-domain' ),
            )
        );

		return apply_filters( 'wp_travel_error_codes', $error_codes, $args );
	}

	public static function get_error( $code, $args = array() ) {
		$error_codes = self::get_error_codes( $args );
		if ( ! empty( $error_codes[ $code ] ) ) {
			return new WP_Error( $code, $error_codes[ $code ]['message'] );
		}

		return new WP_Error( 'WP_TRAVEL_ERROR_CODE_NOT_FOUND', __( "Error code '{$code}' note found.", 'text-domain' ) );
	}
}
