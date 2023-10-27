<?php
/**
 * Plugin Name: Camptix XML CSV
 * Plugin URI: https://europe.wordcamp.org/
 * Description: A plugin that converts uploaded XML files to CSV format.
 * Version: 1.0.0
 * Author: Carlos Longarela, WordCamp Europe
 * Author URI: https://europe.wordcamp.org/
 * License: GPL2
 */

if ( ! function_exists('xml_parser') ) {
	wp_die( __( 'Simplexml PHP extension not available. You need this extension available to use this plugin.', 'camptix-xml-csv' ) );
}

define( 'CAMPTIX_XML_CSV_DIR', plugin_dir_path( __FILE__ ) );
define( 'CAMPTIX_XML_CSV_URL', plugin_dir_url( __FILE__ ) );

// Load the necessary files
require_once CAMPTIX_XML_CSV_DIR . 'includes/csv-converter.php';
require_once CAMPTIX_XML_CSV_DIR . 'includes/xml-parser.php';

/**
 * Function to handle the form submission and display the CSV data.
 */
function camptix_xml_csv_shortcode() {
	// Check if the form was submitted
	if ( isset( $_POST['submit'] ) ) {
		// Get the uploaded file
		$file = $_FILES['file'];

		// Check if the file is an XML file
		if ( $file['type'] === 'text/xml' ) {
			// Get the type of data to export
			$type = $_POST['type'];

			// Convert the XML file to CSV
			$csv_converter = new CSV_Converter();
			$csv_data = $csv_converter->convert_2_csv( $file['tmp_name'], $type );

			// Display the CSV data
			echo '<pre>' . $csv_data . '</pre>';
		} else {
			// Display an error message
			echo '<p class="error">Please upload an XML file.</p>';
		}
	}

	// Display the form
	include plugin_dir_path( __FILE__ ) . 'public/templates/form.php';
}
// Define the shortcode
add_shortcode( 'camptix_xml_csv', 'camptix_xml_csv_shortcode' );
