<?php
/**
 * This file exports a class Camptix_API_2_CSV_Converter which has a method convert_2_csv that takes a valid API URL and saves a CSV file.
 *
 * @package Camptix_XML_CSV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CSV_Converter
 */
class Camptix_API_2_CSV_Converter {
	use Camptix_Common; // Use Common Trait.

	/**
	 * API base URL
	 *
	 * @var string
	 */
	private $api_base_url = '';

	/**
	 * Data type
	 *
	 * @var string
	 */
	private $data_type = '';

	/**
	 * Number of items to retrieve per page.
	 *
	 * @var int
	 */
	private $per_page = 100;

	/**
	 * CSV_Converter constructor.
	 *
	 * @param string $api_base_url API base URL.
	 * @param string $data_type    XML data type.
	 * @param int    $per_page     Number of items to retrieve per page.
	 */
	public function __construct( $api_base_url, $data_type, int $per_page = 100 ) {
		if ( ! empty( $api_base_url ) ) {
			$this->api_base_url = rtrim( $api_base_url, '/' ) . '/wp-json/wp/v2/';
		}

		if ( in_array( $data_type, $this->valid_cpt_types, true ) ) {
			$this->data_type = $data_type;
		}

		if ( $per_page > 10 && 100 >= $per_page ) {
			$this->per_page = $per_page;
		}
	}


	/**
	 * Get API data.
	 *
	 * @return WP_Error|array
	 */
	public function get_api_data() {
		$error = null;

		if ( empty( $this->api_base_url ) ) {
			$error = new WP_Error( __( 'Wrong API URL', 'camptix-xml-csv' ), __( 'API URL it is not a valid URL', 'camptix-xml-csv' ) );
		}

		if ( empty( $this->data_type ) ) {
			$error = new WP_Error( __( 'Wrong data type', 'camptix-xml-csv' ), __( 'Data type it is not a valid data type', 'camptix-xml-csv' ) );
		}

		if ( $error ) {
			return $error;
		}

		switch ( $this->data_type ) {
			case 'wcb_organizer':
				$cpt = 'organizers';
				break;
			case 'wcb_speaker':
				$cpt = 'speakers';
				break;
			case 'wcb_session':
				$cpt = 'sessions';
				break;
			case 'wcb_volunteer':
				$cpt = 'volunteers';
				break;
			case 'wcb_sponsor':
				$cpt = 'sponsors';
				break;
		}

		$api_url = $this->api_base_url . $cpt . '?per_page=' . $this->per_page;

		$response[0] = wp_remote_get( $api_url, array( 'redirection' => false ) );

		if ( is_wp_error( $response[0] ) ) {
			$error = new WP_Error( __( 'API Error', 'camptix-xml-csv' ), __( 'Error retrieving API data', 'camptix-xml-csv' ) );
		}

		if ( 200 !== wp_remote_retrieve_response_code( $response[0] ) ) {
			$error = new WP_Error( __( 'API Error', 'camptix-xml-csv' ), __( 'Error retrieving API data, reponse different from 200', 'camptix-xml-csv' ) );
		}

		if ( $error ) {
			return $error;
		}

		$total_items = wp_remote_retrieve_header( $response[0], 'x-wp-total' );
		$total_pages = wp_remote_retrieve_header( $response[0], 'x-wp-totalpages' );

		if ( 1 < $total_pages ) {
			$i = 1;
			for ( $page = 2; $page <= $total_pages; $page++ ) {
				$api_url        = $this->api_base_url . $cpt . '?per_page=' . $this->per_page . '&page=' . $page;
				$response[ $i ] = wp_remote_get( $api_url, array( 'redirection' => false ) );

				if ( is_wp_error( $response[ $i ] ) ) {
					return new WP_Error( __( 'API Error', 'camptix-xml-csv' ), __( 'Error retrieving API data', 'camptix-xml-csv' ) );
				}

				++$i;
			}
		}

		$res['total_items'] = $total_items;
		$res['total_pages'] = $total_pages;
		$res['response']    = $response;

		return $res;
	}

	/**
	 * Return CSV headers based on the type.
	 *
	 * @param string $data_type XML data type.
	 *
	 * @return array
	 */
	private function csv_headers( $data_type ) {
		$csv_headers = array();

		switch ( $data_type ) {
			case 'wcb_organizer':
				$csv_headers = $this->csv_headers_wcb_organizer;
				break;
			case 'wcb_speaker':
				$csv_headers = $this->csv_headers_wcb_speaker;
				break;
			case 'wcb_session':
				$csv_headers = $this->csv_headers_wcb_session;
				break;
			case 'wcb_volunteer':
				$csv_headers = $this->csv_headers_wcb_volunteer;
				break;
			case 'wcb_sponsor':
				$csv_headers = $this->csv_headers_wcb_sponsor;
				break;
		}

		return $csv_headers;
	}

