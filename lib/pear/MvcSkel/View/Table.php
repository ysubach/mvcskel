<?php
// Whirix Ltd. development team
// site: http://www.whirix.com/
// mail: info@whirix.com
// 
// $Id: TableViewBase.php,v 1.4 2005/03/26 12:03:54 subach Exp $

require_once 'MvcSkel/View.php';
require_once 'MvcSkel/UrlConstructor.php';
require_once 'MvcSkel/UrlFactory/View.php';
 
/**
 * TableViewBase class
 */
class MvcSkel_View_Table extends MvcSkel_View
{
    /**
     * Smarty template that must be shown. Empty by default.
     */
    var $template = '';

    /**
     * Number of objects on one page. Equal to 10 by default.
     */
    var $pageSize = 10;

    /**
     * Prefix for all variables passed via GET/POST and stored in session. Empty
     * by default.
     */
    var $varPrefix = '';

    /**
     * UrlConstructor object clones of those used to produce all links assigned
     * to smarty for output. MUST be set.
     */
    var $linkPrefix;

    /**
     * Total number of objects
     */
    var $_count = 0;

    /**
     * List of object read from database
     */
    var $_objects = array();

    /**
     * Total number of pages
     */
    var $_pagesCount;

    /**
     * Current page number
     */
    var $_currentPage = 0;

    /**
     * Current column for sorting
     */
    var $_sortColumn = '';

    /**
     * Sorting direction
     */
    var $_sortDirection = 0;

    /**
     * Default sort direction
     */
    var $_defaultSortDirection = 1;
    
    /**
     * Search string
     */
    var $_searchString = '';

    /**
     * Seach array - consist field from database for search.
     */
    var $searchColumns = array();

    /**
     * description of all shown columns.
     * Hash map: alias of column is key, appropriate value is following hash
     * map:
     *  - 'db_name' - name of column in database;
     *  - 'as' - SQL alias name, it can be skipped.
     * Example:
     * $columns = array('name'    => array('db_name'   => 'state.name',
     *                                     'as' => 'state_name'),
     *                  'country' => array('db_name'   => 'country.name',
     *                                     'as' => 'country_name')
     *            );
     * It is empty by default.
     */
    var $columns = array();

    /**
     * Distance between pages
     */
    var $pageDistance = 10;

	/**
	 *	constructor function
	 *	@return	void
	 */
	function MvcSkel_View_Table() {
        // set some properties automatically
        $context = MvcSkel_Context::getCurrent();
		$contextString = $context->toString('/');
        // predefined template name, nobody prevent you to rewrite it 
        // in inherited class
        $this->template = "{$contextString}.tpl";
        $this->linkPrefix = MvcSkel_UrlFactory_View::makeUrl($contextString);
		$contextString = $context->toString('');		
        $this->varPrefix = strtolower($contextString);
	}

    /**
     * Process parameters for table view. Session and current HTTP request are
     * checked for appropriate params. $varPrefix added to all names.
     */
    function processParameters()
    {
        // Get parameter names
        $this->_initParameterNames();
        $pCurrentPage   = $this->_pCurrentPage;
        $pSortColumn    = $this->_pSortColumn;
        $pSortDirection = $this->_pSortDirection;
        $pSearchString  = $this->_pSearchString;        

        // Try to get params from session
        if (isset($_SESSION[$pCurrentPage])) {
            
            $this->_currentPage = $_SESSION[$pCurrentPage];
        }
        if (isset($_SESSION[$pSortColumn])) {
            
            $this->_sortColumn = $_SESSION[$pSortColumn];
        }
        if (isset($_SESSION[$pSortDirection])) {
            
            $this->_sortDirection = $_SESSION[$pSortDirection];
        }
        if (isset($_SESSION[$pSearchString])) {

            $this->_searchString = $_SESSION[$pSearchString];
        }

        // Try from HTTP request
        if (isset($_REQUEST[$pCurrentPage])) {
            
            $this->_currentPage = $_REQUEST[$pCurrentPage];
        }
        if (isset($_REQUEST[$pSortColumn])) {
            
            $this->_sortColumn = $_REQUEST[$pSortColumn];
            $this->_currentPage = 0;
        }
        if (isset($_REQUEST[$pSortDirection])) {
            
            $this->_sortDirection = $_REQUEST[$pSortDirection];
            $this->_currentPage = 0;
        }
        if (isset($_REQUEST[$pSearchString])) {

            $this->_searchString = $_REQUEST[$pSearchString];
            $this->_currentPage = 0;            
        }

        // Store parameters in session
        $_SESSION[$pCurrentPage]   = $this->_currentPage;
        $_SESSION[$pSortColumn]    = $this->_sortColumn;
        $_SESSION[$pSortDirection] = $this->_sortDirection;
        $_SESSION[$pSearchString]  = $this->_searchString;        
    }

