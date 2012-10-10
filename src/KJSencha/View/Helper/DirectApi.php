<?php

namespace KJSencha\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\HeadScript;
use KJSencha\Frontend\Bootstrap;

/**
 * DirectApi helper - allows rendering Api definitions passed to Ext.direct.Manager
 */
class DirectApi extends AbstractHelper
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
        if ($directApi = $this->bootstrap->getDirectApi()) {
            $this->headScript->appendScript($directApi->buildRemotingProvider()->render());
        }
    }
}
