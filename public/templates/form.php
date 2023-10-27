<div class="camptix-xml-csv-form">
	<form method="post" enctype="multipart/form-data">
		<label for="xml-file"><?php esc_html_e( 'Choose an XML file:', 'camptix-xml-csv' ); ?></label>
		<input type="file" id="xml-file" name="xml_file" required>
		<label for="data-type"><?php esc_html_e( 'Choose the data type:', 'camptix-xml-csv' ); ?></label>
		<select id="data-type" name="data_type" required>
			<option value="organizers"><?php esc_html_e( 'Organizers', 'camptix-xml-csv' ); ?></option>
			<option value="volunteers"><?php esc_html_e( 'Volunteers', 'camptix-xml-csv' ); ?></option>
			<option value="sponsors"><?php esc_html_e( 'Sponsors', 'camptix-xml-csv' ); ?></option>
		</select>
		<button type="submit"><?php esc_html_e( 'Export to CSV', 'camptix-xml-csv' ); ?></button>
	</form>
</div>
