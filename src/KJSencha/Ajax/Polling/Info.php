<?php

namespace KJSencha\Ajax\Polling;

use KJSencha\Annotation as ExtJS;

class Info
{
    /**
      * @ExtJS\Interval(seconds="1")
     */
    public function getInfo()
    {
        return array(
            'seconds' => 1,
            'test' => 'gahas',
        );
    }

}
