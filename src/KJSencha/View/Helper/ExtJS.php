<?php

namespace KJSencha\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Helper\HeadLink;
use Zend\View\Helper\HeadScript;

/**
 * Ext JS view helper - aids in including ExtJs CSS/JS files
 */
class ExtJS extends AbstractHelper
{
    /**
     * Path which points to the library
     *
     * @var string
     */
    protected $libraryPath;

    /**
     * @var HeadLink
     */
    protected $headLink;

    /**
     * @var HeadScript
     */
    protected $headScript;

    protected $options = array(
        'development'   => true,
        'theme'         => 'default',
        'extCfg'        => array(),
        'libraryPath'   => '',
    );

    /**
     * @param string $headLink
     * @param HeadLink $headLink
     * @param HeadScript $headScript
     */
    public function __construct($libraryPath, HeadLink $headLink, HeadScript $headScript)
    {
        $this->options['libraryPath'] = rtrim((string) $libraryPath, '/');
        $this->headLink = $headLink;
        $this->headScript = $headScript;
    }

    /**
     * Loading the library in a view
     *
     * @param array $options
     */
    public function loadLibrary()
    {
        $libVersion = $this->options['development'] ? 'ext-all-dev.js' : 'ext-all.js';
        $this->headLink->appendStylesheet($this->options['libraryPath'] . '/resources/css/ext-all.css');
        $this->headScript->prependFile($this->options['libraryPath'] . '/' . $libVersion);
    }
}
