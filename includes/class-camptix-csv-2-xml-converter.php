<?php
/**
 * This file exports a class CSV_Converter which has a method convert that takes an XML file path and returns a CSV string.
 * The class uses the xml_parser function to parse the XML file and extract the necessary data.
 *
 * @package Camptix_XML_CSV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CSV_Converter
 */
class Camptix_CSV_2_XML_Converter {
	use Camptix_Common; // Use Common Trait.

	/**
	 * DOM document object
	 *
	 * @var DOMDocument
	 */
	private $dom_document = null;

	/**
	 * CSV_Converter constructor.
	 */
	public function __construct() {
		$this->dom_document = new DOMDocument( '1.0', 'UTF-8' );

		$this->dom_document->preserveWhiteSpace = false;
	}

	/**
	 * Check if the type is valid based on file name.
	 *
	 * @param string $csv_file_name CSV file name.
	 *
	 * @return string|bool
	 */
	public function get_csv_type( string $csv_file_name ) {
		// Split the file name into parts.
		$file_name_parts = explode( '-', $csv_file_name );

		if ( ! isset( $file_name_parts[1] ) ) {
			return false;
		}

		// Check if the name is a valid CPT.
		if ( in_array( $file_name_parts[1], $this->valid_cpt_types, true ) ) {
			return $file_name_parts[1];
		} else {
			return false;
		}
	}

	/**
	 * Set organizers data.
	 *
	 * @param mixed $channel_node Channel node to add the item.
	 * @param array $data         Data to add.
	 * @return void
	 *
	 * @throws DOMException DOM operations raise exceptions under particular circumstances.
	 */
	private function set_organizers_data( $channel_node, $data ) {
		// Create the item element.
		$item = $this->dom_document->createElement( 'item' );

		// Create the title element with the value.
		$title      = $this->dom_document->createElement( 'title' );
		$title_text = $this->dom_document->createCDATASection( $data[0] );
		$title->appendChild( $title_text );
		$item->appendChild( $title );

		// Create the content element with the value.
		$content      = $this->dom_document->createElement( 'content:encoded' );
		$content_text = $this->dom_document->createCDATASection( $data[1] );
		$content->appendChild( $content_text );
		$item->appendChild( $content );

		// Create the excerpt element with the value.
		$excerpt      = $this->dom_document->createElement( 'excerpt:encoded' );
		$excerpt_text = $this->dom_document->createCDATASection( $data[2] );
		$excerpt->appendChild( $excerpt_text );
		$item->appendChild( $excerpt );

		// Create the post_name element with the value.
		$post_name      = $this->dom_document->createElement( 'wp:post_name' );
		$post_name_text = $this->dom_document->createCDATASection( $data[3] );
		$post_name->appendChild( $post_name_text );
		$item->appendChild( $post_name );

		$postmeta  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key = $this->dom_document->createCDATASection( '_wcpt_user_name' );
		$meta_key->appendChild( $cdata_key );
		$meta_value  = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value = $this->dom_document->createCDATASection( $data[4] );
		$meta_value->appendChild( $cdata_value );
		$postmeta->appendChild( $meta_key );
		$postmeta->appendChild( $meta_value );
		$item->appendChild( $postmeta );

		$postmeta_2  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_2  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_2 = $this->dom_document->createCDATASection( '_wcb_organizer_first_time' );
		$meta_key_2->appendChild( $cdata_key_2 );
		$meta_value_2 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[5] );
		$meta_value_2->appendChild( $cdata_value );
		$postmeta_2->appendChild( $meta_key_2 );
		$postmeta_2->appendChild( $meta_value_2 );
		$item->appendChild( $postmeta_2 );

