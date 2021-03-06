<?php
/**
 * @version $Id$
 * @package    Suggest Vote Comment Bribe
 * @subpackage _ECR_SUBPACKAGE_
 * @copyright Copyright (C) 2010 Interpreneurial LLC. All rights reserved.
 * @license GNU/GPL
 */

//--No direct access
defined('_JEXEC') or die('=;)');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Suggestion Component
 *
 * @package    Suggest Vote Comment Bribe
 * @subpackage Views
 */

class SuggestionsViewSuggestions extends JView
{
	/**
	 * Suggestions view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'COMPONENT_TITLE' ), 'generic.png' );
		//      JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		//      JToolBarHelper::addNewX();
		//		JToolBarHelper::preferences( 'com_suggestvotecommentbribe' );

		// Get data from the model
		$items =& $this->get('Data');
		$pagination =& $this->get('Pagination');

		// access the Component-wide default parameters
		$params = &JComponentHelper::getParams( 'com_suggestvotecommentbribe' );
		// add in the item parameters
		$params->merge( new JParameter( $row->params ) );

		// push data into the template
		$this->assignRef('items', $items);
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}//function

}//class
