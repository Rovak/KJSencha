<?php

namespace KJSenchaTest\Direct\Form;

use KJSencha\Annotation as Ext;

/**
 * Demonstrates uploading a file
 *
 * Mark the methods which handles a upload with Ext\Formhandler annotation
 */
class Upload
{
    /**
     * Upload File example
     *
     * Simply return the filenames which have been uploaded
     *
     * @Ext\Formhandler
     * @return array
     */
    public function uploadFile()
    {
        $files = array();

        foreach ($_FILES as $file) {
            $files[] = $file['name'];
        }

        return array(
            'success' => true,
            'msg' => 'File(s) succesfully uploaded:<br>' . implode('<br>', $files),
        );
    }
}