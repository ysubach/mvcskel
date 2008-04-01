<?php
$cp = realpath(dirname(__FILE__) . '/..');
$p = array();
$p[] = $cp . '';
$p[] = $cp . '/lib/Smarty';
$p[] = $cp . '/lib/pear';
$p[] = $cp . '/lib/pear/MvcSkel';
$p[] = $cp . '/lib';
$p[] = $cp . '/Model';
$p[] = ini_get('include_path');
ini_set('include_path', join(PATH_SEPARATOR, $p));

session_start();

require_once 'PHPUnit.php';
require_once 'Config.php';
require_once 'MvcSkel/Context.php';
require_once 'PHPUnit/RepeatedTest.php';
require_once 'DataobjectTestSetup.php';

function TestCaseRunnerRec($root) {
    //Find test cases in currnet directory and run all of them
    foreach (glob($root.'/*Test.php') as $filename) {
        require_once $filename;
        $context = new MvcSkel_Context(str_replace ('.php','',$filename));
        $className = $context->pop();
        if (class_exists($className)) {
            if (!fromCC()) {
                echo "<h2>$className</h2>\n";
            }
            
            $methods = get_class_methods($className);
            if (in_array('suite', $methods)) {
                $object = new $className();
                $suite = $object->suite();
                unset($object);
                unset($methods);
            } else {
                $suite = new PHPUnit_TestSuite($className);	
            }
            $result = PHPUnit::run($suite);
            if (fromCC()) {
                echo $result->toString();
                // exit on first failed suite
                if (!$result->wasSuccessful()) {
                    exit(1);
                }
            } else {
                echo $result->toHTML();
            }
            
            unset($suite);
            unset($result);
            unset($object);
            unset($context);
            unset($className);
        }
    }
    
    //Search subdirectorys and go there 
    foreach (glob($root.'/*',GLOB_ONLYDIR) as $path) {
        if (is_dir($path)) {
            TestCaseRunnerRec($path);
        }
    }
}

// returns true if executed for cruise control
function fromCC() {
    return (isset($GLOBALS['argv']) && in_array('-cc', $GLOBALS['argv']));
}

//Read configuration for Test Cases
$config = new Config();
$__config = $config->parseConfig('TestCaseConf.xml', 'xml');
$__config = $__config->toArray();
$__config = $__config['root']['configuration'];
$GLOBALS['__testCaseConf'] = $__config;

$options = &PEAR::getStaticProperty('MvcSkel', 'options');
$options = $__config;
// emulates commonPath option variable
$options['commonPath'] = $cp;

if (isset($GLOBALS['__testCaseConf']['testPath'])) {
    TestCaseRunnerRec($GLOBALS['__testCaseConf']['testPath']);
}

?>