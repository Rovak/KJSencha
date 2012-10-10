<?php

namespace KJSencha\Service;

/**
 * Provides a test "echo" service to be used in new installations
 * as a test service
 */
class TestEchoService
{
    /**
     * @var string
     */
    protected $greeting;

    /**
     * @param string $greeting
     */
    public function __construct($greeting)
    {
        $this->greeting = (string) $greeting;
    }

    /**
     * @param string $name
     * @return string
     */
    public function greet($name)
    {
        return $this->greeting . $name;
    }
}
