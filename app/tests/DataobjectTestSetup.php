<?php
// Whirix Ltd. development team
// site: http://www.whirix.com/
// mail: info@whirix.com
//
// $Id: DataobjectTestSetup.php,v 1.1 2005/07/19 03:21:40 iutin Exp $

require_once 'PHPUnit/TestDecorator.php';
require_once 'DB.php';

define('DB_DATAOBJECT_NO_OVERLOAD', true);

class DataobjectTestSetup extends PHPUnit_TestDecorator
{
    var $_dsn;
    var $_dataBase;
    var $_conn;
    
    /**
    * Class constructors.
    *
    * @param  object of class PHPUnit_TestCase or PHPUnit_TestSuite that will be wrap be this object
    * @access public
    */
    function DataobjectTestSetup(&$test)
    {
        $this->PHPUnit_TestDecorator($test);
        $this->_loadConfig();
        preg_match("/(\w+)$/", $this->_dsn, $matches);
        $this->_dataBase = $matches[0];
        $this->_dsn = preg_replace('/\/(\w+)$/', '', $this->_dsn);
        $this->_conn = DB::connect($this->_dsn);
    }
    
    /**
    * Create database and all tables according configuration.
    *
    * @access public
    */
    function createDB()
    {
        $this->_conn->query('DROP DATABASE IF EXISTS '.$this->_dataBase);
        $this->_conn->query('CREATE DATABASE '.$this->_dataBase);
        $this->_conn->query('USE '.$this->_dataBase);
    }
    
    /**
    * Drop currently used database
    *
    * @access public
    */
    function dropDB()
    {
        return $this->_conn->query('DROP DATABASE IF EXISTS '.$this->_dataBase);
    }
    
    /**
    * Delete data from all tables of database
    *
    * @param  string
    * @access public
    */
    function clearAll()
    {
        $result = $this->_conn->query('show tables');
        while ($row = $result->fetchRow())
        {
            $this->_conn->query('TRUNCATE TABLE '.$row[0]);
        }
    }
    
    /**
    * Execute all querys in SQL file.
    *
    * @param  string Name of file with SQL querys
    * @access public
    */
    function execute($sqlFile)
    {
        foreach (explode(',', $sqlFile) as $fileName)
        {
            if (($fileName = trim($fileName)) != '')
            {
                foreach (explode(';', @file_get_contents($fileName)) as $query)
                {
                    if (!trim($query))
                    {
                        continue;
                    }
                    //echo '<pre>'.$query.'</pre>';
                    $result = $this->_conn->query($query);
                    if (DB::isError($result) && $result->getCode() != DB_ERROR_ALREADY_EXISTS)
                    {
                        return $result;
                    }
                }
            }
        }
        return true;
    }
    
    /**
    * Sets up the fixture. This method is called before a test is executed.
    *
    * @access protected
    * @abstract
    */
    function setUp()
    {
        $this->createDB();
        if (isset($GLOBALS['__testCaseConf']['DataobjectTestSetup']['setupSQLFile']))
        {
            $this->execute($GLOBALS['__testCaseConf']['DataobjectTestSetup']['setupSQLFile']);
        }
    }
    
    /**
    * Tears down the fixture. This method is called after a test is executed.
    *
    * @access protected
    * @abstract
    */
    function tearDown() {
        $this->dropDB();
    }
    
    /**
    * Run TestCase
    *
    * @param  object PHPUnit_TestResult - A TestResult collects the results of executing a test case
    * @access public
    */
    function run(&$result)
    {
        $this->setUp();
        $this->_test->run($result);
        $this->tearDown();
    }
    
    /**
    * Load configuration.
    *
    * @access public
    */
    function _loadConfig()
    {
        if (!isset($GLOBALS['__testCaseConf']))
        {
            //Read configuration for Test Cases
            $config = new Config();
            $__config = $config->parseConfig('TestCaseConf.xml', 'xml');
            $__config = $__config->toArray();
            $__config = $__config['root']['configuration'];
            $GLOBALS['__testCaseConf'] = $__config;
        }
        //Reconfigarate PEAR::DB_DataObject
        $DBConf = &PEAR::getStaticProperty('DB_DataObject','options');
        if (isset($GLOBALS['__testCaseConf']['DataobjectTestSetup']))
        {
            foreach ($GLOBALS['__testCaseConf']['DataobjectTestSetup'] as $key => $value)
            {
	            $DBConf[$key] = $value;
            }
        }
        $this->_dsn = $DBConf['database'];
    }
}
?>