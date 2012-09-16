<?php

namespace KJSencha\View\Model;

use Zend\View\Model\JsonModel;

/**
 * TODO
 */
class ExtJSModel extends JsonModel
{
    protected $mergeUnnamedChildren = true;

    protected $rootParam = 'rows';

    private $exec = array();
    private $add = array();

    public function __construct($variables = null, $options = null)
    {
        parent::__construct($variables, $options);

        $this->success = TRUE;
    }

    public function exec($exec)
    {
        $this->exec[] = $exec;
    }

    public function add($add)
    {
        $this->add[] = $add;
    }

    public function setResult($result)
    {
        $this->setVariable($this->rootParam, $result);
    }

    public function getResult()
    {
        return $this->__get($this->rootParam);
    }

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

        return parent::serialize();
    }
}
