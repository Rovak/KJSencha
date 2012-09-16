<?php

namespace KJExtJs\Data\Rest;

use DomainException;
use Exception;
use KJSencha\Data\AjaxController;
use KJSencha\View\Model\Result;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;

/**
 * Rest Model
 *
 * Represents the REST models
 */
abstract class AbstractModel extends AjaxController
{
    protected $primaryKey = 'id';
    protected $success = true;
    protected $rootParam = 'data';
    protected $successParam = 'success';

    abstract public function read($id);

    abstract public function update($id, $data);

    abstract public function create($data);

    abstract public function delete($id);

    public function setSuccess($success)
    {
        $this->success = (boolean) $success;
    }

    public function isSuccess()
    {
        return $this->success;
    }

    public function getList()
    {
        throw new DomainException('Not implemented');
    }

    /**
     * Dispatch ajax request
     *
     * @param  Request $request  [description]
     * @param  [type]  $response [description]
     * @return [type]  [description]
     */
    public function dispatch(Request $request, Response $response = null)
    {
        $data = parent::dispatch($request, $response);

        // Define id, is NULL if not set
        $id = $data->getVariable($this->primaryKey, NULL);

        try {
            switch (strtolower($request->getMethod())) {
                case 'get':
                    if (null !== $id) {
                        $return = $this->read($id);
                        break;
                    }
                    $return = $this->getList();
                    break;
                case 'post':
                    $return = $this->create($data);
                    break;
                case 'put':
                    if (null === $id) {
                        throw new DomainException('Missing identifier');
                    }
                    $return = $this->update($id, $data);
                    break;
                case 'delete':
                    if (null === $id) {
                        throw new DomainException('Missing identifier');
                    }
                    $return = $this->delete($id);
                    break;
                default:
                    throw new DomainException('Invalid HTTP method!');
            }
            

            if ($return instanceof Result) {
                $result = $return;
            } else {
                $result = new Result;
                $result->setResult($return);
            }
            
            $result->setRootParam($this->rootParam);

            $result->setSuccess(true);

        } catch (Exception $e) {
            $result = new Result;
            $result->setSuccess(false);
            $result->error = array(
                'className' => get_class($e),
                'message' => $e->getMessage(),
            );
        }

        return $result;
    }

}
