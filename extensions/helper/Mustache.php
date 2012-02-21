<?php

namespace li3_mustache\extensions\helper;

use li3_mustache\libraries\Mustache as Must;

class Mustache extends \lithium\template\Helper {

	/**
	 * Renders one mustache element with given $data
	 *
	 * @param string $name name of the element, below elements/mustache
	 * @param string $data an array or object what to hand to the mustache layer
	 * @param string $options additional options, currently none
	 * @return string the rendered mustache template
	 */
	public function render($name, $data = array(), $options = array()) {
		$template = $this->template($name);
		$partials = $this->partials($template);
		$data = $this->_extract($data);
		return new Must($template, $data, $partials);
	}

	/**
	 * Find the correct (mustache) element and return its content
	 *
	 * @param string $name Name of element to look for below elements/mustache
	 * @param string $params additional params to put into the view()->render()
	 * @return string the rendered element with (hopefully) the mustache template in it
	 */
	public function template($name, $params = array()) {
		$element = array('element' => "mustache/{$name}");
		return $this->_context->view()->render($element, $params);
	}

	/**
	 * generates a valid Mustache partials array for given $template
	 *
	 * @param string $template mustache template with partials in it
	 * @return array an associative array containing all partials
	 */
	public function partials($template) {
		$result = array();
		$regex = sprintf(
			'/(?:(?<=\\n)[ \\t]*)?%s(?:(?P<type>[%s])(?P<tag_name>.+?)|=(?P<delims>.*?)=)%s\\n?/s',
			preg_quote('{{', '/'), '\>', preg_quote('}}', '/')
		);
		preg_match_all($regex, $template, $matches);
		if (!empty($matches['tag_name'])) {
			foreach($matches['tag_name'] as $name) {
				$result[$name] = $this->template($name);
			}
		}
		return $result;
	}

	/**
	 * make sure, only array data is returned
	 *
	 * @param string $data mixed can be objects or array
	 * @return array resulting data with only array format
	 */
	public function _extract($data) {
		array_walk_recursive($data, function(&$item, &$key){

			// we need to convert our data, that is probably an object
			// to an array, or our mustache complains.
			if(is_object($item)) {

				// get type of class (we do not care for namespaces...)
				$class_type = basename(str_replace('\\', '/', get_class($item)));

				// First, we check, if this is a Collection or RecordSet,
				// because we need to get only the values, the $keys are $ids
				// which makes mustache think, this is an named object, instead
				// of an array
				if (in_array($class_type, array('RecordSet', 'DocumentSet', 'DocumentCollection'))) {
					$item = array_values($item->data());

				// whatever it is, if it has a data() method on it, we should call that.
				// that way, we can even throw models or whatever you think in it.
				// If you handover a custom tailored object, make sure you implement this
				// so it works out of the box
				} elseif(is_callable(array($item, 'data'))) {
					$item = $item->data();
				}
			}
		});
		return $data;
	}

}
