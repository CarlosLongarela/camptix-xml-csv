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
class Camptix_CSV_2_XML_Converter {
	use Camptix_Common; // Use Common Trait.

	/**
	 * DOM document object
	 *
	 * @var DOMDocument
	 */
	private $dom_document = null;

	/**
	 * CSV_Converter constructor.
	 */
	public function __construct() {
		$this->dom_document = new DOMDocument();

		$this->dom_document->preserveWhiteSpace = false;
	}

	/**
	 * Check if the type is valid based on file name.
	 *
	 * @param string $csv_file_name CSV file name.
	 *
	 * @return string|bool
	 */
	public function get_csv_type( string $csv_file_name ) {
		// Split the file name into parts.
		$file_name_parts = explode( '-', $csv_file_name );

		if ( ! isset( $file_name_parts[1] ) ) {
			return false;
		}

		// Check if the name is a valid CPT.
		if ( in_array( $file_name_parts[1], $this->valid_cpt_types, true ) ) {
			return $file_name_parts[1];
		} else {
			return false;
		}
	}

	/**
	 * Convert CSV to XML
	 *
	 * @param string $csv_file  CSV file path.
	 * @param string $data_type CSV data type.
	 *
	 * @return string|WP_Error XML string or WP_Error object.
	 */
	public function convert_2_xml( string $csv_file, string $data_type ) {
		//$dom = new DOMDocument();
		$dom = new DOMDocument( '1.0', 'UTF-8' );

		// Create a processing instruction.
		//$pi = $dom->createProcessingInstruction( 'xml', 'version="1.0" encoding="UTF-8"' );

		// Add the processing instruction to the DOMDocument.
		//$dom->appendChild( $pi );

		// Create the rss element.
		$rss = $dom->createElement( 'rss' );

		// Set the version attribute.
		$rss->setAttribute( 'version', '2.0' );

		// Set the namespace attributes.
		$rss->setAttribute( 'xmlns:excerpt', $this->ns_excerpt );
		$rss->setAttribute( 'xmlns:content', $this->ns_content );
		$rss->setAttribute( 'xmlns:wfw', $this->ns_wfw );
		$rss->setAttribute( 'xmlns:dc', $this->ns_dc );
		$rss->setAttribute( 'xmlns:wp', $this->ns_wp );

		// Create the channel element.
		$channel = $dom->createElement( 'channel' );



		/**
		 * Read CSV file and generate DOM structure.
		 */
		$row_number = 0;
		$csv_data   = fopen( $csv_file, 'r' ); // phpcs:ignore

		if ( false !== ( $csv_data ) ) {
			while ( false !== ( $data = fgetcsv( $csv_data ) ) ) {
				$cols = count( $data );
				echo "<p> $cols columns in row $row_number: <br /></p>\n";

				++$row_number;

				if ( $row_number > 1 ) {
					// Create the item element.
					$item = $dom->createElement( 'item' );

					// Create the title element with the value.
					$title      = $dom->createElement( 'title' );
					$title_text = $dom->createCDATASection( $data[0] );
					$title->appendChild( $title_text );
					$item->appendChild( $title );

					// Create the content element with the value.
					$content      = $dom->createElement( 'content:encoded' );
					$content_text = $dom->createCDATASection( $data[1] );
					$content->appendChild( $content_text );
					$item->appendChild( $content );

					// Create the excerpt element with the value.
					$excerpt      = $dom->createElement( 'excerpt:encoded' );
					$excerpt_text = $dom->createCDATASection( $data[2] );
					$excerpt->appendChild( $excerpt_text );
					$item->appendChild( $excerpt );

					// Create the post_name element with the value.
					$post_name      = $dom->createElement( 'wp:post_name' );
					$post_name_text = $dom->createCDATASection( $data[3] );
					$post_name->appendChild( $post_name_text );
					$item->appendChild( $post_name );

					$channel->appendChild( $item );
				}
//				for ( $i = 0; $i < $cols; $i++ ) {
//					if ( 1 === $row_number ) {
//						echo '<h2>Estoy en la cabecera</h2>';
//					} else {
//						echo $data[$i] . "<br />\n";
//
//
//					}
//				}
			}
			fclose( $csv_data ); // phpcs:ignore
		}

		// Add the channel element to the rss element.
		$rss->appendChild( $channel );

		// Add the rss element to the DOMDocument.
		$dom->appendChild( $rss );

		// Print the DOMDocument to the screen.
		echo '<textarea cols="10" rows="20">';
		echo $dom->saveXML();
		echo '</textarea>';

		//return $xml_out;
	}

	/**
	 * Write XML file.
	 *
	 * @param string $xml_data  XML data to write to file.
	 * @param string $data_type Data CPT type.
	 *
	 * @return string
	 */
	public function write_xml( string $xml_data, string $data_type ) {
		$filename = 'camptix-' . $data_type . '-' . gmdate( 'Y-m-d' ) . '.xml';

		$xml = fopen( CAMPTIX_XML_CSV_UPLOAD_DIR . $filename, 'w' ); // phpcs:ignore
		fwrite( $xml, $xml_data ); // phpcs:ignore
		fclose( $xml ); // phpcs:ignore

		return $filename;
	}
}
