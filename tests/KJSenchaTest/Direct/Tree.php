<?php

namespace KJSenchaTest\Direct;

/**
 * Direct Tree Example
 *
 * @see http://docs.sencha.com/ext-js/4-1/#!/example/direct/direct-tree.html
 */
class Tree
{

    /**
     * Retrieve the nodes by a given ID
     *
     * @param string $id "root" or "n{number}"
     * @return array
     */
    public function getTree($id)
    {
        $out = array();
        if ($id == "root") {
            for ($i = 1; $i <= 5; ++$i) {
                array_push($out, array(
                    'id' => 'n' . $i,
                    'text' => 'Node ' . $i,
                    'leaf' => false
                ));
            }

        }
        // Check if the id is 2 long which means it is appended by a N
        else if (strlen($id) == 2) {
            $num = substr($id, 1);
            for ($i = 1; $i <= 5; ++$i) {
                array_push($out, array(
                    'id' => $id . $i,
                    'text' => 'Node ' . $num . '.' . $i,
                    'leaf' => true
                ));
            }
        }
        return $out;
    }

}