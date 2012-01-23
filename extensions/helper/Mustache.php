<?php

namespace li3_mustache\extensions\helper;

use li3_mustache\libraries\Mustache as Must;

class Mustache extends \lithium\template\Helper {

	public function render($name, $data = array(), $options = array()) {
		$template = $this->template($name);
		return new Must($template, $data);
	}

	public function template($name, $params = array()) {

		$process = array('element' => "mustache/{$name}");
		return $this->_context->view()->render($process, $params);
	}

}
