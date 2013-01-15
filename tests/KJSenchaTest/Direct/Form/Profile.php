<?php

namespace KJSenchaTest\Direct\Form;

use KJSencha\Annotation as Ext;

class Profile
{

    /**
     * Basic information
     *
     * @return array
     */
    public function getBasicInfo()
    {
        return array(
            'success' => true,
            'data' => array(
                'name' => 'Roy van Kaathoven',
                'email' => 'opensource@kj.nu',
                'company' => 'KJ Business Software',
            )
        );
    }

    /**
     * Update basic information
     *
     * @Ext\Formhandler
     */
    public function updateBasicInfo($values)
    {
        return array(
            'errors' => array(
                'name' => 'Wrong info!'
            )
        );
    }

    /**
     * @return array
     */
    public function getPhoneInfo()
    {
        return array(
            'success' => true,
            'data' => array(
                'office' => '1-800-CALLEXT',
                'cell' => '443-555-1234',
                'home' => '',
            )
        );
    }

    /**
     * @return array
     */
    public function getLocationInfo()
    {
        return array(
            'success' => true,
            'data' => array(
                'street' => '1234 Red Dog Rd.',
                'city' => 'Seminole',
                'state' => 'FL',
                'zip' => '33776',
            )
        );
    }

}