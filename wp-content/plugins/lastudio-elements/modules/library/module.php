<?php
namespace Lastudio_Elements\Modules\Library;

use Lastudio_Elements\Base\Module_Base;
use Lastudio_Elements\Modules\Library\Classes\Shortcode;
use Lastudio_Elements\Modules\Library\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function __construct() {
        parent::__construct();
		new Shortcode();

        \Elementor\Plugin::$instance->documents
            ->register_document_type( 'footer', Documents\Footer::get_class_full_name() );
	}

    public function get_name() {
        return 'library';
    }

    public static function is_preview() {
        return \Elementor\Plugin::$instance->preview->is_preview_mode() || is_preview();
    }

}