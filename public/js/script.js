document.addEventListener( 'DOMContentLoaded', function() {
	const btn_convert_xml_2_csv = document.getElementById( 'btn-convert-xml-2-csv' );
	const btn_convert_csv_2_xml = document.getElementById( 'btn-convert-csv-2-xml' );
	const form_camptix_csv_xml  = document.getElementById( 'camptix-csv-xml-form' );
	const form_camptix_xml_csv  = document.getElementById( 'camptix-xml-csv-form' );

	function hide_forms() {
		form_camptix_csv_xml.classList.add( 'camptix-hidden' );
		form_camptix_xml_csv.classList.add( 'camptix-hidden' );
	}

	function remove_message() {
		const msg = document.getElementById( 'camptix-msg' );
		if ( msg ) {
			msg.remove();
		}
	}

	btn_convert_xml_2_csv.addEventListener( 'click', function() {
		hide_forms();
		remove_message();
		form_camptix_xml_csv.classList.remove( 'camptix-hidden' );
	} );

	btn_convert_csv_2_xml.addEventListener( 'click', function() {
		hide_forms();
		remove_message();
		form_camptix_csv_xml.classList.remove( 'camptix-hidden' );
	} );

} );
