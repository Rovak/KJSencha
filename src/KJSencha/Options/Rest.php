<?php

namespace KJSencha\Options;

use Zend\Stdlib\AbstractOptions;

class Rest extends AbstractOptions
{
    protected $modules = array();

    public function getModules()
    {
        return $this->modules;
    }

    public function getModule($module)
    {
        if ( ! isset($this->modules[$module])) {
            return false;
        }

        return $this->modules[$module];
    }

    public function setModules(array $modules)
    {
        $this->modules = array();

        foreach ($modules as $module => $namespace) {
            $this->addModule($module, $namespace);
        }

    }

    public function addModule($module, $namespace)
    {
        $this->modules[$module] = $namespace;
    }
}
