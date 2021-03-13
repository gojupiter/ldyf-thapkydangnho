<?php
namespace Lastudio_Elements\Modules\ContactForm7;

use Lastudio_Elements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

    public function get_name() {
        return 'contact-form-7';
    }

    public function get_widgets() {
        return [
            'Contact_Form7'
        ];
    }
}