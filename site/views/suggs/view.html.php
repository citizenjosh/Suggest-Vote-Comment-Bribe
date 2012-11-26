<?php
/**
 * @version $Id$
 * @package    Suggest Vote Comment Bribe
 * @subpackage Views
 * @copyright Copyright (C) 2010 Interpreneurial LLC. All rights reserved.
 * @license GNU/GPL
 */

//--No direct access
defined('_JEXEC') or die('=;)');

jimport('joomla.application.component.view');

class SuggestionViewsuggs extends JView
{

	function display($tpl = null)
	{
		JHTML::stylesheet( 'suggestvotecommentbribe.css', 'components/com_suggestvotecommentbribe/assets/' );

		$items   = & $this->get( 'Data');
		$pagination =& $this->get('Pagination');

		$lists = & $this->get('List');


		// get Itemid
		$menus = &JSite::getMenu();
		$menu  = $menus->getActive();
		$Itemid = $menu->id;

		// pack up the variables
		$this->assignRef('Itemid', $Itemid);
		$this->assignRef('items', $items);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('lists', $lists);

		parent::display($tpl);
	}// function

}// class
