<?php

namespace KJSencha\Direct\Remoting\Api;

interface ApiInterface
{

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $url
     */
    public function setUrl($url);

    /**
     * @param string
     * @return \KJSencha\Direct\Remoting\Api\Object\Action
     */
    public function getAction($name);

    /**
     *
     * @param string $action
     * @return boolean
     */
    public function hasAction($action);

    /**
     * @return array
     */
    public function toApiArray();

    /**
     * @return \KJSencha\Frontend\Direct\RemotingProvider
     */
    public function buildRemotingProvider();
}
