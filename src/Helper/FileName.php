<?php

namespace App\Helper;

class FileName
{
    /**
     * @param $name
     * @return null|string
     */
    public static function getValidFileName($name)
    {
        $fileParts  = FileName::getFileNameParts($name);
        $parsedName = $fileParts['filename'];

        if (array_key_exists('extension', $fileParts)) {
            $parsedName = $fileParts['filename'] . '.' . $fileParts['extension'];
        }

        return $parsedName;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public static function getFileNameParts($name)
    {
        $pathInfo = pathinfo($name);

        if (array_key_exists('extension', $pathInfo)) {
            $pathInfo['extension'] = strtolower($pathInfo['extension']);
        }

        return $pathInfo;
    }
}