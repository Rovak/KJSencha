<?php

namespace KJSencha\Frontend;

use ArrayObject;
use KJSencha\Direct\Remoting\Api\Api;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Model\ViewModel;

/**
 * Ext JS Bootstrap
 *
 * Allows PHP code to hook into the ext js bootstrap
 */
class Bootstrap
{
    protected $parameters = array();
    protected $paths = array();
    protected $variables = array();
    protected $requires = array();
    protected $modules = array();
    protected $viewModel;
    protected $template = 'kjsencha/bootstrap';
    protected $directApi;

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->viewModel = new ViewModel;
        $this->viewModel->setTemplate($this->template);

        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $this->setOption($key, $value);
        }
    }

    /**
     * Zet een optie
     *
     * @param string $key
     * @param mixed  $value
     */
    public function setOption($key, $value)
    {
        switch (strtolower($key)) {

            case 'modules':
                $this->setModules($value);
                break;

            case 'require':
            case 'requires':
                $this->setRequires($value);
                break;

            case 'paths':
                $this->setPaths($value);
                break;

            case 'variables':
                $this->setVariables($value);
                break;

            case 'directapi':
                $this->setDirectApi($value);
                break;

            default:
                $this->parameters[$key] = $value;
                break;
        }
    }

    /**
     * Set Ext.Loader paths
     *
     * @param array $paths
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }

    /**
     * Add javascript variables
     *
     * @param array $variables
     */
    public function addVariables(array $variables)
    {
        $this->variables = array_merge($this->variables, $variables);
    }

    /**
     * Set javascript variables
     *
     * @param array $variables
     */
    public function setVariables(array $variables)
    {
        $this->variables = $variables;
    }

    /**
     * Set classnames which will be included during bootstrap
     *
     * @param array
     */
    public function setRequires(array $requires)
    {
        $this->requires = $requires;
    }

    /**
     * Get the required classes
     *
     * @return array
     */
    public function getRequires()
    {
        return $this->requires;
    }

    /**
     * Get the Ext.Loader paths
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Get custom vars
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Get the modules that will be included in this bootstrap
     *
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Set the modules that will be included in this bootstrap
     *
     * @param array $modules
     */
    public function setModules(array $modules)
    {
        $this->modules = $modules;
    }

    /**
     * Retrieve the API
     *
     * @return Api
     */
    public function getDirectApi()
    {
        return $this->directApi;
    }

    /**
     * Set the Direct API
     *
     * @param Api $directApi
     */
    public function setDirectApi(Api $directApi)
    {
        $this->directApi = $directApi;
    }

    /**
     * @return ViewModel
     */
    public function getViewModel()
    {
        $this->viewModel->setVariables(array_merge($this->parameters, array(
            'bootstrap' => $this,
        )));

        return $this->viewModel;
    }

}
