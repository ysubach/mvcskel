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
        $lastmod = gmdate('D, d M Y H:i:s', $this->lastModified()).' GMT';
        $this->hash = md5($_REQUEST['files'].$lastmod);

        $this->logger->debug('lastmod:'. $lastmod);
        $this->logger->debug('type:'. $this->type);

        // maybe we just send 304 header
        $this->logger->debug('check browser cache...');
        $this->conditionalGet($lastmod);
        $this->logger->debug('no browser cache, continue');

        // Try the cache first to see if the combined files were already generated
        $cacheFileId = 'cache-'.$this->hash.'.'.$this->type;
        $this->logger->debug('cacheFileId:'. $cacheFileId);

        $options = MvcSkel_Helper_Config::read();
        $cacheDir = $options['tmp_dir'] . DIRECTORY_SEPARATOR;
        $cacheLite = new Cache_Lite(array('cacheDir'=>$cacheDir));
        $contents = $cacheLite->get($cacheFileId);

        if ($contents) {
            $this->logger->debug('disc cache is actual, output cached content');
            $this->output($contents);
            exit();
        }

        $this->logger->debug('no disk cache, continue...');

        // Get contents of the files
        $contents = $this->getContent();
        $this->output($contents);

        $cacheLite->save($contents, $cacheFileId);
        $this->logger->debug('cache created');
    }

    /**
     * Output ready content with all correct headers
     */
    protected function output($content) {
        header ("Content-Type: text/" . $this->type);
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
                    ($this->type == 'css' && substr($path, -4) != '.css')) {
                header ("HTTP/1.0 403 Forbidden");
                exit;
            }
            $lastModified = max($lastModified, filemtime($path));
        }
        return $lastModified;
    }

    public function getContent() {
        $content = '';
        foreach ($this->elements as $element) {
            $path = realpath($this->base . '/' . $element);
            $content .= ("\n\n" . file_get_contents($path));
        }
        $this->logger->debug('compiled '.strlen($content).' bytes');
        return $content;
    }

    /**
     * Send not modified header if nothing changed
     * @param int $lastmod last modification of content
     */
    protected function conditionalGet ($lastmod) {
        $etag = '"' . $this->hash . '"';

        // ETag is sent even with 304 header
        header("ETag: $etag");
        $this->logger->debug("sent: ETag: $etag");
        $ifmod = $iftag = null;
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $ifmod = $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $lastmod;
            $this->logger->debug('HTTP_IF_MODIFIED_SINCE: '.$_SERVER['HTTP_IF_MODIFIED_SINCE']);
        }
        if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            $iftag =  $_SERVER['HTTP_IF_NONE_MATCH'] == $etag;
            $this->logger->debug('HTTP_IF_NONE_MATCH:'.$_SERVER['HTTP_IF_NONE_MATCH']);
        }

        // If either matches and neither is a mismatch, send not modified header
        if (($ifmod || $iftag) && ($ifmod !== false && $iftag !== false)) {
            header('HTTP/1.1 304 Not Modified');
            exit();
        }

        // Last-Modified doesn't need to be sent with 304 response
        header("Last-Modified: $lastmod");
        $this->logger->debug("sent: Last-Modified: $lastmod");
    }
}
