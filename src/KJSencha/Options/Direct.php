<?php

namespace KJSencha\Options;

use Zend\Stdlib\AbstractOptions;

class Direct extends AbstractOptions
{
    protected $modules = array();

    protected $defaultModule;

    public function setDefaultModule($module)
    {
        $this->defaultModule = (string) $module;
    }

    public function getDefaultModule()
    {
        return $this->defaultModule;
    }

    public function getModules()
    {
        return $this->modules;
    }

    public function getModule($name)
    {
        if ( ! isset($this->modules[$name])) {
            return null;
        }

        return $this->modules[$name];
    }

    public function setModules(array $modules)
    {
        $this->modules = array();
        $this->defaultModule = null;

        foreach ($modules as $name => $module) {
            $this->addModule($name, $module);
        }
    }

    /**
     * Add a module
     *
     * @param [type] $name    [description]
     * @param [type] $options [description]
     */
    public function addModule($name, $options)
    {
        $this->modules[$name] = array(
            'namespace' => $options['namespace'],
            'directory' => $options['directory'],
        );

        // If we do not have a default module then make the first one default
           if (null == $this->getDefaultModule()) {
            $this->setDefaultModule($name);
        }
    }
}
