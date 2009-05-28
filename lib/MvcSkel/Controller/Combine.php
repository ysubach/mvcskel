<?php

/**
* CSS and Javascript Combinator 0.5
*
* 
* Copyright 2006 by Niels Leenheer 
* 
* Permission is hereby granted, free of charge, to any person obtaining 
* a copy of this software and associated documentation files (the 
* "Software"), to deal in the Software without restriction, including 
* without limitation the rights to use, copy, modify, merge, publish, 
* distribute, sublicense, and/or sell copies of the Software, and to 
* permit persons to whom the Software is furnished to do so, subject to 
* the following conditions: 
*  
* The above copyright notice and this permission notice shall be 
* included in all copies or substantial portions of the Software. 
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE 
* LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION 
* OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION 
* WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. 
*
* PHP versions 4 and 5
*
* @category   framework
* @package    MvcSkel
* @modified   2007, Whirix Ltd, Sasha Evdokov.
* @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
* @link       http://trac.whirix.com/mvcskel
*/

/**
* Include pear cache.
*/
require_once 'Cache/Lite.php';

class MvcSkel_Controller_Combine extends MvcSkel_Controller {

    /**
    * Type request files (css or javascript)
    *
    * @var string $_type
    */
    var $_type;
    
    /**
    * Request files 
    *
    * @var string $_files
    */
    var $_files;

    public function actionCss() {
        $this->_type = 'css';
        $this->_files = $_REQUEST['files'];
        $this->combine();
    }
    public function actionJs() {
        $this->_type = 'javascript';
        $this->_files = $_REQUEST['files'];
        $this->combine();
    }
    
    /**
    * Combine and create cache
    */
    protected function combine() {
        $cache = true;
        $options = MvcSkel_Helper_Config::read();
        
        $cacheDir = rtrim($options['tmp'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $base = $this->_determineDir();

        $elements = explode(',', $this->_files);
        /*var_dump($elements);
        var_dump($_REQUEST);
        die();*/

        $hash = $this->_lastModified($base, $elements);

        header ("Etag: \"" . $hash . "\"");

        // Return visit and no modifications, so do not send anything
        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) &&
            stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == '"' . $hash . '"') {
            // Return visit and no modifications, so do not send anything
            header ("HTTP/1.0 304 Not Modified");
            header ('Content-Length: 0');
        } else {
            // First time visit or files were modified
            if ($cache) {
                // Determine supported compression method
                $gzip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');

                $encoding = $this->_checkMethod();

                // Try the cache first to see if the combined files were already generated
                $cacheFileId = 'cache-' . $hash . '.' . $this->_type . ($encoding != 'none' ? '.' . $encoding : '');

                $cacheLite = new Cache_Lite(array('cacheDir'=>$cacheDir));
                $cacheFile = $cacheLite->get($cacheFileId);

                if ($cacheFile) {
                  if ($encoding != 'none') {
                     header ("Content-Encoding: " . $encoding);
                  }

                  header ("Content-Type: text/" . $this->_type);
                  header ("Content-Length: " . strlen($cacheFile));

                  echo $cacheFile;

                  exit;
                }
            }

            // Get contents of the files
            $contents = '';
            reset($elements);
            while (list(,$element) = each($elements)) {
                $path = realpath($base . '/' . $element);
                $contents .= "\n\n" . file_get_contents($path);
            }

            // Send Content-Type
            header ("Content-Type: text/" . $this->_type);

            if (isset($encoding) && $encoding != 'none') {
                // Send compressed contents
                $contents = gzencode($contents, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE);
                header ("Content-Encoding: " . $encoding);
                header ('Content-Length: ' . strlen($contents));
                echo $contents;
            } else {
                // Send regular contents
                header ('Content-Length: ' . strlen($contents));
                echo $contents;
            }

            // Save cache
            $cacheFileId = 'cache-' . $hash . '.' . $this->_type . ($encoding != 'none' ? '.' . $encoding : '');

            $cacheLite = new Cache_Lite(array('cacheDir'=>$cacheDir));
//            $cacheLite = new Cache_Lite(array('cacheDir'=>$cacheDir, 'lifeTime'=>false));
            $cacheLite->save($contents,$cacheFileId);
	    }
    }

    /**
    * Determine the directory and type we should use
    * @param  string $dir base dir
    */
    protected function _determineDir($dir = '.') {
        if ($this->_type == 'css') {
           $base = realpath($dir.'/styles');
        } else if ($this->_type == 'javascript') {
           $base = realpath($dir.'/js');
        } else {
          header ("HTTP/1.0 503 Not Implemented");
          exit;
        }
        return $base;
    }


    /**
    * Determine last modification date of the files
    * @param  string $base base dir
    * @param  string $elements file list
    */
    protected function _lastModified($base, $elements) {
        $lastModified = 0;
        while (list(,$element) = each($elements)) {
            $path = realpath($base . '/' . $element);
            if ($path!==false && 
               (($this->_type == 'javascript' && substr($path, -3) != '.js') ||
                ($this->_type == 'css'        && substr($path, -4) != '.css'))) {
                header ("HTTP/1.0 403 Forbidden");
                exit;
            }
            if (substr($path, 0, strlen($base)) != $base || !file_exists($path)) {
            echo $path;
            die();
                header ("HTTP/1.0 404 Not Found");
                exit;
            }
            $lastModified = max($lastModified, filemtime($path));
        }
        return $lastModified . '-' . md5($this->_files);
    }


    /**
    * Determine supported compression method
    */
    protected function _checkMethod() {
        // Determine supported compression method
        $gzip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
        $deflate = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate');

        // Determine used compression method
        $encoding = $gzip ? 'gzip' : ($deflate ? 'deflate' : 'none');

        // Check for buggy versions of Internet Explorer
        if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Opera') &&
            preg_match('/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches)) {
            $version = floatval($matches[1]);
            if ($version < 6)
               $encoding = 'none';
            if ($version == 6 && !strstr($_SERVER['HTTP_USER_AGENT'], 'EV1'))
               $encoding = 'none';
        }
        return $encoding;
    }
}
