<?php

namespace KJSencha\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\HeadLink;
use Zend\View\Helper\HeadScript;

/**
 * Ext JS view helper - aids in including ExtJs CSS/JS files
 */
class ExtJS extends AbstractHelper
{
    /**
     * @var HeadLink
     */
    protected $headLink;

    /**
     * @var HeadScript
     */
    protected $headScript;

    /**
     * @var array
     */
    protected $config;

    /**
     * @param array      $config
     * @param HeadLink   $headLink
     * @param HeadScript $headScript
     */
    public function __construct(array $config, HeadLink $headLink, HeadScript $headScript)
    {
        $this->config     = $config;
        $this->headLink   = $headLink;
        $this->headScript = $headScript;
    }

    /**
     * Loading the ExtJs library and CSS in a view
     */
    public function loadLibrary()
    {
        $lib = rtrim($this->config['library_path'], '/') . '/';

        foreach (array_reverse($this->config['css']) as $css) {
            $this->headLink->prependStylesheet($lib . $css);
        }

        foreach (array_reverse($this->config['js']) as $js) {
            $this->headScript->prependFile($lib . $js);
        }
    }
}
