<?php

namespace KJSencha\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\HeadScript;
use KJSencha\Frontend\Bootstrap;

/**
 * Variables helper - allows rendering of configured JS variables required by your application
 */
class Variables extends AbstractHelper
{
    /**
     * @var HeadScript
     */
    protected $headScript;

    /**
     * @param HeadScript $headScript
     */
    public function __construct(HeadScript $headScript, Bootstrap $bootstrap)
    {
        $this->headScript = $headScript;
        $this->bootstrap = $bootstrap;
    }

    /**
     * Loads required variables in the head script
     *
     * @param array $options
     */
    public function __invoke()
    {
        $script = '';

        foreach ($this->bootstrap->getVariables() as $key => $value) {
            $script .= 'var ' . $key . '=' . json_encode($value) . ';' . PHP_EOL;
        }

        $this->headScript->appendScript($script);
    }
}
