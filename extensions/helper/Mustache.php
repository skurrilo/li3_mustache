<?php

namespace li3_mustache\extensions\helper;

use li3_mustache\libraries\Mustache as MustacheEngine;

use lithium\util\Inflector;
use lithium\util\String;
use lithium\util\Set;

class Mustache extends \lithium\template\Helper {

	/**
	 * Renders one mustache template with given $data
	 *
	 * @param string $template content of the template to be rendered
	 * @param string $data an array or object what to hand to the mustache layer
	 * @param string $options additional options, to put into the view()->render()
	 * @return string the rendered mustache template
	 */
	public function render($template, $data = array(), $options = array()) {
		$partials = $this->partials($template);
		$data += $this->_context->data();
		return new MustacheEngine($template, $this->_extract($data), $partials);
	}

	/**
	 * Renders one mustache template called `$name`
	 *
	 * @param string $name name of the element, below views/mustache
	 * @param string $data an array or object what to hand to the mustache layer
	 * @param string $options additional options, to put into the view()->render()
	 * @return string the rendered mustache template
	 */
	public function template($name, $data = array(), $options = array()) {
		$template = $this->element($name, $data, $options);
		return $this->render($template, $data);
	}

	/**
	 * Find the correct (mustache) element and return its content
	 *
	 * @param string $name Name of element to look for below views/mustache
	 * @param string $data data to be put into the mustache template
	 * @param string $params additional params to put into the view()->render()
	 * @return string the rendered element with (hopefully) the mustache template in it
	 */
	public function element($name, $data = array(), $params = array()) {
		$data += $this->_context->data();
		return $this->_view()->render(array('element' => '../mustache/' . $name), $data, $params);
	}

	/**
	 * generates a valid Mustache partials array for given $template
	 *
	 * @param string $template mustache template with partials in it
	 * @param array $data an array or object to hand to the mustache layer
	 * @param array $options additional options, to put into the view()->render()
	 * @return array an associative array containing all partials
	 */
	public function partials($template, $data = array(), $options = array()) {
		$result = array();
		$regex = sprintf(
			'/(?:(?<=\\n)[ \\t]*)?%s(?:(?P<type>[%s])(?P<tag_name>.+?)|=(?P<delims>.*?)=)%s\\n?/s',
			preg_quote('{{', '/'), '\>', preg_quote('}}', '/')
		);
		preg_match_all($regex, $template, $matches);
		if (!empty($matches['tag_name'])) {
			foreach ($matches['tag_name'] as $name) {
				$result[$name] = $this->element($name, $data, $options);
			}
		}
		return $result;
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
			$data = Set::flatten($data);
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
		$options += array('template' => $this->element($name, array(), $options));
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