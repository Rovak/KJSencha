<?php

namespace KJSencha\Ajax\Rest;

use KJSencha\Data\Rest\AbstractModel;

class Klant extends AbstractModel
{
    public function read($id)
    {
        return array(
            'id' => $id,
            'omschrijving' => 'Klant : ' . $id
        );
    }

    public function update($id, $data)
    {
        return $data;
    }

    public function create($data)
    {
        return $data;
    }

    public function delete($id)
    {
        return $data;
    }
}