    /**
     * Reset all view parameters to default values. Remove all parameters from
     * session.
     */
    function resetParameters()
    {
        // Get parameter names
        $this->_initParameterNames();
        $pCurrentPage   = $this->_pCurrentPage;
        $pSortColumn    = $this->_pSortColumn;
        $pSortDirection = $this->_pSortDirection;
        $pSearchString  = $this->_pSearchString;        

        // Remove them from session
        unset($_SESSION[$pCurrentPage]);
        unset($_SESSION[$pSortColumn]);
        unset($_SESSION[$pSortDirection]);
        unset($_SESSION[$pSearchString]);        

        // Reset to default
        $this->_currentPage   = 0;
        $this->_sortColumn    = '';
        $this->_sortDirection = 0;
        $this->_searchString  = '';        
    }

    /**
     * Initialize parameter names using prefix
     */
    function _initParameterNames()
    {
        $this->_pCurrentPage   = $this->varPrefix.'_current_page';
        $this->_pSortColumn    = $this->varPrefix.'_sort_column';
        $this->_pSortDirection = $this->varPrefix.'_sort_direction';
        $this->_pSearchString  = $this->varPrefix.'_search_string';        
    }

    function _setPages()
    {
        $this->_pagesCount = floor($this->_count / $this->pageSize);
        if ($this->_count % $this->pageSize > 0) {

            $this->_pagesCount++;
        }
        if ($this->_currentPage >= $this->_pagesCount and $this->_pagesCount > 0) {

            $this->_currentPage = $this->_pagesCount - 1;
        }
    }
    
    /**
     * Initialize object properties
     *
     * @param object $dataObject
     */
    function initialize(&$dataObject)
    {
        $this->processParameters();

        $this->_addSearchConditions($dataObject);
        $this->_addColumns($dataObject);

        // Calculate page relates values
        $this->_count = $this->_countObjects($dataObject);
        $this->_setPages();

        // Query params
        $db_column = '';
        if ($this->_sortColumn!='')
        {
            $scol =& $this->_sortColumn;
            if (isset($this->columns[$scol]['as']))
            {
                $db_column = $this->columns[$scol]['as'];
            }
            else
            {
                $db_column = $this->columns[$scol]['db_name'];
            }
        }
        $from = $this->pageSize * $this->_currentPage;

        // Make select
        $this->_getObjects($dataObject,
                           $db_column, $this->_sortDirection,
                           $from, $this->pageSize);
    }

    /**
     * selectAdd columns 
     *
     * @param object $dataObject
     */
    function _addColumns(&$dataObject)
    {
        $dataObject->selectAdd();
        foreach ($this->columns as $key=>$val){

            if (isset($val['as']))
            {
                $dataObject->selectAdd($val['db_name'].' as '.$val['as']);
            }
            else
            {
                $dataObject->selectAdd($val['db_name']);
            }
        }
    }

    /**
     *
     *
     */
    function _getObjects(&$dataObject,
                         $sortColumn, $sortDirection,
                         $limitFrom, $limitSize)
    {
        $this->_objects = $this->getList($dataObject, 0,
                                         $sortColumn, $sortDirection,
                                         $limitFrom, $limitSize);
    }

