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
		$this->dom_document = new DOMDocument();

		$this->dom_document->preserveWhiteSpace = false;
	}
}
