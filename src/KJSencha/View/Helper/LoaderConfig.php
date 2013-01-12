<?php

namespace KJSencha\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\HeadScript;
use Zend\View\Helper\BasePath;
use KJSencha\Frontend\Bootstrap;

/**
 * LoaderConfig helper - allows rendering of of ExtJs.Loader configs so that
 * JS libraries can be loaded from different paths
 */
class LoaderConfig extends AbstractHelper
{
    /**
     * @var HeadScript
     */
    protected $headScript;

    /**
     * @var BasePath
     */
    protected $basePath;

    /**
     * @param BasePath   $basePath
     * @param HeadScript $headScript
     * @param Bootstrap  $bootstrap
     */
    public function __construct(BasePath $basePath, HeadScript $headScript, Bootstrap $bootstrap)
    {
        $this->headScript = $headScript;
        $this->bootstrap = $bootstrap;
        $this->basePath = $basePath;
    }

    /**
     * Appends the required configs in a head script
     */
    public function __invoke()
    {
        $namespaces = $this->bootstrap->getPaths();

        if ($namespaces) {
            foreach ($namespaces as $namespace => $path) {
                if ($path[0] !== '/') {
                    $namespaces[$namespace] = $this->basePath->__invoke($path);
                }
            }

            $data = array(
                'enabled'  => true,
                'paths'    => $namespaces,
            );

            $this->headScript->appendScript('Ext.Loader.setConfig(' . json_encode($data) . ');');
        }

        if ($requires = $this->bootstrap->getRequires()) {
            $this->headScript->appendScript('Ext.syncRequire(' . json_encode($requires) . ');');
        }
    }
}
