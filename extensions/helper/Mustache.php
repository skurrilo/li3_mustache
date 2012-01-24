<?php

namespace li3_mustache\extensions\helper;

use li3_mustache\libraries\Mustache as Must;

class Mustache extends \lithium\template\Helper {

	public function render($name, $data = array(), $options = array()) {
		$template = $this->template($name);

		$data = $this->_extract($data);

		return new Must($template, $data);
	}

	public function template($name, $params = array()) {

		$process = array('element' => "mustache/{$name}");
		return $this->_context->view()->render($process, $params);
	}

	public function _extract($data) {

		$keys = array_keys($data);
		foreach($keys as $key) {
			if(is_callable(array($data[$key], 'data'))) {
				$data[$key] = $data[$key]->data();
			}
		}
		return $data;
	}

}
