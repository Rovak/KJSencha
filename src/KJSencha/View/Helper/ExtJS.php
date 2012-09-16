<?php

namespace KJSencha\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Stdlib\ArrayUtils;

/**
 * Ext JS view helper
 */
class ExtJS extends AbstractHelper
{
    /**
     * Path which points to the library
     *
     * @var string
     */
    protected $libraryPath;

    protected $defaultOptions = array(
        'development'	=> TRUE,
        'theme'			=> 'default',
        'extCfg'		=> array(),
    );

    /**
     * Loading the library in a view
     *
     * @param array $options
     */
    public function loadLibrary(array $options = array())
    {
        $view = $this->getView();

        $options = ArrayUtils::merge($this->defaultOptions, $options);

        $libVersion = $options['development'] ? 'ext-all-dev.js' : 'ext-all.js';

        $view->headLink()
            ->appendStylesheet($this->getLibraryPath() . '/resources/css/ext-all.css');

        $view->headScript()
            ->prependFile($this->getLibraryPath() . '/' . $libVersion);
    }

    /**
     * Set the library path
     *
     * @param string $libraryPath
     */
    public function setLibraryPath($libraryPath)
    {
        $this->libraryPath = rtrim($libraryPath, '/');
    }

    /**
     * Retrieve the path to the library
     *
     * Will fallback to cdn.sencha.io
     *
     * @return string
     */
    public function getLibraryPath()
    {
        if (null == $this->libraryPath) {
            // Fallback to ExtJS CDN
            $this->libraryPath = 'http://cdn.sencha.io/ext-4.1.1-gpl';
        }

        return $this->libraryPath;
    }
}
