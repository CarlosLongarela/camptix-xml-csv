<?php
/**
 * Process the form
 *
 * @package Camptix_XML_CSV
 */

// Check the nonce.
if ( ! isset( $_POST['camptix_api_csv_nonce'] ) || ! wp_verify_nonce( $_POST['camptix_api_csv_nonce'], 'camptix_api_csv_nonce' ) ) {
	wp_die( esc_html__( 'Security check failed.', 'camptix-xml-csv' ) );
}

require_once CAMPTIX_XML_CSV_DIR . 'includes/trait-camptix-common.php';

require_once CAMPTIX_XML_CSV_DIR . 'includes/class-camptix-api-2-csv-converter.php';

$api_base_url = esc_url( $_POST['api_url'] );
$data_type    = esc_attr( $_POST['api_type'] );

$converter = new Camptix_API_2_CSV_Converter( $api_base_url, $data_type );

$data = $converter->get_api_data();

if ( is_wp_error( $data ) ) {
	echo '<div id="camptix-msg" class="camptix-error">' . esc_html__( 'Error:', 'camptix-xml-csv' ) . ' ' . esc_html( $data->get_error_message() ) . '</div>';
} else {
	$csv_data = $converter->convert_2_csv( $data );
	$csv_link = $converter->write_csv( $csv_data );

	echo '<div id="camptix-msg" class="camptix-success">' . esc_html__( 'CSV file created successfully:', 'camptix-xml-csv' ) . ' ';
	echo '<a href="' . esc_url( CAMPTIX_XML_CSV_UPLOAD_URL . $csv_link ) . '" download="' . esc_attr( $csv_link ) . '">' . esc_html__( 'Download CSV file', 'camptix-xml-csv' ) . '</a> <em>(' . esc_attr( $csv_link ) . ')</em></div>';
}
