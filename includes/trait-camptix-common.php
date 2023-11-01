<?php
/**
 * Common trait functions.
 *
 * @since      1.0.0
 *
 * @package Camptix_XML_CSV
 */

/**
 * Trait Camptix_Common
 */
trait Camptix_Common {

	/**
	 * WordPress namespace
	 *
	 * @var string
	 */
	protected $ns_wp = 'http://wordpress.org/export/1.2/';

	/**
	 * Excerpt namespace
	 *
	 * @var string
	 */
	protected $ns_excerpt = 'http://wordpress.org/export/1.2/excerpt/';

	/**
	 * Content namespace
	 *
	 * @var string
	 */
	protected $ns_content = 'http://purl.org/rss/1.0/modules/content/';

	/**
	 * Comments namespace
	 *
	 * @var string
	 */
	protected $ns_wfw = 'http://wellformedweb.org/CommentAPI/';

	/**
	 * Dc namespace
	 *
	 * @var string
	 */
	protected $ns_dc = 'http://purl.org/dc/elements/1.1/';

	/**
	 * CPT Types
	 *
	 * @var array
	 */
	protected $valid_cpt_types = array(
		'wcb_organizer',
		'wcb_speaker',
		'wcb_session',
		'wcb_volunteer',
		'wcb_sponsor',
	);

	/**
	 * CSV hedaers por CPT wcb_organizer.
	 *
	 * @var array
	 */
	protected $csv_headers_wcb_organizer = array( 'Title', 'Content', 'Excerpt', 'Post Name' );

	/**
	 * CSV hedaers por CPT wcb_speaker.
	 *
	 * @var array
	 */
	protected $csv_headers_wcb_speaker = array( 'Speaker ID', 'Title', 'Content', 'Excerpt', 'Post Name', 'User Email', 'WP User Name', 'Is First Time' );

	/**
	 * CSV hedaers por CPT wcb_session.
	 *
	 * @var array
	 */
	protected $csv_headers_wcb_session = array( 'Title', 'Content', 'Excerpt', 'Post Name', 'Session Time', 'Session Duration in seconds', 'Session Type', 'Session Slides', 'Session Video', 'Session Speaker ID', 'Track', 'Track Nicename' );

	/**
	 * CSV hedaers por CPT wcb_volunteer.
	 *
	 * @var array
	 */
	protected $csv_headers_wcb_volunteer = array( 'Title', 'Content', 'Excerpt', 'Post Name', 'WP User Name', 'Volunteer Email', 'Is First Time' );

	/**
	 * CSV hedaers por CPT wcb_sponsor.
	 *
	 * @var array
	 */
	protected $csv_headers_wcb_sponsor = array( 'Title', 'Content', 'Excerpt', 'Post Name', 'Company Name', 'Website', 'First Name', 'Last Name', 'Email Address', 'Phone Number', 'Street Address', 'City', 'State', 'Zip Code', 'Country' );

	/**
	 * Return error associate with PHP upload error.
	 *
	 * @param string $file_error String with the file error constant.
	 *
	 * @return string
	 */
	private function upload_error( string $file_error ): string {
		switch ( $file_error ) {
			case UPLOAD_ERR_INI_SIZE:
				$message = __( 'The uploaded file exceeds the upload_max_filesize directive in php.ini', 'camptix-xml-csv' );
				break;
			case UPLOAD_ERR_FORM_SIZE:
				$message = __( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'camptix-xml-csv' );
				break;
			case UPLOAD_ERR_PARTIAL:
				$message = __( 'The uploaded file was only partially uploaded', 'camptix-xml-csv' );
				break;
			case UPLOAD_ERR_NO_FILE:
				$message = __( 'No file was uploaded', 'camptix-xml-csv' );
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$message = __( 'Missing a temporary folder', 'camptix-xml-csv' );
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$message = __( 'Failed to write file to disk', 'camptix-xml-csv' );
				break;
			case UPLOAD_ERR_EXTENSION:
				$message = __( 'File upload stopped by extension', 'camptix-xml-csv' );
				break;
			default:
				$message = __( 'Unknown upload error', 'camptix-xml-csv' );
				break;
		}

		return $message;
	}

	/**
	 * Move uploaded file to user directory.
	 *
	 * @param array $file     Array of uploaded file.
	 *
	 * @return string|WP_Error
	 */
	protected function move_uploaded_file( array $file ) {
		$upload_dir     = wp_upload_dir();
		$upload_path    = $upload_dir['basedir'];
		$dest_file_name = $upload_path . '/camptix/' . gmdate( 'Y_m_d-H_i_s' ) . '-' . $file['name'];

		if ( ! is_uploaded_file( $file['tmp_name'] ) ) {
			return new WP_Error( 'Filesystem error', __( 'File is not a uploaded file', 'camptix-xml-csv' ) );
		}

		if ( UPLOAD_ERR_OK === $file['error'] && move_uploaded_file( $file['tmp_name'], $dest_file_name ) ) {
			return $dest_file_name;
		} else {
			return new WP_Error( 'File upload error', $this->upload_error( $file['error'] ) );
		}
	}
}
