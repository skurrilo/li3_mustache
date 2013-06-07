<?php

namespace li3_mustache\extensions\adapter\template\view;

use Mustache_Engine;
use lithium\core\Libraries;

class Mustache extends \lithium\template\view\adapter\File {

	/**
	 * `Mustache_Engine` object instance used by this adapter.
	 *
	 * @var object
	 */
	private $_engine = null;

	public function _init() {
		parent::_init();
		$this->_engine = new Mustache_Engine($this->_config);
	}

	public function render($template, $data = array(), array $options = array()) {
		$this->_context = $options['context'] + $this->_context;
		$this->_engine->setHelpers($this->_context);
		$this->_data = (array) $data + $this->_vars;
		return $this->_engine->render(file_get_contents($template), $this->_data);
	}

	/**
	 * Returns a template file name
	 *
	 * @param string $type
	 * @param array $params
	 * @return string
	 */
	public function template($type, array $params) {
		$library = Libraries::get(isset($params['library']) ? $params['library'] : true);
		$params['library'] = $library['path'];
		return $this->_paths($type, $params);
	}
}

?>