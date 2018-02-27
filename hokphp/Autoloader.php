<?php 

/**
 * Autoload class used to autoload classes.
 */
class Autoloader
{
    /**
     * Autoloads a not found class into the namespace
     * @return bool  Wether the class was autoloaded
     */
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
            if (file_exists($file)) {
                require_once($file);
                return true;
            } else {
                die('cant find class '.$file);
            }
            return false;
        });
    }
}
spl_autoload_register('Autoloader::register');