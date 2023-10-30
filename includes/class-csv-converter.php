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
				$csv_headers = array( 'Title', 'Content', 'Excerpt', 'Post Name' );
				break;
			case 'wcb_session':
				$csv_headers = array( 'Title', 'Content', 'Excerpt', 'Post Name' );
				break;
			case 'wcb_volunteer':
				$csv_headers = array( 'Title', 'Content', 'Excerpt', 'Post Name' );
				break;
			case 'wcb_sponsor':
				$csv_headers = array( 'Title', 'Content', 'Excerpt', 'Post Name' );
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
	private function csv_data( $item, $data_type ) {
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
				$csv_data = array();
				break;
			case 'wcb_session':
				$csv_data = array();
				break;
			case 'wcb_volunteer':
				$csv_data = array();
				break;
			case 'wcb_sponsor':
				$csv_data = array();
				break;
			default:
				$csv_data = array();
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
			$csv_data = $this->csv_data( $item, $data_type );

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
	 * @param string $csv_data CSV data to download.
	 *
	 * @return void
	 */
	public function download_csv( string $csv_data ) {
		$filename = 'camptix-' . gmdate( 'Y-m-d' ) . '.csv';

		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );

		echo $csv_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		exit;
	}
}
