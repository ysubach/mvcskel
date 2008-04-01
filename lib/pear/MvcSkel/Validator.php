<?php
// Whirix Ltd. development team
// site: http://www.whirix.com/
// mail: info@whirix.com
//
// $Id: Validator.php,v 1.2 2004/08/16 03:39:19 subach Exp $

require_once 'Validate.php';

/**
 * Perform validation of single data fields. This class is container (namespace)
 * for static validation functions. Each function raise error using ErrorManager
 * if validation failed.
 */
class MvcSkel_Validator {
    /**
     * Check if value not empty
     * @param string field
     * @param string value
     * @return true if value not empty, false otherwise
     */
    function checkNotEmpty($field, $value)
    {
        if (trim($value)=='') {
            
            MvcSkel_ErrorManager::raise($field, 'not empty value expected');
            return false;
        }
        else {

            return true;
        }
    }

    /**
     * Check if value is correct currency
     * @param string field
     * @param string value
     * @return true if value not empty, false otherwise
     */
    function checkCurrency($field, $value)
    {
        $value = trim($value);
        if (!ereg('^[[:digit:]]*\.{0,1}[[:digit:]]{0,2}$', $value)) {
            
            MvcSkel_ErrorManager::raise($field, 'please enter correct sum');
            return false;
        }
        else {

            return true;
        }
    }

    /**
     * Check if value is correct integer number
     * @param string field
     * @param string value
     * @return true if validation ok
     */
    function checkInteger($field, $value)
    {
        $value = trim($value);
        if (!ereg('^[[:digit:]]+$', $value)) {
            
            MvcSkel_ErrorManager::raise($field, 'please enter integer number');
            return false;
        }
        else {

            return true;
        }
    }

    /**
     * Check if value is correct URL
     * @param string field
     * @param string value
     * @return true if value not empty, false otherwise
     */
    function checkURL($field, $value)
    {
        $value = trim($value);
        if (!ereg('\:\/\/', $value)) {
            
            MvcSkel_ErrorManager::raise($field, 'please enter correct URL');
            return false;
        }
        else {

            return true;
        }
    }

    /**
     * Check if value is correct email
     * @param string field
     * @param string value
     * @return true if value is correct, false otherwise
     */
    function checkEmail($field, $value)
    {
        $value = trim($value);        
        if (!Validate::email($value)) {

            MvcSkel_ErrorManager::raise($field, 'please enter correct Email');
            return false;
        }
        return true;
    }

    /**
     * Check if file is not empty
     * @param string field
     * @param HTTP_Upload_File file
     * @return true if file not empty, false otherwise
     */
    function checkFileNotEmpty($field, $file)
    {
        if (!$file->isValid() or $file->getProp('size')==0) {
            
            MvcSkel_ErrorManager::raise($field, 'please upload not empty file');
            return false;
        }
        else {

            return true;
        }
    }

    /**
     * Check if image format is correct. This implementation check it using only
     * file extension.
     * @param string field
     * @param HTTP_Upload_File file
     * @return true if file is correct image, false otherwise
     */
    function checkImageFormat($field, $file)
    {
        $result = true;
        if ($file->isValid()) {

            $filename = $file->getProp('name');
            if (!ereg('\.gif$', $filename) and
                !ereg('\.png$', $filename) and
                !ereg('\.jpg$', $filename) and
                !ereg('\.jpeg$', $filename)) {
            
                MvcSkel_ErrorManager::raise($field, 'please upload PNG, GIF or JPEG file');
                return false;
            }
        }
        else {

            $result = false;
        }
        return $result;
    }
}

?>