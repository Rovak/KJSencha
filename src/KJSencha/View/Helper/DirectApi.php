<?php

namespace KJSencha\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\HeadScript;
use KJSencha\Frontend\Bootstrap;

/**
 * DirectApi helper - allows rendering API definitions passed to Ext.direct.Manager
 */
class DirectApi extends AbstractHelper
{
    /**
     * @var HeadScript
     */
    protected $headScript;

    /**
     * @param HeadScript $headScript
     * @param Bootstrap  $bootstrap
     */
    public function __construct(HeadScript $headScript, Bootstrap $bootstrap)
    {
        $this->headScript = $headScript;
        $this->bootstrap = $bootstrap;
    }

    /**
     * Loads Ext.direct.Manager API configuration in head scripts
     */
    public function __invoke()
    {
        if ($directApi = $this->bootstrap->getDirectApi()) {
            $this->headScript->appendScript($directApi->buildRemotingProvider()->render());
        }
    }
}
