<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives;

// append Set autoloader
spl_autoload_register(array('\alxmsl\Primitives\Autoloader', 'autoload'));

/**
 * Base class
 * @author alxmsl
 * @date 10/22/12
 */
final class Autoloader {
    /**
     * @var array array of available classes
     */
    private static $classes = array(
        'alxmsl\\Primitives\\Autoloader' => 'Autoloader.php',

        'alxmsl\\Primitives\\SetFactory'        => 'SetFactory.php',
        'alxmsl\\Primitives\\Set\\SetInterface' => 'Set/SetInterface.php',
        'alxmsl\\Primitives\\Set\\Set'          => 'Set/Set.php',

        'alxmsl\\Primitives\\Set\\Provider\\AbstractProvider' => 'Set/Provider/AbstractProvider.php',
        'alxmsl\\Primitives\\Set\\Provider\\RedisProvider'    => 'Set/Provider/RedisProvider.php',
        'alxmsl\\Primitives\\Set\\Provider\\PostgresProvider' => 'Set/Provider/PostgresProvider.php',

        'alxmsl\\Primitives\\Set\\Iterator\\AbstractIterator' => 'Set/Iterator/AbstractIterator.php',
        'alxmsl\\Primitives\\Set\\Iterator\\PostgresIterator' => 'Set/Iterator/PostgresIterator.php',
        'alxmsl\\Primitives\\Set\\Iterator\\RedisIterator'    => 'Set/Iterator/RedisIterator.php',
    );

    /**
     * Component autoloader
     * @param string $className claass name
     */
    public static function autoload($className) {
        if (array_key_exists($className, self::$classes)) {
            $fileName = realpath(dirname(__FILE__)) . '/' . self::$classes[$className];
            if (file_exists($fileName)) {
                include $fileName;
            }
        }
    }
}
