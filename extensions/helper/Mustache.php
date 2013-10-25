<?php

namespace li3_mustache\extensions\helper;

use li3_mustache\libraries\Mustache as MustacheEngine;
use Handlebars_Engine;

use lithium\core\Libraries;
use lithium\util\Inflector;
use lithium\util\String;
use lithium\util\Set;

class Mustache extends \lithium\template\view\adapter\File {

	/**
	 * `Mustache_Engine` object instance used by this adapter.
	 *
	 * @var object
	 */
	protected $_config = array();

	/**
	 * `Mustache_Engine` object instance used by this adapter.
	 *
	 * @var object
	 */
	private $_engine = null;

	public function _init() {
		parent::_init(array('compile' => false));
		$this->_engine = new Handlebars_Engine($this->_config);
	}

	/**
	 * Renders one mustache element with given $data
	 *
	 * @param string $template name of the element, below views/mustache
	 * @param string $data an array or object what to hand to the mustache layer
	 * @param string $options additional options, to put into the view()->render()
	 * @return string the rendered mustache template
	 */
	public function render($template, $data = array(), array $options = array()) {
		$defaults = array('context' => array());
		$options += $defaults;

		$this->_data = (array) $data + $this->_vars;
		return $this->_engine->render($this->template($template), $this->_data);
	}

	public function template($name, array $params = array()) {
		$library = Libraries::get(isset($params['library']) ? $params['library'] : true);
		$params['library'] = $library['path'];
		return $this->_view()->render(array('element' => '../mustache/' . $name), array(), $params);
	}

	/**
	 * Parses an associative array into an array, containing one
	 * array for each row, that has 'key' and 'value' filled
	 * as expected. That makes rendering of arbitrary meta-data
	 * much simpler, e.g. if you do not know, what data you are
	 * about to retrieve.
	 *
	 * @param array $data an associative array containing mixed data
	 * @return array an numerical indexed array with arrays for each
	 *         item in $data, having 'key' and 'value' set accordingly
	 */
	public function data(array $data = array(), array $options = array()) {
		$defaults = array('flatten' => true);
		$options += $defaults;
		if ($options['flatten']) {
			$data = Set::flatten($this->_extract($data));
		}
		return array_map(function($key, $value) {
			return compact('key', 'value');
		}, array_keys($data), $data);
	}

	/**
	 * generates a script-tag with a mustache template in it
	 *
	 * The script tag has the given template as content and has a
	 * attribute type="text/html" which is proposed as a template
	 * to be used clientside with javascript.
	 *
	 * @param string $name Name of template to include
	 * @param string $options additional options, to put into the view()->render()
	 * @return string the script-tag with correct attributes and template
	 */
	public function script($name, $options = array()) {
		$defaults = array('name' => 'tpl_' . Inflector::slug($name, '_'));
		$options += $defaults;
		$options += array('template' => $this->template($name, array(), $options));
		$scriptblock = '<script id="{:name}" type="text/html" charset="utf-8">{:template}</script>';
		return String::insert($scriptblock, $options);
	}

	/**
	 * returns View class form context
	 *
	 * @return object instance of created View Instance
	 */
	public function _view() {
		return $this->_context->view();
	}

	/**
	 * make sure, only array data is returned
	 *
	 * @param string $data mixed can be objects or array
	 * @return array resulting data with only array format
	 */
	public function _extract($data) {
		array_walk_recursive($data, function(&$item, &$key) {
			if (is_object($item)) {
				if (is_a($item, '\lithium\data\Collection')) {
					$item = array_values($item->data());
				} elseif (is_callable(array($item, 'data'))) {
					$item = $item->data();
				}
			}
		});
		return $data;
	}
}

?>