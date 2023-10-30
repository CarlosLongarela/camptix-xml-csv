document.addEventListener( 'DOMContentLoaded', function() {
	const btn_convert_xml_2_csv = document.getElementById( 'btn-convert-xml-2-csv' );
	const btn_convert_csv_2_xml = document.getElementById( 'btn-convert-csv-2-xml' );
	const camptix_xml_csv_forms = document.getElementById( 'camptix-xml-csv-forms' );

	btn_convert_xml_2_csv.addEventListener( 'click', function() {
		fetch('/wp-admin/admin-ajax.php?action=load_xml_2_csv_form', {
			method: 'GET',
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		} )
			.then( response => response.text() )
			.then( data => {
				camptix_xml_csv_forms.innerHTML = data;
		} )
			.catch( error => console.error( error ) );
	} );

	btn_convert_csv_2_xml.addEventListener( 'click', function() {
		fetch('/wp-admin/admin-ajax.php?action=load_csv_2_xml_form', {
			method: 'GET',
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		} )
			.then( response => response.text() )
			.then( data => {
				camptix_xml_csv_forms.innerHTML = data;
		} )
			.catch( error => console.error( error ) );
	} );

} );
