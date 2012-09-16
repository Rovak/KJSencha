<?php

namespace KJSencha\Data\Store;

use Zend\Stdlib\RequestInterface as Request;
use KJSencha\Options\Store as StoreOptions;

/**
 * Router
 */
class Router
{
    protected $options;

    /**
     * Constructor
     *
     * @param StoreOptions $options [description]
     */
    public function __construct(StoreOptions $options)
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

        if ( ($namespace = $this->options->getModule($query['module'])) == false) {
            throw new \Exception('Unkown module ' . $query['module']);
        }

        if ( ! isset($query['store'])) {
            throw new \Exception('Store required!');
        }

        return array(
            'className' => $namespace . '\\' . $query['store']
        );
    }
}
