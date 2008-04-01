<?php
/**
 * MvcSkel Smarty class definition.
 * @package MvcSkel
 */

/**
 * Include Smarty.
 */
require_once 'Smarty.class.php';

/**
 * Include Context.
 */
require_once 'MvcSkel/Context.php';

/**
 * Customized Smarty class
 * @package MvcSkel
 */
class MvcSkel_Smarty extends Smarty {
    /**
     * Define delimiters for smarty
     */
    var $left_delimiter  = '<!--{';
    var $right_delimiter = '}-->';
    var $debugging = false;
    var $compile_check = false;
    var $force_compile = false;
    
    /**
     * C-tor
     */
    function MvcSkel_Smarty() {
        // Define directories
        $opt = &PEAR::getStaticProperty('MvcSkel', 'options');
        
        $this->compile_dir   = $opt['tempDir'] . 'templates_c';
        $this->cache_dir     = $opt['tempDir'] . 'cache';
        $this->template_dir  = $opt['commonPath'] . 'templates';
        $this->plugins_dir[] = $opt['commonPath'] . 'lib/plugins/Smarty';
        $this->config_dir    = $this->template_dir;

        // Call parent c-tor
        $this->Smarty();

        // Set context
        $context = MvcSkel_Context::getCurrent();
        $this->assign('context', $context);

        $this->debugging     = $opt['smarty']['debugging'];
        $this->compile_check = $opt['smarty']['compile_check'];
        $this->force_compile = $opt['smarty']['force_compile'];
    }
}

