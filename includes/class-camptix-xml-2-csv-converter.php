<?php
/**
 * This file exports a class Camptix_XML_2_CSV_Converter which has a method convert_2_csv that takes an XML file uploaded and saves a CSV file.
 * The class uses the DOMDocument to parse the XML file and extract the necessary data.
 *
 * @package Camptix_XML_CSV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CSV_Converter
 */
class Camptix_XML_2_CSV_Converter {
	use Camptix_Common; // Use Common Trait.

	/**
	 * DOM document object
	 *
	 * @var DOMDocument
	 */
	private $dom_document = null;

	/**
	 * CSV_Converter constructor.
	 *
	 * @param string $xml_data XML data to convert.
	 */
	public function __construct( string $xml_data ) {
		$this->dom_document = new DOMDocument();

		$this->dom_document->preserveWhiteSpace = false;
		$this->dom_document->loadXML( $xml_data );
	}

	/**
	 * Check if the type is valid.
	 *
	 * @return string|bool
	 */
	public function get_xml_type() {
		// Get all the wp:post_type nodes.
		$post_type_nodes = $this->dom_document->getElementsByTagNameNS( $this->ns_wp, 'post_type' );

		// Loop through the nodes and check for the value of CPT.
		foreach ( $post_type_nodes as $post_type_node ) {
			$value = $post_type_node->nodeValue; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			if ( in_array( $value, $this->valid_cpt_types, true ) ) {
				return $value;
			}
		}

		return false;
	}

