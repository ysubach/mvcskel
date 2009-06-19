<?php
/**
 * CSS and Javascript Combinator.
 *
 * PHP versions 5
 *
 * @category   framework
 * @package    MvcSkel
 * @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
 * @link       http://trac.whirix.com/mvcskel
 */

/**
* Include pear cache.
*/
require_once 'Cache/Lite.php';

/**
 * Combine JS and CSS files.
 * @todo make this controller as helper?
 * @package    MvcSkel
 * @subpackage Controller
 */
class MvcSkel_Controller_Combine extends MvcSkel_Controller {
    private $type;

    private $logger;

    private $base;

    private $elements;

    /**
     * C-r.
     */
    public function  __construct() {
        session_cache_limiter('public');
        $this->logger = MvcSkel_Helper_Log::get(__CLASS__);
        $this->elements = explode(',', $_REQUEST['files']);
        $this->logger->debug('elements:'. $_REQUEST['files']);
    }

    public function actionJs() {
        $this->type = 'javascript';
        $this->base = 'js/';
        $this->combine();
    }
    public function actionCss() {
        $this->type = 'css';
        $this->base = 'styles/';
        $this->combine();
    }

    /**
    * Combine and create cache
    */
    protected function combine() {
        $lastmod = gmdate('D, d M Y H:i:s \G\M\T', $this->lastModified());
        $this->hash = md5($_REQUEST['files'].$lastmod);

        $this->logger->debug('lastmode:'. $lastmod);
        $this->logger->debug('type:'. $this->type);

        // maybe we just send 304 header
        $this->logger->debug('check browser cache...');
        $this->conditionalGet($lastmod);
        $this->logger->debug('no browser cache, continue');

        // Determine supported compression method
        $gzip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
        $this->logger->debug('gzip:'. $gzip);

        $encoding = $this->_checkMethod();
        $this->logger->debug('encoding:'. $encoding);

        // Try the cache first to see if the combined files were already generated
        $cacheFileId = 'cache-'.$this->hash.'.'.$this->type.'.'.$encoding;
        $this->logger->debug('cacheFileId:'. $cacheFileId);

        $options = MvcSkel_Helper_Config::read();
        $cacheDir = $options['tmp_dir'] . DIRECTORY_SEPARATOR;
        $cacheLite = new Cache_Lite(array('cacheDir'=>$cacheDir));
        $cacheFile = $cacheLite->get($cacheFileId);

        if ($cacheFile) {
            $this->logger->debug('disc cache is actual, output cached content');
            $this->output($cacheFile, $encoding);
            exit();
        }

        $this->logger->debug('no disk cache, continue...');

        // Get contents of the files
        $contents = $this->getContent();

        // Send compressed contents?
        if ($encoding != 'none') {
            $this->logger->debug('gzipping...');
            $contents = gzencode($contents, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE);
            $this->logger->debug('gzipped size: '.strlen($contents).' bytes');
        }
        $this->output($contents, $encoding);

        $cacheLite->save($contents, $cacheFileId);
        $this->logger->debug('cache created');
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
        echo $content;
        $this->logger->debug(strlen($content) . ' bytes sent to client');
    }

    /**
     * Determine last modification date of the files
     * AND SECURITY CHECK.
     * @param  string $base base dir
     * @param  string $elements file list
     */
    protected function lastModified() {
        $lastModified = 0;
        foreach ($this->elements as $element) {
            $path = realpath($this->base . $element);
            if (!file_exists($path)) {
                $this->logger->err('incorrect path: '.$this->base . $element);
                header ("HTTP/1.0 404 Not Found");
                exit;
            }
            if (strpos($path, $this->base)===false) {
                $this->logger->err('danger access defined: '.$path);
                header ("HTTP/1.0 403 Forbidden");
                exit;
            }
            if (($this->type == 'javascript' && substr($path, -3) != '.js') ||
                ($this->type == 'css'        && substr($path, -4) != '.css')) {
                header ("HTTP/1.0 403 Forbidden");
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
        $etag = '"' . $this->hash . '"';

        // ETag is sent even with 304 header
        header("ETag: $etag");
        $ifmod = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $lastmod : null;
        $iftag = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] == $etag : null;
        
        $this->logger->debug("sent: ETag: $etag");
        $this->logger->debug('HTTP_IF_MODIFIED_SINCE: '.$_SERVER['HTTP_IF_MODIFIED_SINCE']);
        $this->logger->debug('HTTP_IF_NONE_MATCH:'.$_SERVER['HTTP_IF_NONE_MATCH']);

        // If either matches and neither is a mismatch, send not modified header
        if (($ifmod || $iftag) && ($ifmod !== false && $iftag !== false)) {
            header('HTTP/1.0 304 Not Modified');
            exit();
        }
        // Last-Modified doesn't need to be sent with 304 response
        header("Last-Modified: $lastmod");
        $this->logger->debug("sent: Last-Modified: $lastmod");
    }
}
