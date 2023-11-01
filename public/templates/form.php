<?php
/**
 * Form to convert a XML file to CSV
 *
 * @package Camptix_XML_CSV
 */

?>
<div class="wp-block-buttons is-layout-flex is-content-justification-center camptix-wceu-btns">
	<div class="wp-block-button">
		<a id="btn-convert-xml-2-csv" class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Convert XML to CSV', 'camptix-xml-csv' ); ?></a>
	</div>
	<div class="wp-block-button">
		<a id="btn-convert-csv-2-xml" class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Convert CSV to XML', 'camptix-xml-csv' ); ?></a>
	</div>
</div>

<div id="camptix-xml-csv-form" class="camptix-xml-csv-form">
	<form method="post" enctype="multipart/form-data" class="is-layout-flex camptix-form">
		<?php
		wp_nonce_field( 'camptix_csv_xml_nonce', 'camptix_csv_xml_nonce' );
		?>

		<input type="hidden" id="file-type" name="file_type" value="xml_2_csv">

		<p class="camptix_form_text"><?php esc_html_e( 'Convert a XML file downloaded from WordPress export option and convert it to CSV to edit with Google Spreadsheets, Excel, Libre Office Calc or similar.', 'camptix-xml-csv' ); ?></p>

		<div class="camptix-form-item">
			<label for="xml-file"><?php esc_html_e( 'Choose an XML file:', 'camptix-xml-csv' ); ?></label>
			<input type="file" id="xml-file" accept="text/xml" name="xml_file" required>
		</div>

		<div class="camptix-form-item">
			<button type="submit"><?php esc_html_e( 'Export to CSV', 'camptix-xml-csv' ); ?></button>
		</div>
	</form>
</div>

<div id="camptix-csv-xml-form" class="camptix-csv-xml-form camptix-hidden">
	<form method="post" enctype="multipart/form-data" class="is-layout-flex camptix-form">
		<?php
		wp_nonce_field( 'camptix_csv_xml_nonce', 'camptix_csv_xml_nonce' );
		?>

		<input type="hidden" id="file-type" name="file_type" value="csv_2_xml">

		<p class="camptix_form_text"><?php esc_html_e( 'Convert a CSV from Google Spreadsheets, Excel, Libre Office Calc or similar to import in WordPress.', 'camptix-xml-csv' ); ?></p>

		<div class="camptix-form-item">
			<label for="csv-file"><?php esc_html_e( 'Choose an CSV file:', 'camptix-xml-csv' ); ?></label>
			<input type="file" id="csv-file" name="csv_file" accept="text/csv" required>
		</div>

		<div class="camptix-form-item">
			<button type="submit"><?php esc_html_e( 'Export to XML', 'camptix-xml-csv' ); ?></button>
		</div>
	</form>
</div>
