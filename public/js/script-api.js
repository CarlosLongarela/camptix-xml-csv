document.addEventListener( 'DOMContentLoaded', function() {
	const form_camptix_api_csv  = document.getElementById( 'camptix-api-csv-form' );
	const input_camptix_api_csv = document.getElementById( 'api-url' );

	function remove_message() {
		const msg = document.getElementById( 'camptix-msg' );
		if ( msg ) {
			msg.remove();
		}
	}

	form_camptix_api_csv.addEventListener( 'submit', function( event ) {
		const url = input_camptix_api_csv.value;

		remove_message();

		if ( ! url.startsWith( 'https://' ) ) {
			event.preventDefault();
			alert( camptix_xml_csv_i18n.https );
		} else if ( url.includes( 'wp-json' ) ) {
			event.preventDefault();
			alert( camptix_xml_csv_i18n.no_wp_json );
		}
	} );

} );
