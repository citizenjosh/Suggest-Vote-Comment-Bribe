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

class SuggestionViewbribe extends JView
{
   function display($tpl = null)
   {
      JHTML::stylesheet( 'Suggestion.css', 'administrator/components/com_suggestion/assets/' );

      //Data from model
      $item =& $this->get('Data');
      $isNew = ($item->id < 1);
      $text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );

      $editor =& JFactory::getEditor();
$this->assignRef('editor', $editor);

      $lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $item->published );
$this->assignRef('lists', $lists);

      $this->assignRef('item', $item);
      parent::display($tpl);
   }
}
