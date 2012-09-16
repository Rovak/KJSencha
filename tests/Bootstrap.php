<?php
use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;

error_reporting( E_ALL | E_STRICT );

/**
 * Unittest bootstrap
 */
class Bootstrap
{
    public static $serviceManager;

    public static function go()
    {
        $zf2Path = realpath(defined('ZF2_PATH') ? ZF2_PATH : (getenv('ZF2_PATH') ?: '/zf2/library'));

        // parent directory of this module
        $zf2ModulesPaths = dirname(dirname(__DIR__)) . PATH_SEPARATOR;
        // other paths to find modules one
        $zf2ModulesPaths .= getenv('ZF2_MODULES_TEST_PATHS') ?: realpath(__DIR__ . '/../../../vendor');

        // autoload ZF2
        include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true,
            ),
        ));

        // use ModuleManager to load this module and it's dependencies
        $config = array(
            'modules' => array(
                'KJSencha'
            ),
            'module_listener_options' => array(
                'config_glob_paths'    => array(
                    __DIR__ . '/config/autoload/{,*.}{global,local}.php',
                ),
                'config_cache_enabled' => false,
                'module_paths' => explode(PATH_SEPARATOR, $zf2ModulesPaths),
            ),
        );

        $app = Application::init($config);
        self::$serviceManager = $app->getServiceManager();
    }

    public static function getServiceManager()
    {
        return self::$serviceManager;
    }
}

// Load the user-defined test configuration file, if it exists; otherwise, load
// the default configuration.
if (is_readable(__DIR__ . DIRECTORY_SEPARATOR . 'TestConfiguration.php')) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestConfiguration.php';
} else {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'TestConfiguration.php.dist';
}

Bootstrap::go();
