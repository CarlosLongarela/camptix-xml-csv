<?php
/**
 * Process the form
 *
 * @package Camptix_XML_CSV
 */

// Check the nonce.
if ( ! isset( $_POST['camptix_csv_xml_nonce'] ) || ! wp_verify_nonce( $_POST['camptix_csv_xml_nonce'], 'camptix_csv_xml_nonce' ) ) {
	wp_die( esc_html__( 'Security check failed.', 'camptix-xml-csv' ) );
}

$convert_2_csv = false;
$convert_2_xml = false;

if ( 'xml_2_csv' === $_POST['file_type'] ) {
	if ( 'text/xml' === $_FILES['xml_file']['type'] ) {
		$convert_2_csv = true;
	} else {
		$res = new WP_Error( 'File type no XML', __( 'Please upload an XML file.', 'camptix-xml-csv' ) );
		echo '<div class="camptix-error">' . esc_html( $res->get_error_message() ) . '</div>';
	}
} elseif ( 'csv_2_xml' === $_POST['file_type'] ) {
	if ( 'text/csv' === $_FILES['csv_file']['type'] ) {
		$convert_2_xml = true;
	} else {
		$res = new WP_Error( 'File type no XML', __( 'Please upload an CSV file.', 'camptix-xml-csv' ) );
		echo '<div class="camptix-error">' . esc_html( $res->get_error_message() ) . '</div>';
	}
}

require_once CAMPTIX_XML_CSV_DIR . 'includes/trait-camptix-common.php';

if ( $convert_2_csv ) {
	require_once CAMPTIX_XML_CSV_DIR . 'includes/class-camptix-xml-2-csv-converter.php';

	$xml_file    = $_FILES['xml_file']['tmp_name'];
	$xml_content = file_get_contents( $xml_file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	$converter = new Camptix_XML_2_CSV_Converter( $xml_content );
	$data_type = $converter->get_xml_type();

	if ( false !== $data_type ) {
		$csv_data = $converter->convert_2_csv( $data_type );
		$csv_link = $converter->write_csv( $csv_data, $data_type );
		echo '<div class="camptix-success">' . esc_html__( 'CSV file created successfully:', 'camptix-xml-csv' ) . ' ';
		echo '<a href="' . esc_url( CAMPTIX_XML_CSV_UPLOAD_URL . $csv_link ) . '" download>' . esc_html__( 'Download CSV file', 'camptix-xml-csv' ) . '</a></div>';
	} else {
		$res = new WP_Error( 'File type no XML', __( 'Please select a valid data type.', 'camptix-xml-csv' ) );
		echo '<div class="camptix-error">' . esc_html( $res->get_error_message() ) . '</div>';
	}
}

if ( $convert_2_xml ) {
	require_once CAMPTIX_XML_CSV_DIR . 'includes/class-camptix-csv-2-xml-converter.php';

	$csv_file      = $_FILES['csv_file']['tmp_name'];
	$csv_file_name = $_FILES['csv_file']['name'];

	$converter = new Camptix_CSV_2_XML_Converter();
	$data_type = $converter->get_csv_type( $csv_file_name );

	if ( false !== $data_type ) {
		$xml_data = $converter->convert_2_xml( $csv_file, $data_type );
		$xml_link = $converter->write_xml( $xml_data, $data_type );
		echo '<div class="camptix-success">' . esc_html__( 'XML file created successfully:', 'camptix-xml-csv' ) . ' ';
		echo '<a href="' . esc_url( CAMPTIX_XML_CSV_UPLOAD_URL . $xml_link ) . '" download>' . esc_html__( 'Download XML file', 'camptix-xml-csv' ) . '</a></div>';
	} else {
		$res = new WP_Error( 'CSV file name incorrect', __( 'Please select a valid CSV file. Check plugin README for valid names.', 'camptix-xml-csv' ) );
		echo '<div class="camptix-error">' . esc_html( $res->get_error_message() ) . '</div>';
	}
}
