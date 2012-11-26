<?php
/**
 * @version $Id$
 * @package    Suggest Vote Comment Bribe
 * @subpackage Models
 * @copyright Copyright (C) 2010 Interpreneurial LLC. All rights reserved.
 * @license GNU/GPL
 */

//--No direct access
defined('_JEXEC') or die('=;)');

jimport('joomla.application.component.model');

// import Joomla Categories library
jimport( 'joomla.application.categories' );


class SuggestionsModelsugg extends JModel
{
	private $_cats = null;

    	private $_parent = null;

	function __construct()
	{
		parent::__construct();
	}

	function store($data)
	{
		$row =& $this->getTable('sugg');

		if (!$row->bind($data)) {
			return false;
		}

		if (!$row->check()) {
			return false;
		}

		if (!$row->store()) {
			return false;
		}
		return true;
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row =& $this->getTable('sugg');

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

	function getData()
	{
		$id = JRequest::getVar('cid');
		$row =& $this->getTable('sugg');
		$row->load($id[0]);
		return $row;
	}

	function getCategories($recursive = false)
	{
		$categories = JCategories::getInstance('Content');
        	$this->_parent = $categories->get();
        	if(is_object($this->_parent))
        	{
            		$this->_cats = $this->_parent->getChildren($recursive);
        	}
	        else
	        {
	            	$this->_cats = false;
	        }
	
	        return $this->loadCats($this->_cats);

	}

   	protected function loadCats($cats = array())
    	{

        	if(is_array($cats))
	        {
	            $i = 0;
	            $return = array();
	            foreach($cats as $JCatNode)
	            {
	                $return[$i]->title = $JCatNode->title;
	                $return[$i]->id = $JCatNode->id;
	                if($JCatNode->hasChildren())
	                    $return[$i]->children = $this->loadCats($JCatNode->getChildren());
	                else
	                    $return[$i]->children = false;
	
	                $i++;
	            }
	
	            return $return;
	        }
	
	        return false;
	
	}	
}
