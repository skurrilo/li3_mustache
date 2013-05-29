# li3_mustache

Lithium library for parsing mustache views, uses [PHP Mustache](https://github.com/bobthecow/mustache.php).

Mustache Spec version: `1.1.2`
PHP Mustache version: `1.0.0`

## Installation

Add a submodule to your li3 libraries:

	git submodule add git@github.com:bruensicke/li3_mustache.git libraries/li3_mustache

and activate it in you app (config/bootstrap/libraries.php), of course:

	Libraries::add('li3_mustache');

## Usage

Within your views, you can easily use mustache elements with a helpful mustache helper:

	<?= $this->mustache->render('posts/detail', compact('post')); ?>

The mustache template will then be pulled from `{:library}/views/mustache/posts/detail.html.php`

Any model, that is passed in (or a collection of models) will be converted to array-data at the moment, to allow for easy usage.
I will work on a model-callback solution, which allows for template functions to be called.

If you want to provide the mustache template for js-usage, this can be easily done like that:

	<?= $this->mustache->script('posts/index'); ?>

This will render the following javascript block (including the template):

	<script id="tpl_posts_index" type="text/html" charset="utf-8"> { mustache template here } </script>

You can easily use this template with `tpl_` followed by `Inflector::slugified` template name (where - will become _).

## additional usage

Instead of using only mustache for the view in general, i prefer to use mustache templates for all kind of data representation within the view, whereas i leave the view itself as default html/php mix. But this library comes with a mustache View class as well, to use mustache views completely.

## Todos

The following points is my roadmap. If you need any of this features sooner than later, please let me know.

  - [✓] Provide useful helper
  - [✓] allow for easy using of an element as mustache template
  - [✓] mustache template folder to be at views/mustache
  - [✓] allow for mustache templates to live within libraries
  - [✓] allow subtemplates to be rendered, no registration required
  - [ ] provide additional scope that carries li3-relevant information (i.e. request, session, etc)
  - [ ] allow callbacks to model methods on collection data

## Credits

* [lithium](http://www.lithify.me)
* [Nate Abele](https://github.com/nateabele/li3_mustache)
* [bobthecow](https://github.com/bobthecow/mustache.php)

Please report any bug, here: https://github.com/bruensicke/li3_mustache/issues

