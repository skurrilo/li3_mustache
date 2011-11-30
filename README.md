# li3_mustache

Lithium library for parsing mustache views, uses [PHP Mustache](https://github.com/bobthecow/mustache.php).

Mustache Spec version: `1.1.2`
PHP Mustache version: `0.8.1`

## Installation

Add a submodule to your li3 libraries:

	git submodule add git@github.com:bruensicke/li3_mustache.git libraries/li3_mustache

and activate it in you app (config/bootstrap/libraries.php), of course:

	Libraries::add('li3_mustache');

## Todos

The following points is my roadmap. If you need any of this features sooner than later, please let me know.

- Provide useful helper
- allow for easy using of an element as mustache template
- provide additional scope that carries li3-relevant information (i.e. request, session, etc)

## Credits

* [li3](http://www.lithify.me)
* [Nate Abele](https://github.com/nateabele/li3_mustache)

Please report any bug, here: https://github.com/bruensicke/li3_mustache/issues

