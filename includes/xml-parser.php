<?php
/**
 * This file exports a function `xml_parser` which takes an XML file path and returns an array of data.
 * The function uses the `SimpleXMLElement` class to parse the XML file and extract the necessary data.
 *
 * @param string $xml_file_path The path to the XML file.
 *
 * @return array The array of data extracted from the XML file.
 */
function xml_parser( $xml_file_path ) {
	$xml  = simplexml_load_file( $xml_file_path );
	$data = array();

	foreach ( $xml->channel->item as $item ) {
		$title     = (string) $item->title;
		$content   = (string) $item->children( 'content', true )->encoded;
		$excerpt   = (string) $item->children( 'excerpt', true )->encoded;
		$post_name = (string) $item->children( 'wp', true )->post_name;

		$data[] = array(
			'title'     => $title,
			'content'   => $content,
			'excerpt'   => $excerpt,
			'post_name' => $post_name,
		);
	}

	return $data;
}
