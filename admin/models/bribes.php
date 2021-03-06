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

class SuggestionsModelbribes extends JModel
{
	var $_data;

	function _buildQuery()
	{
		$where = array();

		/*      if ($this->search)
		 {
		 $where[] = 'LOWER(name) LIKE \''. $this->search. '\'';
		 }
		 */
		$where      = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		##ECR_MAT_FILTER_MODEL1##
		/*
		 if (($this->filter_order != 'title') && ($this->filter_order != 'type_id') && ($this->filter_order != 'description') && ($this->filter_order != 'e.ordering') && ($this->filter_order != 'id') && ($this->filter_order != 'min') && ($this->filter_order != 'def') && ($this->filter_order != 'max')) $this->filter_order = '';*/
		if (($this->filter_order) && ($this->filter_order_Dir))
		{
			$orderby    = ' ORDER BY '. $this->filter_order .' '. $this->filter_order_Dir;
		}
		else
		{
			$orderby=' ORDER BY #__suggestvotecommentbribe_bribe.id';
		}
		if($where)
		{
			$where.=' and SID=#__suggestvotecommentbribe_sugg.id';
		}
		else
		{
			$where=' where SID=#__suggestvotecommentbribe_sugg.id';
		}
		$this->_query = ' SELECT *'
		. ' FROM #__suggestvotecommentbribe_bribe,#__suggestvotecommentbribe_sugg'
		. $where
		. $orderby
		;
		return $this->_query;
	}


	var $_total = null;
	var $_pagination = null;

	function __construct()
	{
		parent::__construct();
		$app= JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$this->filter_order_Dir = $app->getUserStateFromRequest( $option.'b.filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$this->filter_order  = $app->getUserStateFromRequest( $option.'b.filter_order', 'filter_order',  'ordering', 'cmd' );
		$this->filter_order  = $app->getUserStateFromRequest( $option.'b.filter_order', 'filter_order',  'ordering', 'cmd' );

		//      $this->search     = $app->getUserStateFromRequest( "$option.search", 'search', '', 'string' );
		//      $this->search     = JString::strtolower( $this->search );

		$limit      = $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$limitstart = $app->getUserStateFromRequest( $option.'b.limitstart', 'limitstart', 0, 'int' );
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function getData()
	{
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}

	function getList()
	{
		// table ordering
		$lists['order_Dir']  = $this->filter_order_Dir;
		$lists['order']      = $this->filter_order;


		// search filter
		//      $lists['search']= $this->search;

		return $lists;
	}

	function getTotal()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}
}// class
