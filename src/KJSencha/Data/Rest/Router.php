<?php

namespace KJSencha\Data\Rest;

use Zend\Stdlib\RequestInterface as Request;
use KJSencha\Options\Rest as RestOptions;

/**
 * Router
 */
class Router
{
    /**
     * @var RestOptions
     */
    protected $options;

    /**
     * Constructor
     *
     * @param RestOptions $options [description]
     */
    public function __construct(RestOptions $options)
    {
        $this->options = $options;
    }

    /**
     * Request
     *
     * @param  Request $request [description]
     * @return [type]  [description]
     */
    public function match(Request $request)
    {
        $query = $request->getQuery()->toArray();

        if ( ! isset($query['module'])) {
            throw new \Exception('Module required!');
        }

        if ( false == ($namespace = $this->options->getModule($query['module']))) {
            throw new \Exception('Unknown module ' . $query['module']);
        }

        if ( ! isset($query['model'])) {
            throw new \Exception('Model required!');
        }

        return array(
            'className' => $namespace . '\\' . $query['model']
        );
    }
}