	/**
	 * Get post meta value.
	 *
	 * @param string $meta_key Meta key to search.
	 * @param object $item     DOM object with data.
	 *
	 * @return string
	 */
	private function get_post_meta( string $meta_key, $item ) {

		$post_meta = $item->getElementsByTagNameNS( $this->ns_wp, 'postmeta' );

		foreach ( $post_meta as $meta ) {
			$meta_key_node = $meta->getElementsByTagNameNS( $this->ns_wp, 'meta_key' )->item( 0 )->nodeValue;

			if ( $meta_key === $meta_key_node ) {
				return $meta->getElementsByTagNameNS( $this->ns_wp, 'meta_value' )->item( 0 )->nodeValue;
			}
		}
		return '';
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
	 * @param object $item      DOM object with data.
	 * @param string $data_type XML data type.
	 *
	 * @return array
	 */
	private function get_csv_data( $item, $data_type ) {
		$csv_data = array();

		switch ( $data_type ) {
			case 'wcb_organizer':
				$title     = $item->getElementsByTagName( 'title' )->item( 0 )->nodeValue;
				$content   = $item->getElementsByTagNameNS( $this->ns_content, 'encoded' )->item( 0 )->nodeValue;
				$excerpt   = $item->getElementsByTagNameNS( $this->ns_excerpt, 'encoded' )->item( 0 )->nodeValue;
				$post_name = $item->getElementsByTagNameNS( $this->ns_wp, 'post_name' )->item( 0 )->nodeValue;

				// Add CSV row.
				$csv_data = array( $title, $content, $excerpt, $post_name );
				break;
			case 'wcb_speaker':
				$speaker_id   = $item->getElementsByTagNameNS( $this->ns_wp, 'post_id' )->item( 0 )->nodeValue;
				$title        = $item->getElementsByTagName( 'title' )->item( 0 )->nodeValue;
				$content      = $item->getElementsByTagNameNS( $this->ns_content, 'encoded' )->item( 0 )->nodeValue;
				$excerpt      = $item->getElementsByTagNameNS( $this->ns_excerpt, 'encoded' )->item( 0 )->nodeValue;
				$post_name    = $item->getElementsByTagNameNS( $this->ns_wp, 'post_name' )->item( 0 )->nodeValue;
				$user_email   = $this->get_post_meta( '_wcb_speaker_email', $item );
				$wp_user_name = $this->get_post_meta( '_wcpt_user_name', $item );

				// Add CSV row.
				$csv_data = array( $speaker_id, $title, $content, $excerpt, $post_name, $user_email, $wp_user_name );
				break;
			case 'wcb_session':
				$title              = $item->getElementsByTagName( 'title' )->item( 0 )->nodeValue;
				$content            = $item->getElementsByTagNameNS( $this->ns_content, 'encoded' )->item( 0 )->nodeValue;
				$excerpt            = $item->getElementsByTagNameNS( $this->ns_excerpt, 'encoded' )->item( 0 )->nodeValue;
				$post_name          = $item->getElementsByTagNameNS( $this->ns_wp, 'post_name' )->item( 0 )->nodeValue;
				$session_time       = $this->get_post_meta( '_wcpt_session_time', $item );
				$session_duration   = $this->get_post_meta( '_wcpt_session_duration', $item );
				$session_type       = $this->get_post_meta( '_wcpt_session_type', $item );
				$session_slides     = $this->get_post_meta( '_wcpt_session_slides', $item );
				$session_video      = $this->get_post_meta( '_wcpt_session_video', $item );
				$session_speaker_id = $this->get_post_meta( '_wcpt_speaker_id', $item );
				$track              = $item->getElementsByTagName( 'category' )->item( 0 )->nodeValue;
				$track_nicename     = $item->getElementsByTagName( 'category' )->item( 0 )->getAttribute( 'nicename' );

				// Add CSV row.
				$csv_data = array( $title, $content, $excerpt, $post_name, $session_time, $session_duration, $session_type, $session_slides, $session_video, $session_speaker_id, $track, $track_nicename );
				break;
			case 'wcb_volunteer':
				$title           = $item->getElementsByTagName( 'title' )->item( 0 )->nodeValue;
				$content         = $item->getElementsByTagNameNS( $this->ns_content, 'encoded' )->item( 0 )->nodeValue;
				$excerpt         = $item->getElementsByTagNameNS( $this->ns_excerpt, 'encoded' )->item( 0 )->nodeValue;
				$post_name       = $item->getElementsByTagNameNS( $this->ns_wp, 'post_name' )->item( 0 )->nodeValue;
				$wp_user_name    = $this->get_post_meta( '_wcpt_user_name', $item );
				$volunteer_email = $this->get_post_meta( '_wcb_volunteer_email', $item );
				$is_first_time   = $this->get_post_meta( '_wcb_volunteer_first_time', $item );

				// Add CSV row.
				$csv_data = array( $title, $content, $excerpt, $post_name, $wp_user_name, $volunteer_email, $is_first_time );
				break;
			case 'wcb_sponsor':
				$title           = $item->getElementsByTagName( 'title' )->item( 0 )->nodeValue;
				$content         = $item->getElementsByTagNameNS( $this->ns_content, 'encoded' )->item( 0 )->nodeValue;
				$excerpt         = $item->getElementsByTagNameNS( $this->ns_excerpt, 'encoded' )->item( 0 )->nodeValue;
				$post_name       = $item->getElementsByTagNameNS( $this->ns_wp, 'post_name' )->item( 0 )->nodeValue;
				$company_name    = $this->get_post_meta( '_wcpt_sponsor_company_name', $item );
				$website         = $this->get_post_meta( '_wcpt_sponsor_website', $item );
				$first_name      = $this->get_post_meta( '_wcpt_sponsor_first_name', $item );
				$last_name       = $this->get_post_meta( '_wcpt_sponsor_last_name', $item );
				$email_address   = $this->get_post_meta( '_wcpt_sponsor_email_address', $item );
				$phone_number    = $this->get_post_meta( '_wcpt_sponsor_phone_number', $item );
				$street_address1 = $this->get_post_meta( '_wcpt_sponsor_street_address1', $item );
				$city            = $this->get_post_meta( '_wcpt_sponsor_city', $item );
				$state           = $this->get_post_meta( '_wcpt_sponsor_state', $item );
				$zip_code        = $this->get_post_meta( '_wcpt_sponsor_zip_code', $item );
				$country         = $this->get_post_meta( '_wcpt_sponsor_country', $item );

				// Add CSV row.
				$csv_data = array( $title, $content, $excerpt, $post_name, $company_name, $website, $first_name, $last_name, $email_address, $phone_number, $street_address1, $city, $state, $zip_code, $country );
				break;
		}

		return $csv_data;
	}

	/**
	 * Convert XML to CSV.
	 *
	 * @param string $data_type XML data type.
	 *
	 * @return string|WP_Error CSV string or WP_Error object.
	 */
	public function convert_2_csv( string $data_type ) {
		// Add CSV headers based on data type.
		$csv_headers = $this->csv_headers( $data_type );

		$csv_file = fopen( 'php://temp', 'w' ); // phpcs:ignore

		fputcsv( $csv_file, $csv_headers );

		$item_nodes = $this->dom_document->getElementsByTagName( 'item' );

		foreach ( $item_nodes as $item ) {
			$csv_data = $this->get_csv_data( $item, $data_type );

			fputcsv( $csv_file, $csv_data );
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
	 * @param string $data_type XML data type.
	 *
	 * @return string
	 */
	public function write_csv( string $csv_data, string $data_type ) {
		$filename = 'camptix-' . $data_type . '-' . gmdate( 'Y-m-d' ) . '.csv';

		$csv = fopen( CAMPTIX_XML_CSV_UPLOAD_DIR . $filename, 'w' ); // phpcs:ignore
		fwrite( $csv, $csv_data ); // phpcs:ignore
		fclose( $csv ); // phpcs:ignore

		return $filename;
	}
}
