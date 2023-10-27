<?php
/**
 * The template for the shortcode.
 *
 * This template can be overridden by copying it to yourtheme/camptix-xml-csv/shortcode.php.
 *
 * @package Camptix_XML_CSV
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get the types of data to export.
$types = array(
	'organizers' => __( 'Organizers', 'camptix-xml-csv' ),
	'volunteers' => __( 'Volunteers', 'camptix-xml-csv' ),
	'sponsors'   => __( 'Sponsors', 'camptix-xml-csv' ),
);

// Get the current page URL.
$page_url = esc_url( add_query_arg( null, null ) );

// Get the nonce field.
$nonce_field = wp_nonce_field( 'camptix_xml_csv_export', 'camptix_xml_csv_nonce', true, false );

// Get the select field.
$select_field = sprintf(
	'<select name="camptix_xml_csv_type" id="camptix-xml-csv-type">%s</select>',
	implode( '', array_map( function( $value, $label ) {
		return sprintf(
			'<option value="%s">%s</option>',
			esc_attr( $value ),
			esc_html( $label )
		);
	}, array_keys( $types ), $types ) )
);

// Get the submit button.
$submit_button = sprintf(
	'<button type="submit" name="camptix_xml_csv_submit" id="camptix-xml-csv-submit" class="button">%s</button>',
	esc_html__( 'Export', 'camptix-xml-csv' )
);

// Output the form.
printf(
	'<form action="%s" method="post" id="camptix-xml-csv-form">%s%s%s</form>',
	esc_url( $page_url ),
	$nonce_field,
	$select_field,
	$submit_button
);
