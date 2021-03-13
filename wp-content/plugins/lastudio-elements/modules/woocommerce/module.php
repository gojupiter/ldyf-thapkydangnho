<?php
namespace Lastudio_Elements\Modules\Woocommerce;

use Lastudio_Elements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends Module_Base {

    public function get_name() {
        return 'woocommerce';
    }

    public function get_widgets() {
        return [
            'Products'
        ];
    }
}