<?php
/**
 * Form to convert a JSON API response to CSV
 *
 * @package Camptix_XML_CSV
 */

?>

<div id="camptix-api-csv-form" class="camptix-api-csv-form">
	<form method="post" enctype="multipart/form-data" class="is-layout-flex camptix-form">
		<?php
		wp_nonce_field( 'camptix_api_csv_nonce', 'camptix_api_csv_nonce' );
		?>

		<input type="hidden" id="file-type" name="file_type" value="api_2_csv">

		<p class="camptix_form_text"><?php esc_html_e( 'Convert a JSON API response to CSV to edit with Google Spreadsheets, Excel, Libre Office Calc or similar.', 'camptix-xml-csv' ); ?></p>

		<div class="camptix-form-item">
			<label for="api-type"><?php esc_html_e( 'Select the API type:', 'camptix-xml-csv' ); ?></label>
			<select id="api-type" name="api_type" required>
				<option value="wcb_organizer"><?php esc_html_e( 'Organizers', 'camptix-xml-csv' ); ?></option>
				<option value="wcb_speaker"><?php esc_html_e( 'Speakers', 'camptix-xml-csv' ); ?></option>
				<option value="wcb_session"><?php esc_html_e( 'Sessions', 'camptix-xml-csv' ); ?></option>
				<option value="wcb_volunteer" disabled><?php esc_html_e( 'Volunteers', 'camptix-xml-csv' ); ?> (<?php esc_html_e( 'API route not available', 'camptix-xml-csv' ); ?>)</option>
				<option value="wcb_sponsor"><?php esc_html_e( 'Sponsors', 'camptix-xml-csv' ); ?></option>
			</select>
		</div>

		<div class="camptix-form-item">
			<label for="api-url"><?php esc_html_e( 'Enter the API URL base (without the API part):', 'camptix-xml-csv' ); ?></label>
			<input type="url" id="api-url" name="api_url" class="camptix_form_text" placeholder="https://europe.wordcamp.org/2024/" required>
		</div>

		<div class="camptix-form-item">
			<button type="submit"><?php esc_html_e( 'Export to CSV', 'camptix-xml-csv' ); ?></button>
		</div>
	</form>
</div>
