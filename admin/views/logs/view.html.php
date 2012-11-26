<?php
/**
 * @version $Id$
 * @package    Suggestion
 * @subpackage Views
 * @copyright Copyright (C) 2009 Interpreneurial LLC. All rights reserved.
 * @license GNU/GPL 
*/

//--No direct access
defined('_JEXEC') or die('=;)');

jimport('joomla.application.component.view');

class SuggestionsViewlogs extends JView
{

   function display($tpl = null)
   {
      JHTML::stylesheet( 'Suggestion.css', 'administrator/components/com_Suggestion/assets/' );
      JToolBarHelper::title(   '  ' .JText::_( 'logs' ), 'log');

 //     JToolBarHelper::deleteList();
 //     JToolBarHelper::editListX();
 //     JToolBarHelper::addNewX();

      $items   = & $this->get( 'Data');
      $pagination =& $this->get('Pagination');

      $lists = & $this->get('List');

      $this->assignRef('items', $items);
      $this->assignRef('pagination', $pagination);
      $this->assignRef('lists', $lists);

      parent::display($tpl);
   }// function

}// class