	/**
	 * Return CSV data based on the type.
	 *
	 * @param object $item JSON object with data.
	 *
	 * @return array
	 */
	private function get_csv_data( $item ) {
		$csv_data = array();

		switch ( $this->data_type ) {
			case 'wcb_organizer':
				$title        = $item->title->rendered;
				$content      = $item->content->rendered;
				$excerpt      = $item->excerpt->rendered;
				$post_name    = $item->slug;
				$wp_user_name = $item->meta->_wcpt_user_name;
				$is_first     = '';

				// Add CSV row.
				$csv_data = array( $title, $content, $excerpt, $post_name, $wp_user_name, $is_first );
				break;
			case 'wcb_speaker':
				$speaker_id   = '';
				$title        = $item->title->rendered;
				$content      = $item->content->rendered;
				$excerpt      = $item->excerpt->rendered;
				$post_name    = $item->slug;
				$user_email   = '';
				$wp_user_name = $item->meta->_wcpt_user_name;
				$is_first     = '';

				// Add CSV row.
				$csv_data = array( $speaker_id, $title, $content, $excerpt, $post_name, $user_email, $wp_user_name, $is_first );
				break;
			case 'wcb_session':
				$title              = $item->title->rendered;
				$content            = $item->content->rendered;
				$excerpt            = $item->excerpt->rendered;
				$post_name          = $item->slug;
				$session_time       = $item->meta->_wcpt_session_time;
				$session_duration   = $item->meta->_wcpt_session_duration;
				$session_type       = $item->meta->_wcpt_session_type;
				$session_slides     = $item->meta->_wcpt_session_slides;
				$session_video      = $item->meta->_wcpt_session_video;
				$session_speaker_id = isset( $item->meta->_wcpt_speaker_id[0] ) ? $item->meta->_wcpt_speaker_id[0] : '';
				$track              = isset( $item->session_track[0] ) ? $item->session_track[0] : '';
				$track_nicename     = '';

				// Add CSV row.
				$csv_data = array( $title, $content, $excerpt, $post_name, $session_time, $session_duration, $session_type, $session_slides, $session_video, $session_speaker_id, $track, $track_nicename );
				break;
			case 'wcb_volunteer':
				$title           = $item->title->rendered;
				$content         = $item->content->rendered;
				$excerpt         = $item->excerpt->rendered;
				$post_name       = $item->slug;
				$wp_user_name    = $item->meta->_wcpt_user_name;
				$volunteer_email = '';
				$is_first_time   = '';

				// Add CSV row.
				$csv_data = array( $title, $content, $excerpt, $post_name, $wp_user_name, $volunteer_email, $is_first_time );
				break;
			case 'wcb_sponsor':
				$title           = $item->title->rendered;
				$content         = $item->content->rendered;
				$excerpt         = $item->excerpt->rendered;
				$post_name       = $item->slug;
				$company_name    = '';
				$website         = $item->meta->_wcpt_sponsor_website;
				$first_name      = '';
				$last_name       = '';
				$email_address   = '';
				$phone_number    = '';
				$street_address1 = '';
				$city            = '';
				$state           = '';
				$zip_code        = '';
				$country         = '';

				// Add CSV row.
				$csv_data = array( $title, $content, $excerpt, $post_name, $company_name, $website, $first_name, $last_name, $email_address, $phone_number, $street_address1, $city, $state, $zip_code, $country );
				break;
		}

		return $csv_data;
	}

	/**
	 * Convert API data to CSV.
	 *
	 * @param array $data API data.
	 *
	 * @return string
	 */
	public function convert_2_csv( array $data ) {
		// Add CSV headers based on data type.
		$csv_headers = $this->csv_headers( $this->data_type );

		$csv_file = fopen( 'php://temp', 'w' ); // phpcs:ignore

		fputcsv( $csv_file, $csv_headers );

		foreach ( $data['response'] as $response ) {
			$response_body = wp_remote_retrieve_body( $response );

			$items = json_decode( $response_body );

			foreach ( $items as $item ) {
				$csv_data = $this->get_csv_data( $item );

				fputcsv( $csv_file, $csv_data );
			}
		}

		rewind( $csv_file );
		$csv_out = stream_get_contents( $csv_file );
		fclose( $csv_file ); // phpcs:ignore

		return $csv_out;
	}

	/**
	 * Write CSV file.
	 *
	 * @param string $csv_data  CSV data to write to file.
	 *
	 * @return string
	 */
	public function write_csv( string $csv_data ) {
		$filename = 'camptix-' . $this->data_type . '-' . gmdate( 'Y-m-d' ) . '.csv';

		$csv = fopen( CAMPTIX_XML_CSV_UPLOAD_DIR . $filename, 'w' ); // phpcs:ignore
		fwrite( $csv, $csv_data ); // phpcs:ignore
		fclose( $csv ); // phpcs:ignore

		return $filename;
	}
}
