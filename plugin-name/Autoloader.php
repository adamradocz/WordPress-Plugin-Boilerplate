<?php

declare(strict_types=1);

namespace PluginName;

// If this file is called directly, abort.
if (!defined('ABSPATH')) exit;

/**
 * The PSR-4 autoloader project-specific implementation.
 *
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt to load the \Foo\Bar\Baz\Qux class
 * from /path/to/project/src/Baz/Qux.php:
 *
 *      new \Foo\Bar\Baz\Qux;
 *
 * @link https://www.php-fig.org/psr/psr-4/examples/ The code this autoloader is based upon.
 *
 * @since             1.0.0
 * @package           PluginName
 *
 * @param   $className The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function (string $className): void
{
    // Project-specific namespace prefix
    $prefix = 'PluginName\\';

    // Base directory for the namespace prefix
    $baseDir = __DIR__ . '/';

    // Does the class use the namespace prefix?
    $prefixLength = strlen($prefix);
    if (strncmp($prefix, $className, $prefixLength) !== 0)
    {
        // No, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relativeClassName = substr($className, $prefixLength);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $filePath = $baseDir . str_replace('\\', '/', $relativeClassName) . '.php';

    // If the file exists, require it
    if (file_exists($filePath))
    {
        require_once $filePath;
    }
    else
    {
        exit(esc_html("The file $className.php could not be found!"));
    }
});