    /**
     *
     *
     */
    function _addSearchConditions(&$dataObject)
    {
        // Set search
        $this->_searchString = trim($this->_searchString);
        if ($this->_searchString!='') {

            $first = true;
            foreach ($this->searchColumns as $field) {
                
                $dataObject->whereAdd(($first === true ? '(' : ''). "$field LIKE '%".$dataObject->escape($this->_searchString)."%'", ($first === true ? 'AND' : 'OR'));
                $first = false;
            }
            $dataObject->whereAdd(")", '');
        }
    }
    
    /**
     *
     *
     */
    function _countObjects(&$dataObject)
    {
        return $dataObject->count();
    }


    /**
     * Get list of objects from database. Must be called only STATICALLY!
     *
     * @param $dataObject object used to perform database queries
     * @param $recursionDepth DEPRECATED
     * @param $sortColumn name of column for sorting, empty value to
     *                    disable sorting
     * @param $sortDirection 1 - ascending, -1 - descending, 0 - default
     * @param $fromRow start fetching object
     * @param $limitRows limit of fetching objects
     * @return array of fetched objects
     */
    function getList(&$dataObject,     $recursionDepth=0,
                     $sortColumn = '', $sortDirection = 0,
                     $fromRow = 0,     $limitRows = -1) {
        // Set query limits
        if ($limitRows==-1) {
            if ($fromRow>0) {
                $dataObject->limit($fromRow);
            }
        } else {
            $dataObject->limit($fromRow, $limitRows);
        }

        // Set sorting
        if ($sortColumn!='') {
            $orderStr = $sortColumn;
            if ($sortDirection==1) {
                $orderStr .= ' asc';
            }
            elseif ($sortDirection==-1) {
                $orderStr .= ' desc';
            }
            $dataObject->orderBy($orderStr);
        }

        // Read objects from database
        $objects = array();
        $dataObject->find();
        while ($dataObject->fetch()) {
            $objects[] = clone($dataObject);
        }

        return $objects;
    }
	
