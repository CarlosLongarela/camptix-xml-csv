<?php
/**
 * Form to convert a CSV file to XML
 *
 * @package Camptix_XML_CSV
 */

?>
<div id="camptix-csv-xml-form" class="camptix-csv-xml-form">
	<form method="post" enctype="multipart/form-data" class="is-layout-flex camptix-form">
		<?php
		wp_nonce_field( 'camptix_csv_xml_nonce', 'camptix_csv_xml_nonce' );
		?>

		<input type="hidden" id="file-type" name="file_type" value="csv_2_xml">

		<p class="camptix_form_text"><?php esc_html_e( 'Convert a CSV from Google Spreadsheets, Excel, Libre Office Calc or similar tou import in WordPress.', 'camptix-xml-csv' ); ?></p>

		<div class="camptix-form-item">
			<label for="csv-file"><?php esc_html_e( 'Choose an CSV file:', 'camptix-xml-csv' ); ?></label>
			<input type="file" id="csv-file" name="csv_file" accept="text/csv" required>
		</div>

		<div class="camptix-form-item">
			<button type="submit"><?php esc_html_e( 'Export to XML', 'camptix-xml-csv' ); ?></button>
		</div>
	</form>
</div>
