<?php
/**
* MvcSkel configurator.
*
* PHP versions 4 and 5
*
* @category   framework
* @package    MvcSkel
* @copyright  2007, Whirix Ltd.
* @license    http://www.gnu.org/licenses/lgpl.html GNU Lesser Public General License (LGPL).
* @link       http://trac.whirix.com/mvcskel
*/

/**
* Include base: DB-Generator
*/
require_once 'System.php';
require_once 'DB/DataObject/Generator.php';
require_once 'MvcSkel/Smarty.php';

/**
 * Model generator.
 *
 * Overrides some hook-methods for better phpDocumentor compatibility
 */
class MvcSkel_Generator extends DB_DataObject_Generator {
    function derivedHookPageLavelDocBlock() {
        return " * @package model\n";
    }
    function derivedHookExtendsDocBlock() {
        return "\n/**\n * Base classes definition\n */\n";
    }
    function derivedHookClassDocBlock() {
        $res = 
        "/**\n" .
        " * Model {$this->classname}\n" .
        " * @package model\n" .
        " */\n";
        return $res; 
    }

    /**
    * Generates View class for every table. Change nothing if file exists.
    */
    function generateViews() {
        $options = &PEAR::getStaticProperty('MvcSkel','options');
        $base = $options['commonPath'];
        $smarty = new MvcSkel_Smarty();
        $smarty->template_dir = '.';
        $smarty->left_delimiter = '{{';
        $smarty->right_delimiter = '}}';
        
        // views
        $this->_generateSkelEntity('View', $smarty, $base);
        $this->_generateSkelEntity('Action', $smarty, $base);
        $this->_generateSkelEntity('Form', $smarty, $base);
        $this->_generateSkelEntity('templates', $smarty, $base, '.tpl');
    }
    
    /**
    * Generate MvcSkel entity of given type.
    * @param string $type type of entity.
    * @param MvcSkel_Smarty $smarty object for rendering
    * @param string $base base path
    * @access protected
    */
    function _generateSkelEntity($type, &$smarty, $base, $ext = '.php') {
        foreach ($this->_definitions as $key=>$value) {
            // remove PK from properties (for templates ONLY)
            if ($ext=='.tpl') {
                $tmpVal = array();
                foreach ($value as $item) {
                    if (strpos($item->flags, 'primary_key')===false) {
                        $tmpVal[] = $item;
                    }
                }
                $smarty->assign('properties', $tmpVal);
            } else {
                $smarty->assign('properties', $value);
            }
            $smarty->assign('table', $key);
            $viewBase = "$base/$type/$key";
            System::mkdir($viewBase);
            
            $templates = $this->_getTemplateFileList($type);
            foreach ($templates as $tpl) {
                $tplName = basename($tpl, '.tpl');
                $smarty->assign('tplName', $tplName);
                $definitionFileName = "{$viewBase}/{$tplName}{$ext}";
                if (!file_exists($definitionFileName)) {
                    $res = $smarty->fetch($tpl);
                    $this->_file_put_contents($definitionFileName, $res);
                    $this->debug("Created $type for $key ($definitionFileName)");
                } else {
                    $this->debug("$type for $key exists ($definitionFileName), skipped");
                }
            }
        }
    }
    
    /**
    * Get file list from given folder.
    * @param string $folder where to get files.
    * @access protected
    * @return array of file names (strings)
    */
    function _getTemplateFileList($folder) {
        return glob("GeneratorTemplates/$folder/*.tpl");
    }

    /**
    * Write file with given name and content.
    * @param string $fn file name
    * @param string $content file content
    * @access protected
    */
    function _file_put_contents($fn, $content) {
        $h = fopen($fn, 'w');
        if (!$h) {
            $this->debug("can't open file $fn for writing");
            return false;
        }
        if (!fwrite($h, $content)) {
            $this->debug("can't write to file $fn for writing");
        }
        return fclose($h);
    }
}