    /**
	 * Assign data to Smarty in preparation for display. You must call
	 * initialize() method of this object first.
     * Data assigned to smarty:
     *   'objectsCount' - total number of objects
     *   'pagesCount' - total number of pages
     *   'currentPage' - current page number
     *   'objects' - list of read objects
     *   'pageLinks' - list of links to pages
     *   'firstPageLink' -
     *   'lastPageLink' -
     *   'previousPageLink' -
     *   'nextPageLink' -
     *   'columns' - array of column's descriptors, each consist of:
     *       'alias' - column alias name
     *       'view_name' - name visible to user
     *       'sortLink' - link to enable sort
     *   'sortColumn' -
     *   'sortDirection' -
	 */
	function prepare()
    {
        parent::prepare();
        $firstPageLink = $this->linkPrefix->__copy();
        $firstPageLink->addVar($this->_pCurrentPage,'0');
        $firstPageLink = $firstPageLink->construct();
        
        $lastPageLink = $this->linkPrefix->__copy();
        $lastPageLink->addVar($this->_pCurrentPage,($this->_pagesCount-1));
        $lastPageLink = $lastPageLink->construct();
        
        $previousPage = $this->_currentPage - 1;
        if ($previousPage<0)
        {
            $previousPage = 0;
        }

        $previousPageLink = $this->linkPrefix->__copy();
        $previousPageLink->addVar($this->_pCurrentPage, $previousPage);
        $previousPageLink = $previousPageLink->construct();
        
        $nextPage = $this->_currentPage + 1;
        if ($nextPage>($this->_pagesCount-1))
        {
            $nextPage = ($this->_pagesCount-1);
        }

        $nextPageLink = $this->linkPrefix->__copy();
        $nextPageLink->addVar($this->_pCurrentPage, $nextPage);
        $nextPageLink = $nextPageLink->construct();

        $pageLinks       = array();
        $pageLinksEmpty  = array('name' => '..',
                                 'link' => '');

        $start =  $this->_currentPage - ($this->_currentPage % 10);
        $end   =  $start + 10;

        // left
        $pg = 0;
        $flag = false;
        while ($pg < $start)
        {
            $pgName = $pg;
            $pgLink = $this->linkPrefix->__copy();
            $pgLink->addVar($this->_pCurrentPage, $pg);
            $pageLinks[] = array('name' => $pgName,
                                 'link' => $pgLink->construct());
            $pg += $this->pageDistance;
            $flag = true;
        }
        if ($flag) {
            
            $pageLinks[] = $pageLinksEmpty;            
        }

        // center
        $pg = $start;
        while ($pg < $end && $pg < $this->_pagesCount)
        {
            $pgName = $pg;
            $pgLink = $this->linkPrefix->__copy();
            $pgLink->addVar($this->_pCurrentPage, $pg);
            $pageLinks[] = array('name' => $pgName,
                                 'link' => $pgLink->construct());
            $pg++;
        }
        
        // right
        $pg = $end + $this->pageDistance;
        $flag = false;        
        while ($pg < $this->_pagesCount) {

            if (!$flag) {
                
                $pageLinks[] = $pageLinksEmpty;
                $flag = true;
            }
            $pgName = $pg;
            $pgLink = $this->linkPrefix->__copy();
            $pgLink->addVar($this->_pCurrentPage, $pg);
            $pageLinks[] = array('name' => $pgName,
                                 'link' => $pgLink->construct());
            $pg += $this->pageDistance;
        }
        if (!$flag && $this->_pagesCount && ($pageLinks[count($pageLinks) - 1]['name'] != $this->_pagesCount - 1)) {

            $pageLinks[] = $pageLinksEmpty;
            $pageLinks[] = array('name' => $this->_pagesCount - 1,
                                 'link' => $lastPageLink);
        }
        
        $columns = array();
        $colAliases = array_keys($this->columns);
        foreach ($colAliases as $colAlias) {

            $col = $this->columns[$colAlias];

            // Set sort link.
            if ($this->_sortColumn==$colAlias) {

                $sortDirection = isset($this->_sortDirection) ? ((-1)*$this->_sortDirection) : $this->_defaultSortDirection;

                $col['sortLink'] = $this->linkPrefix->construct() .
                    '&' . $this->_pSortDirection . '=' . $sortDirection;
                if (empty($this->_sortColumn)) {

                    $col['sortLink'] .= '&' . $this->_pSortColumn . '=' . $colAlias;
                }

            }
            else {

                $col['sortLink'] = $this->linkPrefix->construct() .
                    '&' . $this->_pSortDirection . '=' . $this->_defaultSortDirection .
                    '&' . $this->_pSortColumn . '=' . $colAlias;
            }
            
            $columns[$colAlias] = $col;
        }        
	
        $smarty = &$this->smarty;
        $smarty->assign('objectsCount', $this->_count);
        $smarty->assign('pagesCount', $this->_pagesCount);
        $smarty->assign('currentPage', $this->_currentPage);
        $smarty->assign('objects', $this->_objects);
        $smarty->assign('pageLinks', $pageLinks);
        $smarty->assign('firstPageLink', $firstPageLink);
        $smarty->assign('lastPageLink', $lastPageLink);
        $smarty->assign('previousPageLink', $previousPageLink);
        $smarty->assign('nextPageLink', $nextPageLink);
        $smarty->assign('columns', $columns);
        $smarty->assign('sortColumn', $this->_sortColumn);
        $smarty->assign('sortDirection', $this->_sortDirection);
        $smarty->assign('searchString', $this->_searchString);
        $smarty->assign('pCurrentPage', $this->_pCurrentPage);
        $smarty->assign('pSortColumn', $this->_pSortColumn);
        $smarty->assign('pSortDirection', $this->_pSortDirection);
        $smarty->assign('pSearchString', $this->_pSearchString);
    }
}
?>
