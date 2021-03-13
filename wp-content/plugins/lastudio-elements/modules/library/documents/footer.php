<?php
namespace Lastudio_Elements\Modules\Library\Documents;

use Elementor\Modules\Library\Documents\Library_Document;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor footer library document.
 *
 * Elementor footer library document handler class is responsible for
 * handling a document of a section type.
 *
 * @since 1.0.0
 */
class Footer extends Library_Document {

	/**
	 * Get document properties.
	 *
	 * Retrieve the document properties.
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 *
	 * @return array Document properties.
	 */
	public static function get_properties() {
		$properties = parent::get_properties();
		$properties['group'] = 'blocks';

		return $properties;
	}

	/**
	 * Get document name.
	 *
	 * Retrieve the document name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Document name.
	 */
	public function get_name() {
		return 'footer';
	}

	/**
	 * Get document title.
	 *
	 * Retrieve the document title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Document title.
	 */
	public static function get_title() {
		return __( 'Footer', 'lastudio-elements' );
	}
}