		$channel_node->appendChild( $item );
	}

	/**
	 * Set speakers data.
	 *
	 * @param mixed $channel_node Channel node to add the item.
	 * @param array $data         Data to add.
	 * @return void
	 *
	 * @throws DOMException DOM operations raise exceptions under particular circumstances.
	 */
	private function set_speakers_data( $channel_node, $data ) {
		// Create the item element.
		$item = $this->dom_document->createElement( 'item' );

		$speaker_id      = $this->dom_document->createElement( 'wp:post_id' );
		$speaker_id_text = $this->dom_document->createTextNode( $data[0] );
		$speaker_id->appendChild( $speaker_id_text );
		$item->appendChild( $speaker_id );

		$title      = $this->dom_document->createElement( 'title' );
		$title_text = $this->dom_document->createCDATASection( $data[1] );
		$title->appendChild( $title_text );
		$item->appendChild( $title );

		$content      = $this->dom_document->createElement( 'content:encoded' );
		$content_text = $this->dom_document->createCDATASection( $data[2] );
		$content->appendChild( $content_text );
		$item->appendChild( $content );

		$excerpt      = $this->dom_document->createElement( 'excerpt:encoded' );
		$excerpt_text = $this->dom_document->createCDATASection( $data[3] );
		$excerpt->appendChild( $excerpt_text );
		$item->appendChild( $excerpt );

		$post_name      = $this->dom_document->createElement( 'wp:post_name' );
		$post_name_text = $this->dom_document->createCDATASection( $data[4] );
		$post_name->appendChild( $post_name_text );
		$item->appendChild( $post_name );

		$postmeta  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key = $this->dom_document->createCDATASection( '_wcb_speaker_email' );
		$meta_key->appendChild( $cdata_key );
		$meta_value  = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value = $this->dom_document->createCDATASection( $data[5] );
		$meta_value->appendChild( $cdata_value );
		$postmeta->appendChild( $meta_key );
		$postmeta->appendChild( $meta_value );
		$item->appendChild( $postmeta );

		$postmeta_2  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_2  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_2 = $this->dom_document->createCDATASection( '_wcpt_user_name' );
		$meta_key_2->appendChild( $cdata_key_2 );
		$meta_value_2 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[6] );
		$meta_value_2->appendChild( $cdata_value );
		$postmeta_2->appendChild( $meta_key_2 );
		$postmeta_2->appendChild( $meta_value_2 );
		$item->appendChild( $postmeta_2 );

		$postmeta_3  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_3  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_3 = $this->dom_document->createCDATASection( '_wcb_speaker_first_time' );
		$meta_key_3->appendChild( $cdata_key_3 );
		$meta_value_3 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[7] );
		$meta_value_3->appendChild( $cdata_value );
		$postmeta_3->appendChild( $meta_key_3 );
		$postmeta_3->appendChild( $meta_value_3 );
		$item->appendChild( $postmeta_3 );

		$channel_node->appendChild( $item );
	}

	/**
	 * Set sessions data.
	 *
	 * @param mixed $channel_node Channel node to add the item.
	 * @param array $data         Data to add.
	 * @return void
	 *
	 * @throws DOMException DOM operations raise exceptions under particular circumstances.
	 */
	private function set_sessions_data( $channel_node, $data ) {
		// Create the item element.
		$item = $this->dom_document->createElement( 'item' );

		$title      = $this->dom_document->createElement( 'title' );
		$title_text = $this->dom_document->createCDATASection( $data[0] );
		$title->appendChild( $title_text );
		$item->appendChild( $title );

		$content      = $this->dom_document->createElement( 'content:encoded' );
		$content_text = $this->dom_document->createCDATASection( $data[1] );
		$content->appendChild( $content_text );
		$item->appendChild( $content );

		$excerpt      = $this->dom_document->createElement( 'excerpt:encoded' );
		$excerpt_text = $this->dom_document->createCDATASection( $data[2] );
		$excerpt->appendChild( $excerpt_text );
		$item->appendChild( $excerpt );

		$post_name      = $this->dom_document->createElement( 'wp:post_name' );
		$post_name_text = $this->dom_document->createCDATASection( $data[3] );
		$post_name->appendChild( $post_name_text );
		$item->appendChild( $post_name );

		$postmeta  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key = $this->dom_document->createCDATASection( '_wcpt_session_time' );
		$meta_key->appendChild( $cdata_key );
		$meta_value  = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value = $this->dom_document->createCDATASection( $data[4] );
		$meta_value->appendChild( $cdata_value );
		$postmeta->appendChild( $meta_key );
		$postmeta->appendChild( $meta_value );
		$item->appendChild( $postmeta );

		$postmeta_2  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_2  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_2 = $this->dom_document->createCDATASection( '_wcpt_session_duration' );
		$meta_key_2->appendChild( $cdata_key_2 );
		$meta_value_2 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[5] );
		$meta_value_2->appendChild( $cdata_value );
		$postmeta_2->appendChild( $meta_key_2 );
		$postmeta_2->appendChild( $meta_value_2 );
		$item->appendChild( $postmeta_2 );

		$postmeta_3  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_3  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_3 = $this->dom_document->createCDATASection( '_wcpt_session_type' );
		$meta_key_3->appendChild( $cdata_key_3 );
		$meta_value_3 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[6] );
		$meta_value_3->appendChild( $cdata_value );
		$postmeta_3->appendChild( $meta_key_3 );
		$postmeta_3->appendChild( $meta_value_3 );
		$item->appendChild( $postmeta_3 );

		$postmeta_4  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_4  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_4 = $this->dom_document->createCDATASection( '_wcpt_session_slides' );
		$meta_key_4->appendChild( $cdata_key_4 );
		$meta_value_4 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[7] );
		$meta_value_4->appendChild( $cdata_value );
		$postmeta_4->appendChild( $meta_key_4 );
		$postmeta_4->appendChild( $meta_value_4 );
		$item->appendChild( $postmeta_4 );

		$postmeta_5  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_5  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_5 = $this->dom_document->createCDATASection( '_wcpt_session_video' );
		$meta_key_5->appendChild( $cdata_key_5 );
		$meta_value_5 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[8] );
		$meta_value_5->appendChild( $cdata_value );
		$postmeta_5->appendChild( $meta_key_5 );
		$postmeta_5->appendChild( $meta_value_5 );
		$item->appendChild( $postmeta_5 );

		$postmeta_6  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_6  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_6 = $this->dom_document->createCDATASection( '_wcpt_speaker_id' );
		$meta_key_6->appendChild( $cdata_key_6 );
		$meta_value_6 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[9] );
		$meta_value_6->appendChild( $cdata_value );
		$postmeta_6->appendChild( $meta_key_6 );
		$postmeta_6->appendChild( $meta_value_6 );
		$item->appendChild( $postmeta_6 );

		$category = $this->dom_document->createElement( 'category' );
		$category->setAttribute( 'domain', 'wcb_track' );
		$category->setAttribute( 'nicename', $data[11] );
		$category_text = $this->dom_document->createCDATASection( $data[10] );
		$category->appendChild( $category_text );
		$item->appendChild( $category );

		$channel_node->appendChild( $item );
	}

	/**
	 * Set volunteers data.
	 *
	 * @param mixed $channel_node Channel node to add the item.
	 * @param array $data         Data to add.
	 * @return void
	 *
	 * @throws DOMException DOM operations raise exceptions under particular circumstances.
	 */
	private function set_volunteers_data( $channel_node, $data ) {
		// Create the item element.
		$item = $this->dom_document->createElement( 'item' );

		$title      = $this->dom_document->createElement( 'title' );
		$title_text = $this->dom_document->createCDATASection( $data[0] );
		$title->appendChild( $title_text );
		$item->appendChild( $title );

		$content      = $this->dom_document->createElement( 'content:encoded' );
		$content_text = $this->dom_document->createCDATASection( $data[1] );
		$content->appendChild( $content_text );
		$item->appendChild( $content );

		$excerpt      = $this->dom_document->createElement( 'excerpt:encoded' );
		$excerpt_text = $this->dom_document->createCDATASection( $data[2] );
		$excerpt->appendChild( $excerpt_text );
		$item->appendChild( $excerpt );

		$post_name      = $this->dom_document->createElement( 'wp:post_name' );
		$post_name_text = $this->dom_document->createCDATASection( $data[3] );
		$post_name->appendChild( $post_name_text );
		$item->appendChild( $post_name );

		$postmeta  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key = $this->dom_document->createCDATASection( '_wcpt_user_name' );
		$meta_key->appendChild( $cdata_key );
		$meta_value  = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value = $this->dom_document->createCDATASection( $data[4] );
		$meta_value->appendChild( $cdata_value );
		$postmeta->appendChild( $meta_key );
		$postmeta->appendChild( $meta_value );
		$item->appendChild( $postmeta );

		$postmeta_2  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_2  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_2 = $this->dom_document->createCDATASection( '_wcb_volunteer_email' );
		$meta_key_2->appendChild( $cdata_key_2 );
		$meta_value_2 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[5] );
		$meta_value_2->appendChild( $cdata_value );
		$postmeta_2->appendChild( $meta_key_2 );
		$postmeta_2->appendChild( $meta_value_2 );
		$item->appendChild( $postmeta_2 );

		$postmeta_3  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_3  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_3 = $this->dom_document->createCDATASection( '_wcb_volunteer_first_time' );
		$meta_key_3->appendChild( $cdata_key_3 );
		$meta_value_3 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[6] );
		$meta_value_3->appendChild( $cdata_value );
		$postmeta_3->appendChild( $meta_key_3 );
		$postmeta_3->appendChild( $meta_value_3 );
		$item->appendChild( $postmeta_3 );

		$channel_node->appendChild( $item );
	}

	/**
	 * Set sponsors data.
	 *
	 * @param mixed $channel_node Channel node to add the item.
	 * @param array $data         Data to add.
	 * @return void
	 *
	 * @throws DOMException DOM operations raise exceptions under particular circumstances.
	 */
	private function set_sponsors_data( $channel_node, $data ) {
		// Create the item element.
		$item = $this->dom_document->createElement( 'item' );

		$title      = $this->dom_document->createElement( 'title' );
		$title_text = $this->dom_document->createCDATASection( $data[0] );
		$title->appendChild( $title_text );
		$item->appendChild( $title );

		$content      = $this->dom_document->createElement( 'content:encoded' );
		$content_text = $this->dom_document->createCDATASection( $data[1] );
		$content->appendChild( $content_text );
		$item->appendChild( $content );

		$excerpt      = $this->dom_document->createElement( 'excerpt:encoded' );
		$excerpt_text = $this->dom_document->createCDATASection( $data[2] );
		$excerpt->appendChild( $excerpt_text );
		$item->appendChild( $excerpt );

		$post_name      = $this->dom_document->createElement( 'wp:post_name' );
		$post_name_text = $this->dom_document->createCDATASection( $data[3] );
		$post_name->appendChild( $post_name_text );
		$item->appendChild( $post_name );

		$postmeta  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key = $this->dom_document->createCDATASection( '_wcpt_sponsor_company_name' );
		$meta_key->appendChild( $cdata_key );
		$meta_value  = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value = $this->dom_document->createCDATASection( $data[4] );
		$meta_value->appendChild( $cdata_value );
		$postmeta->appendChild( $meta_key );
		$postmeta->appendChild( $meta_value );
		$item->appendChild( $postmeta );

		$postmeta_2  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_2  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_2 = $this->dom_document->createCDATASection( '_wcpt_sponsor_website' );
		$meta_key_2->appendChild( $cdata_key_2 );
		$meta_value_2 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[5] );
		$meta_value_2->appendChild( $cdata_value );
		$postmeta_2->appendChild( $meta_key_2 );
		$postmeta_2->appendChild( $meta_value_2 );
		$item->appendChild( $postmeta_2 );

		$postmeta_3  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_3  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_3 = $this->dom_document->createCDATASection( '_wcpt_sponsor_first_name' );
		$meta_key_3->appendChild( $cdata_key_3 );
		$meta_value_3 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[6] );
		$meta_value_3->appendChild( $cdata_value );
		$postmeta_3->appendChild( $meta_key_3 );
		$postmeta_3->appendChild( $meta_value_3 );
		$item->appendChild( $postmeta_3 );

		$postmeta_4  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_4  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_4 = $this->dom_document->createCDATASection( '_wcpt_sponsor_last_name' );
		$meta_key_4->appendChild( $cdata_key_4 );
		$meta_value_4 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[7] );
		$meta_value_4->appendChild( $cdata_value );
		$postmeta_4->appendChild( $meta_key_4 );
		$postmeta_4->appendChild( $meta_value_4 );
		$item->appendChild( $postmeta_4 );

		$postmeta_5  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_5  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_5 = $this->dom_document->createCDATASection( '_wcpt_sponsor_email_address' );
		$meta_key_5->appendChild( $cdata_key_5 );
		$meta_value_5 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[8] );
		$meta_value_5->appendChild( $cdata_value );
		$postmeta_5->appendChild( $meta_key_5 );
		$postmeta_5->appendChild( $meta_value_5 );
		$item->appendChild( $postmeta_5 );

		$postmeta_6  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_6  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_6 = $this->dom_document->createCDATASection( '_wcpt_sponsor_phone_number' );
		$meta_key_6->appendChild( $cdata_key_6 );
		$meta_value_6 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[9] );
		$meta_value_6->appendChild( $cdata_value );
		$postmeta_6->appendChild( $meta_key_6 );
		$postmeta_6->appendChild( $meta_value_6 );
		$item->appendChild( $postmeta_6 );

		$postmeta_7  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_7  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_7 = $this->dom_document->createCDATASection( '_wcpt_sponsor_street_address1' );
		$meta_key_7->appendChild( $cdata_key_7 );
		$meta_value_7 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[10] );
		$meta_value_7->appendChild( $cdata_value );
		$postmeta_7->appendChild( $meta_key_7 );
		$postmeta_7->appendChild( $meta_value_7 );
		$item->appendChild( $postmeta_7 );

		$postmeta_8  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_8  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_8 = $this->dom_document->createCDATASection( '_wcpt_sponsor_city' );
		$meta_key_8->appendChild( $cdata_key_8 );
		$meta_value_8 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[11] );
		$meta_value_8->appendChild( $cdata_value );
		$postmeta_8->appendChild( $meta_key_8 );
		$postmeta_8->appendChild( $meta_value_8 );
		$item->appendChild( $postmeta_8 );

		$postmeta_9  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_9  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_9 = $this->dom_document->createCDATASection( '_wcpt_sponsor_state' );
		$meta_key_9->appendChild( $cdata_key_9 );
		$meta_value_9 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value  = $this->dom_document->createCDATASection( $data[12] );
		$meta_value_9->appendChild( $cdata_value );
		$postmeta_9->appendChild( $meta_key_9 );
		$postmeta_9->appendChild( $meta_value_9 );
		$item->appendChild( $postmeta_9 );

		$postmeta_10  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_10  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_10 = $this->dom_document->createCDATASection( '_wcpt_sponsor_zip_code' );
		$meta_key_10->appendChild( $cdata_key_10 );
		$meta_value_10 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value   = $this->dom_document->createCDATASection( $data[13] );
		$meta_value_10->appendChild( $cdata_value );
		$postmeta_10->appendChild( $meta_key_10 );
		$postmeta_10->appendChild( $meta_value_10 );
		$item->appendChild( $postmeta_10 );

		$postmeta_11  = $this->dom_document->createElement( 'wp:postmeta' );
		$meta_key_11  = $this->dom_document->createElement( 'wp:meta_key' );
		$cdata_key_11 = $this->dom_document->createCDATASection( '_wcpt_sponsor_country' );
		$meta_key_11->appendChild( $cdata_key_11 );
		$meta_value_11 = $this->dom_document->createElement( 'wp:meta_value' );
		$cdata_value   = $this->dom_document->createCDATASection( $data[14] );
		$meta_value_11->appendChild( $cdata_value );
		$postmeta_11->appendChild( $meta_key_11 );
		$postmeta_11->appendChild( $meta_value_11 );
		$item->appendChild( $postmeta_11 );

		$channel_node->appendChild( $item );
	}

	/**
	 * Convert CSV to XML
	 *
	 * @param string $csv_file  CSV file path.
	 * @param string $data_type CSV data type.
	 *
	 * @return string|WP_Error XML string or WP_Error object.
	 */
	public function convert_2_xml( string $csv_file, string $data_type ) {
		// Create the rss root element.
		$rss = $this->dom_document->createElement( 'rss' );

		// Set the version attribute.
		$rss->setAttribute( 'version', '2.0' );

		// Set the namespace attributes.
		$rss->setAttribute( 'xmlns:excerpt', $this->ns_excerpt );
		$rss->setAttribute( 'xmlns:content', $this->ns_content );
		$rss->setAttribute( 'xmlns:wfw', $this->ns_wfw );
		$rss->setAttribute( 'xmlns:dc', $this->ns_dc );
		$rss->setAttribute( 'xmlns:wp', $this->ns_wp );

		// Create the channel element.
		$channel = $this->dom_document->createElement( 'channel' );

		/**
		 * Read CSV file and generate DOM structure.
		 */
		$row_number = 0;
		$csv_data   = fopen( $csv_file, 'r' ); // phpcs:ignore

		if ( false !== ( $csv_data ) ) {
			while ( false !== ( $data = fgetcsv( $csv_data ) ) ) { // phpcs:ignore
				++$row_number;

				// First row is CSV headers.
				if ( $row_number > 1 ) {
					switch ( $data_type ) {
						case 'wcb_organizer':
							$this->set_organizers_data( $channel, $data );
							break;
						case 'wcb_speaker':
							$this->set_speakers_data( $channel, $data );
							break;
						case 'wcb_session':
							$this->set_sessions_data( $channel, $data );
							break;
						case 'wcb_volunteer':
							$this->set_volunteers_data( $channel, $data );
							break;
						case 'wcb_sponsor':
							$this->set_sponsors_data( $channel, $data );
							break;
					}
				}
			}
			fclose( $csv_data ); // phpcs:ignore
		}

		// Add the channel element to the rss element.
		$rss->appendChild( $channel );

		// Add the rss element to the DOMDocument.
		$this->dom_document->appendChild( $rss );

		return $this->dom_document->saveXML();
	}

	/**
	 * Write XML file.
	 *
	 * @param string $xml_data  XML data to write to file.
	 * @param string $data_type Data CPT type.
	 *
	 * @return string
	 */
	public function write_xml( string $xml_data, string $data_type ) {
		$filename = 'camptix-' . $data_type . '-' . gmdate( 'Y-m-d' ) . '-converted.xml';

		$xml = fopen( CAMPTIX_XML_CSV_UPLOAD_DIR . $filename, 'w' ); // phpcs:ignore
		fwrite( $xml, $xml_data ); // phpcs:ignore
		fclose( $xml ); // phpcs:ignore

		return $filename;
	}
}
