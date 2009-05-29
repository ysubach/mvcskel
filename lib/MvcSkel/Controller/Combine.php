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
    private $type;

    private $logger;

    private $base;

    private $elements;

    public function  __construct() {
        session_cache_limiter('public');
        $this->logger = MvcSkel_Helper_Log::get();
        $elements = trim($_REQUEST['files'], DIRECTORY_SEPARATOR);

        $this->hash = md5($elements);
        $elements = explode('?', $elements);
        $this->base = $elements[0];
        $this->elements = explode(',', $elements[1]);
    }

    public function actionCss() {
        $this->type = 'css';
        $this->combine();
    }
    public function actionJs() {
        $this->type = 'javascript';
        $this->combine();
    }

    static public function url($base, $files) {
        return MvcSkel_Helper_Url::url("/$base/?".join(urlencode(','), $files));
    }

    /**
    * Combine and create cache
    */
    protected function combine() {
        $lastmod = gmdate('D, d M Y H:i:s \G\M\T', $this->lastModified()) . ' GMT';

        $this->logger->debug('lastmode:'. $lastmod);
        $this->logger->debug('type:'. $this->type);

        // maybe we just send 304 header
        $this->logger->debug('check browser cache...');
        $this->conditionalGet($lastmod);
        $this->logger->debug('NO check browser cache...');

        // Determine supported compression method
        $gzip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
        $this->logger->debug('gzip:'. $gzip);

        $encoding = $this->_checkMethod();
        $this->logger->debug('encoding:'. $encoding);

        // Try the cache first to see if the combined files were already generated
        $cacheFileId = 'cache-'.$this->hash.'.'.$this->type.'.'.$encoding;
        $this->logger->debug('cacheFileId:'. $cacheFileId);

        $options = MvcSkel_Helper_Config::read();
        $cacheDir = rtrim($options['tmp'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $cacheLite = new Cache_Lite(array('cacheDir'=>$cacheDir));
        $cacheFile = $cacheLite->get($cacheFileId);

        if ($cacheFile) {
            $this->logger->debug('from cache: YES');
            $this->logger->debug('output cached content');
            $this->output($cacheFile, $encoding);
            exit();
        }

        $this->logger->debug('from cache: NO');

        // Get contents of the files
        $contents = $this->getContent();

        // Send compressed contents?
        if ($encoding != 'none') {
            $this->logger->debug('gzipping...');
            $contents = gzencode($contents, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE);
            $this->logger->debug('gzipped size: '.strlen($contents).' bytes');
        }
        $this->logger->debug('output newly generated content');
        $this->output($contents, $encoding);

        $this->logger->debug('creating cache');
        $cacheLite->save($contents, $cacheFileId);
    }

    /**
     * Output ready content with all correct headers
     */
    protected function output($content, $encoding) {
        header ("Content-Type: text/" . $this->type);
        if ($encoding != 'none') {
            header ("Content-Encoding: " . $encoding);
        }
        header ('Content-Length: ' . strlen($content));
        $this->logger->debug('echo '.strlen($content) . ' bytes');
        echo $content;
    }

    /**
    * Determine last modification date of the files
    * @param  string $base base dir
    * @param  string $elements file list
    */
    protected function lastModified() {
        $lastModified = 0;
        foreach ($this->elements as $element) {
            $path = realpath($this->base . $element);
            if ($path!==false &&
                (($this->type == 'javascript' && substr($path, -3) != '.js') ||
                    ($this->type == 'css'        && substr($path, -4) != '.css'))) {
                header ("HTTP/1.0 403 Forbidden");
                exit;
            }
            if (!file_exists($path)) {
                header ("HTTP/1.0 404 Not Found");
                exit;
            }
            $lastModified = max($lastModified, filemtime($path));
        }
        return $lastModified;
    }

    public function getContent() {
        foreach ($this->elements as $element) {
            $path = realpath($this->base . '/' . $element);
            $contents .= ("\n\n" . file_get_contents($path));
        }
        $this->logger->debug('compiled '.strlen($contents).' bytes');
        return $contents;
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

    /**
     * Send not modified header if nothing changed
     * @param int $lastmod last modification of content
     */
    protected function conditionalGet ($lastmod) {
        $lastmod = gmdate('D, d M Y H:i:s', intval($lastmod)) . ' GMT';
        $etag = '"' . $this->hash . '"';

        // ETag is sent even with 304 header
        header("ETag: $etag");
        $ifmod = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $lastmod : null;
        $iftag = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] == $etag : null;

        // If either matches and neither is a mismatch, send not modified header
        if (($ifmod || $iftag) && ($ifmod !== false && $iftag !== false)) {
            header('HTTP/1.0 304 Not Modified');
            die();
        }
        // Last-Modified doesn't need to be sent with 304 response
        header("Last-Modified: $lastmod");
    }
}
