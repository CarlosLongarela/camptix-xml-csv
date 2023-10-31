<?php
/**
 * This file exports a class CSV_Converter which has a method convert that takes an XML file path and returns a CSV string.
 * The class uses the xml_parser function to parse the XML file and extract the necessary data.
 *
 * @package Camptix_XML_CSV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CSV_Converter
 */
class Camptix_XML_CSV_Converter {
	use Camptix_Common; // Use Common Trait.

	/**
	 * DOM document object
	 *
	 * @var DOMDocument
	 */
	private $dom_document = null;

	/**
	 * DOM XPath object
	 *
	 * @var DOMXPath
	 */
	private $dom_xpath = null;

	/**
	 * Excerpt namespace
	 *
	 * @var string
	 */
	private $ns_excerpt = 'http://wordpress.org/export/1.2/excerpt/';

	/**
	 * Content namespace
	 *
	 * @var string
	 */
	private $ns_content = 'http://purl.org/rss/1.0/modules/content/';

	/**
	 * Comments namespace
	 *
	 * @var string
	 */
	private $ns_wfw = 'http://wellformedweb.org/CommentAPI/';

	/**
	 * Dc namespace
	 *
	 * @var string
	 */
	private $ns_dc = 'http://purl.org/dc/elements/1.1/';

	/**
	 * WordPress namespace
	 *
	 * @var string
	 */
	private $ns_wp = 'http://wordpress.org/export/1.2/';

	/**
	 * CPT Types
	 *
	 * @var array
	 */
	private $valid_cpt_types = array(
		'wcb_organizer',
		'wcb_speaker',
		'wcb_session',
		'wcb_volunteer',
		'wcb_sponsor',
	);

	/**
	 * CSV_Converter constructor.
	 *
	 * @param string $xml_data XML data to convert.
	 */
	public function __construct( string $xml_data ) {
		$this->dom_document = new DOMDocument();

		$this->dom_document->preserveWhiteSpace = false;
		$this->dom_document->loadXML( $xml_data );

		$this->dom_xpath = new DOMXPath( $this->dom_document );
		$this->dom_xpath->registerNamespace( 'wp', $this->ns_wp );
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
				$csv_headers = array( 'Title', 'Content', 'Excerpt', 'Post Name' );
				break;
			case 'wcb_speaker':
				$csv_headers = array( 'Title', 'Content', 'Excerpt', 'Post Name', 'User Email', 'WP User Name' );
				break;
			case 'wcb_session':
				$csv_headers = array( 'Title', 'Content', 'Excerpt', 'Post Name' );
				break;
			case 'wcb_volunteer':
				$csv_headers = array( 'Title', 'Content', 'Excerpt', 'Post Name' );
				break;
			case 'wcb_sponsor':
				$csv_headers = array( 'Title', 'Content', 'Excerpt', 'Post Name', 'Company Name', 'Website', 'First Name', 'Last Name', 'Email Address', 'Phone Number', 'Street Address', 'City', 'State', 'Zip Code', 'Country' );
				break;
			default:
				$csv_headers = array( 'Title', 'Content', 'Excerpt', 'Post Name' );
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
				$title        = $item->getElementsByTagName( 'title' )->item( 0 )->nodeValue;
				$content      = $item->getElementsByTagNameNS( $this->ns_content, 'encoded' )->item( 0 )->nodeValue;
				$excerpt      = $item->getElementsByTagNameNS( $this->ns_excerpt, 'encoded' )->item( 0 )->nodeValue;
				$post_name    = $item->getElementsByTagNameNS( $this->ns_wp, 'post_name' )->item( 0 )->nodeValue;
				$user_email   = $this->get_post_meta( '_wcb_speaker_email', $item );
				$wp_user_name = $this->get_post_meta( '_wcpt_user_name', $item );

				// Add CSV row.
				$csv_data = array( $title, $content, $excerpt, $post_name, $user_email, $wp_user_name );
				break;
			case 'wcb_session':
				$csv_data = array();
				break;
			case 'wcb_volunteer':
				$csv_data = array();
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
	 * Download CSV file.
	 *
	 * @param string $csv_data  CSV data to download.
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
