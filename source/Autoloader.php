<?php
namespace Set;

// append Set autoloader
spl_autoload_register(array('\Set\Autoloader', 'autoload'));

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
        'Set\\Autoloader' => 'Autoloader.php',

        'Set\\SetFactory' => 'SetFactory.php',
        'Set\\SetInterface' => 'SetInterface.php',
        'Set\\Set' => 'Set.php',

        'Set\\Provider\\AbstractProvider' => 'Provider/AbstractProvider.php',
        'Set\\Provider\\RedisProvider' => 'Provider/RedisProvider.php',
        'Set\\Provider\\PostgresProvider' => 'Provider/PostgresProvider.php',

        'Set\\Iterator\\AbstractIterator' => 'Iterator/AbstractIterator.php',
        'Set\\Iterator\\PostgresIterator' => 'Iterator/PostgresIterator.php',
        'Set\\Iterator\\RedisIterator' => 'Iterator/RedisIterator.php',
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