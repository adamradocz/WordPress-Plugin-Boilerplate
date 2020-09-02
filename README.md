# WordPress Plugin Boilerplate

A standardized, organized, object-oriented foundation for building high-quality WordPress Plugins.

## Contents

The WordPress Plugin Boilerplate includes the following files:

* `.gitignore`. Used to exclude certain files from the repository.
* `CHANGELOG.md`. The list of changes to the core project.
* `README.md`. The file that you’re currently reading.
* A `plugin-name` directory that contains the source code - a fully executable WordPress plugin.

## Features

* The Boilerplate is based on the [Plugin API](http://codex.wordpress.org/Plugin_API), and [Documentation Standards](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/).
* All classes, functions, and variables are documented so that you know what you need to change.
* The Boilerplate uses a strict file organization scheme that similar to the WordPress Plugin Repository structure, and that makes it easy to organize the files that compose the plugin.
* The project includes [a `.pot` file](https://github.com/fxbenard/Blank-WordPress-Pot) as a starting point for internationalization.
* Contatains templates for:
  * [Admin menu](https://codex.wordpress.org/Adding_Administration_Menus)
  * [Settings](https://codex.wordpress.org/Settings_API)
  * [Shortcode](https://codex.wordpress.org/Shortcode_API) as a Form and how to pass data from a Javascript and return data back.
  * [Multisite support](https://wordpress.org/support/article/create-a-network/)
  * [Network Settings](https://codex.wordpress.org/Plugin_API/Action_Reference/network_admin_menu)

## Installation

The Boilerplate can be installed directly into your plugins folder "as-is". You will want to rename it and the classes inside of it to fit your needs. For example, if your plugin is named 'example-me' then:

* rename files from `plugin-name` to `example-me`
* change `plugin_name` to `example_me`
* change `plugin-name` to `example-me`
* change `Plugin_Name` to `Example_Me`
* change `PLUGIN_NAME_` to `EXAMPLE_ME_`
* change `PluginName` to `ExampleMe`
* change `Plugin Name` to `Example Me`

It's safe to activate the plugin at this point.

## Recommended Tools

### i18n Tools

The WordPress Plugin Boilerplate uses a variable to store the text domain used when internationalizing strings throughout the Boilerplate. To take advantage of this method, there are tools that are recommended for providing correct, translatable files:

* [Poedit](http://www.poedit.net/)
* [makepot](http://i18n.svn.wordpress.org/tools/trunk/)
* [i18n](https://github.com/grappler/i18n)

Any of the above tools should provide you with the proper tooling to internationalize the plugin.

## Important Notes

### Licensing

The WordPress Plugin Boilerplate is licensed under the GPL v2 or later; however, if you opt to use third-party code that is not compatible with v2, then you may need to switch to using code that is GPL v3 compatible.

For reference, [here's a discussion](http://make.wordpress.org/themes/2013/03/04/licensing-note-apache-and-gpl/) that covers the Apache 2.0 License used by [Bootstrap](http://twitter.github.io/bootstrap/).

### Includes

Note that if you include your own classes, or third-party libraries, there are three locations in which said files may go:

* `plugin-name/Includes` is where functionality shared between the admin area and the public-facing parts of the site reside
* `plugin-name/Admin` is for all admin-specific functionality
* `plugin-name/Frontend` is for all public-facing functionality

### Multisite development

The plugin is multisite ready out of the box.
Highly recommended articles:
* [Site Metadata](https://make.wordpress.org/core/2019/01/28/multisite-support-for-site-metadata-in-5-1/)
* [Super Admin capabilities](https://wordpress.org/support/article/roles-and-capabilities/#super-admin)

### What About Other Features?

The previous version of the WordPress Plugin Boilerplate included support for a number of different projects such as the [GitHub Updater](https://github.com/afragen/github-updater).

These tools are not part of the core of this Boilerplate, as I see them as being additions, forks, or other contributions to the Boilerplate.

The same is true of using tools like Grunt, Composer, etc. These are all fantastic tools, but not everyone uses them. In order to  keep the core Boilerplate as light as possible, these features have been removed and will be introduced in other editions, and will be listed and maintained on the project homepage.

# Documentation, FAQs, and More

## Coding standards

- Every functions MUST declare [**parameter type**](https://www.php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration) and [**return type**](https://www.php.net/manual/en/functions.returning-values.php#functions.returning-values.type-declaration).
- Every class properties MUST be [**typed**](https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.typed-properties).
- Every PHP files MUST have [**strict type mode**](https://www.php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration.strict) enabled by starting with `declare(strict_types=1);`.
- Every PHP files MUST contain `if (!defined('ABSPATH')) exit;` right after the `namespace` declaration.

### Coding style

Classes: `UpperCamelCase`

Functions: `camelCase`. You must not omit `public`, `protected`, or `private` modifiers.

Variables: `camelCase`

Constants: `ALL_UPPER_CASE_WITH_UNDERSCORE_SEPARATOR`

Parentheses: Allman

Comparison: You should use strict comparison (`===`, `!==`) whenever possible.

Whitespaces: You must not add a whitespace before and after braces.

```php
<?php

declare(strict_types=1);

namespace PluginName;

if (!defined('ABSPATH')) exit;

class User
{
    const DATE_REGISTERED = '2012-06-01';
    
    private int $id;
    
    public function updateAddress($id, $newAddress)
    {
        // Do something
    }
}
```

# Credits

The plugin is based on the [WordPress Plugin Boilerplate](DevinVinson/WordPress-Plugin-Boilerplate).
