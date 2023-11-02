<?php
/**
 * Plugin Name: Camptix XML CSV
 * Plugin URI: https://europe.wordcamp.org/
 * Description: A plugin to converts uploaded XML files to CSV format and back again from CSV to XML. Based on idea and script from Pascal Casier (https://github.com/ePascalC).
 * Version: 1.0.1
 * Author: Carlos Longarela <carlos@longarela.eu>, WordCamp Europe
 * Author URI: https://tabernawp.com/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: camptix-xml-csv
 * Domain Path: /languages
 * Requires at least: 5.2
 * Requires PHP: 7.4
 * Tested up to: 6.3.2
 *
 * @package Camptix_XML_CSV
 */

define( 'CAMPTIX_XML_CSV_DIR', plugin_dir_path( __FILE__ ) );
define( 'CAMPTIX_XML_CSV_URL', plugin_dir_url( __FILE__ ) );
$upload_dir = wp_upload_dir();
define( 'CAMPTIX_XML_CSV_UPLOAD_DIR', $upload_dir['basedir'] . '/camptix-xml-csv/' );
define( 'CAMPTIX_XML_CSV_UPLOAD_URL', $upload_dir['baseurl'] . '/camptix-xml-csv/' );
define( 'CAMPTIX_XML_CSV_VERSION', '1.0.1' );

/**
 * Load the plugin textdomain.
 *
 * @return void
 */
function camptix_xml_csv_load_textdomain() {
	load_plugin_textdomain( 'camptix-xml-csv', false, basename( __DIR__ ) . '/languages' );
}
add_action( 'plugins_loaded', 'camptix_xml_csv_load_textdomain' );

/**
 * Create the custom folder to store the CSV and XML files if not exists.
 *
 * @return void
 */
function create_custom_plugin_dir() {
	if ( ! file_exists( CAMPTIX_XML_CSV_UPLOAD_DIR ) ) {
		wp_mkdir_p( CAMPTIX_XML_CSV_UPLOAD_DIR );
	}
}


/**
 * Delete all CSV and XML files from plugin custom folder.
 *
 * @return void
 */
function delete_files() {
	// Pattern to find CSV and XML files (change it to only * to delete all files).
	$pattern = CAMPTIX_XML_CSV_UPLOAD_DIR . '*.{csv,xml}';

	// Obtain a list with files maching pattern.
	$files = glob( $pattern, GLOB_BRACE );

	foreach ( $files as $file ) {
		if ( is_file( $file ) ) {
			wp_delete_file( $file );
		}
	}
}

/**
 * Function to handle the form submission and display the CSV data.
 */
function camptix_xml_csv_shortcode() {
	// Enqueue the CSS and JS files only when shortcode is used.
	wp_enqueue_style( 'camptix-xml-csv-style', CAMPTIX_XML_CSV_URL . 'public/css/style.css' );
	wp_enqueue_script( 'camptix-xml-csv-script', CAMPTIX_XML_CSV_URL . 'public/js/script.js', array(), CAMPTIX_XML_CSV_VERSION, true );

	create_custom_plugin_dir(); // Checks that plugin custom folder exists or create it.
	delete_files(); // Delete all CSV and XML files from plugin custom folder.

	ob_start();

	include CAMPTIX_XML_CSV_DIR . 'public/templates/form.php';

	// Check if the form was submitted.
	if ( isset( $_POST['file_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		include CAMPTIX_XML_CSV_DIR . 'includes/process-form.php';
	}

	return ob_get_clean();
}
// Define the shortcode.
add_shortcode( 'camptix_xml_csv', 'camptix_xml_csv_shortcode' );

/**
 * Function to handle the AJAX request to load the form to convert XML to CSV.
 */
function load_xml_2_csv_form() {
	include CAMPTIX_XML_CSV_DIR . 'public/templates/form-xml-2-csv.php';

	wp_die();
}
add_action( 'wp_ajax_load_xml_2_csv_form', 'load_xml_2_csv_form' );
add_action( 'wp_ajax_nopriv_load_xml_2_csv_form', 'load_xml_2_csv_form' );

/**
 * Function to handle the AJAX request to load the form to convert CSV to XML.
 */
function load_csv_2_xml_form() {
	include CAMPTIX_XML_CSV_DIR . 'public/templates/form-csv-2-xml.php';

	wp_die();
}
add_action( 'wp_ajax_load_csv_2_xml_form', 'load_csv_2_xml_form' );
add_action( 'wp_ajax_nopriv_load_csv_2_xml_form', 'load_csv_2_xml_form' );
