<?php

namespace KJSencha\Frontend\Direct;

use KJSencha\Frontend as Ext;

/**
 * Ext Direct Remoting Provider
 *
 * @see http://docs.sencha.com/ext-js/4-1/#!/api/Ext.direct.RemotingProvider
 */
class RemotingProvider extends Ext\Base
{
    protected $attributes = array(
        'type'		=> '',
        'url'		=> '',
        'namespace' => '',
        'actions'	=> array(),
    );

    /**
     * Set the url to which requests will be send
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->attributes['url'] = $url;
    }

    /**
     * @return string
     */
    public function render()
    {
        $str = sprintf('Ext.direct.Manager.addProvider(%s);', $this->toJson());

        return $str;
    }
}
