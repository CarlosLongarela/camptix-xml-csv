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
