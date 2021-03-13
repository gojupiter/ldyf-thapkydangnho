<?php
namespace Lastudio_Elements\Modules\Brands;

use Lastudio_Elements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'lastudio-brands';
	}

	public function get_widgets() {
		return [
			'Brands'
		];
	}
}