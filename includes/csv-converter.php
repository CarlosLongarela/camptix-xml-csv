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
class CSV_Converter {

	private $post_type = '';

	private function get_xml_type() {
		$type = $_POST['data_type'];

		switch ( $type ) {
			case 'organizer':
				$type = 'wcb_organizer';
				break;
			case 'speaker':
				$type = 'wcb_speaker';
				break;
			case 'session':
				$type = 'wcb_session';
				break;
			case 'volunteer':
				$type = 'wcb_volunteer';
				break;
			case 'sponsor':
				$type = 'wcb_sponsor';
				break;
			default:
				$type = '';
				break;
		}

		$this->post_type = $type;
	}

	/**
	 * Convert XML to CSV.
	 *
	 * @param string $xml_file_path Path to the XML file.
	 *
	 * @return string|WP_Error CSV string or WP_Error object.
	 */
	public function convert_2_csv( string $xml_file_path ) {
		$xml_data = xml_parser( $xml_file_path );

		if ( is_wp_error( $xml_data ) ) {
			return $xml_data;
		}

		$csv_file = fopen('php://output', 'w');

		// Add CSV headers.
		$csv_headers = array( 'Title', 'Content', 'Excerpt', 'Post Name' );
		fputcsv( $csv_file, $csv_headers );

		foreach ( $xml_data as $item ) {
			$title     = isset( $item['title'] ) ? $item['title'] : '';
			$content   = isset( $item['content'] ) ? $item['content'] : '';
			$excerpt   = isset( $item['excerpt'] ) ? $item['excerpt'] : '';
			$post_name = isset( $item['post_name'] ) ? $item['post_name'] : '';

			// Escape special characters.
			$title     = $this->replace_csv_special_chars( $title );
			$content   = $this->replace_csv_special_chars( $content );
			$excerpt   = $this->replace_csv_special_chars( $excerpt );
			$post_name = $this->replace_csv_special_chars( $post_name );

			// Add CSV row.
			$data = array( $title, $content, $excerpt, $post_name );
			fputcsv( $csv_file, $data );
		}

		$csv_out = stream_get_contents( $csv_file );
		fclose( $csv_out );

		return $csv_out;
	}

	/**
	 * Replace special characters in a string.
	 *
	 * @param string $string The string to replace special characters in.
	 *
	 * @return string The string with special characters replaced.
	 */
	private function replace_csv_special_chars( string $string ) {
		return str_replace( '"', '""', $string );
	}
}
