<?php

namespace KJSencha\View\Model;

use Zend\View\Model\JsonModel;

/**
 * Ext JS resul
 */
class Result extends JsonModel
{
    /**
     * Make sure we merge everything, used by RenderingStrategy
     * @var type
     */
    protected $mergeUnnamedChildren = true;

    /**
     * Param which holds the root data
     *
     * @var string
     */
    protected $rootParam = 'rows';

    /**
     * @var array
     */
    private $exec = array();

    /**
     * @var array
     */
    private $add = array();

    private $result;

    public function __construct($variables = null, $options = null)
    {
        parent::__construct($variables, $options);

        $this->success = TRUE;
    }

    /**
     * Add something that will be executed
     *
     * @param mixed $exec
     */
    public function exec($exec)
    {
        $this->exec[] = $exec;
    }

    /**
     * @param mixed $add
     */
    public function add($add)
    {
        $this->add[] = $add;
    }

    /**
     * Set the result
     *
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set the success
     *
     * @param boolean $result
     */
    public function setSuccess($result)
    {
        $this->success = (boolean) $result;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * Retrieve the root param
     *
     * @return string
     */
    public function getRootParam()
    {
        return $this->rootParam;
    }

    /**
     * Set the root param
     *
     * @param string $rootParam
     */
    public function setRootParam($rootParam)
    {
        $this->rootParam = $rootParam;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        // Parse the _exec queue
        if ($exec = $this->exec) {
            $result = '';

            if (is_array($exec)) {
                foreach ($exec as $cmd) {
                    $result.= (string) $cmd . ';';
                }
            } else {
                $result = (string) $exec . ';';
            }

            $this->setVariable('_exec', $result);
        }

            // Parse the add queue
        if ($exec = $this->add) {
            $this->setVariable('_add', $this->add);
        }

        $this->setVariable($this->getRootParam(), $this->getResult());

        return parent::serialize();
    }
}
