document.addEventListener( 'DOMContentLoaded', function() {
	const form_camptix_api_csv  = document.getElementById( 'camptix-api-csv-form' );
	const input_camptix_api_csv = document.getElementById( 'api-url' );

	form_camptix_api_csv.addEventListener( 'submit', function( event ) {
		const url = input_camptix_api_csv.value;
		if ( ! url.startsWith( 'https://' ) ) {
			event.preventDefault();
			//alert( 'La URL debe comenzar con "https://" y no debe contener "wp-json".' );
			alert( camptix_xml_csv_i18n.https );
		} else if ( url.includes( 'wp-json' ) ) {
			event.preventDefault();
			//alert( 'La URL no debe contener "wp-json".' );
			alert( camptix_xml_csv_i18n.no_wp_json );
		}
	} );

} );
