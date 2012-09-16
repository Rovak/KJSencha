<?php

namespace KJSencha\Direct\Remoting\Api;

interface ApiInterface
{
    public function getUrl();
    public function getName();
    public function setUrl($url);
    public function getAction($name);
    public function hasAction($action);
    public function toApiArray();
    public function buildRemotingProvider();

}
