<?php
/**
 * Plugin Name: Camptix-xml-csv
 * Plugin URI: https://europe.wordcamp.org/
 * Description: A plugin that converts uploaded XML files to CSV format while maintaining only specific data fields.
 * Version: 1.0.0
 * Author: Carlos Longarela, WordCamp Europe
 * Author URI: https://europe.wordcamp.org/
 * License: GPL2
 */

// Load necessary files
require_once CAMPTIX_XML_CSV_DIR . 'includes/csv-converter.php';
require_once CAMPTIX_XML_CSV_DIR . 'includes/xml-parser.php';

// Define shortcode and form submission function
function camptix_xml_csv_shortcode() {
	ob_start();
	include CAMPTIX_XML_CSV_DIR . 'public/templates/shortcode.php';
	return ob_get_clean();
}
add_shortcode( 'camptix_xml_csv', 'camptix_xml_csv_shortcode' );

function camptix_xml_csv_handle_form_submission() {
	if ( isset( $_POST['camptix_xml_csv_submit'] ) ) {
		// Validate form data.
		if ( ! wp_verify_nonce( $_POST['camptix_xml_csv_nonce'], 'camptix_xml_csv' ) ) {
			wp_die( __( 'Security check failed.', 'camptix-xml-csv' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'camptix-xml-csv' ) );
		}

		if ( ! isset( $_FILES['camptix_xml_csv_file'] ) || ! $_FILES['camptix_xml_csv_file']['tmp_name'] ) {
			wp_die( __( 'Please upload a file.', 'camptix-xml-csv' ) );
		}

		// Convert XML to CSV
		$csv_converter = new CSV_Converter();
		$csv_data      = $csv_converter->convert_2_csv( $_FILES['camptix_xml_csv_file']['tmp_name'] );

		// Download CSV file
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment; filename="camptix-xml-csv.csv"' );
		echo $csv_data;
		exit;
	}
}
add_action( 'init', 'camptix_xml_csv_handle_form_submission' );
