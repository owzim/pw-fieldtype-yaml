<?php

/**
 * Class definition of Autoloader
 *
 * @author Marco Stoll <marco.stoll@neuwaerts.de>
 * @version 1.0.4
 * @copyright Copyright (c) 2013, neuwaerts GmbH
 * @filesource
 *
 * modified by owzim to use 'owzim\FieldtypeYaml' namespace
 */

namespace owzim\FieldtypeYaml;

/**
 * Class Autoloader
 *
 * Generic autoloader (PSR-0 style)
 *
 * Can be registered as followed:
 *
 * <code>
 * require_once('/path/to/FieldtypeYaml/owzim/FieldtypeYaml/Autoloader.php');
 * spl_autoload_register('owzim\FieldtypeYaml\Autoloader::autoload');
 * </code>
 *
 * @see http://www.php-fig.org/psr/0/
 */
class Autoloader
{

    public static $basePath = null;

    /**
     * @field boolean Flag signaling, whether the autoload callback is already registered
     */
    protected static $isRegistered = false;

    /**
     * Registers the autoload callback
     *
     * Does nothing, if already registered.
     */
    public static function register()
    {
        if (self::$isRegistered) return;

        spl_autoload_register(array(__CLASS__, 'autoload'));
        self::$isRegistered = true;
    }

    /**
     * Autoloader callback
     *
     * @param $className
     */
    public static function autoload($className)
    {
        if (is_null(self::$basePath)) self::$basePath =
            dirname(dirname(__FILE__)) .
            DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

        $basePath = self::$basePath;

        $className = ltrim($className, '\\');
        $fileName  = $basePath;
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        if (!is_file($fileName)) return;

        require_once($fileName);
    }
}
