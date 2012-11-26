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

class SuggestionsViewSuggestion extends JView
{
    /**
     * Suggestion view display method
     * 
     * @return void
     **/
   function display($tpl = null)
   {
      //get the Suggestion
      $Suggestion    =& $this->get('Data');
      $isNew      = ($Suggestion->id < 1);

      $text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
      JToolBarHelper::title(  'Suggestion: <small><small>[ ' . $text.' ]</small></small>' );
      JToolBarHelper::save();
      if ($isNew)  {
         JToolBarHelper::cancel();
      } else {
         // for existing items the button is renamed `close`
         JToolBarHelper::cancel( 'cancel', JText::_('Cancel') );
      }
      $lists['login'] = JHTML::_('select.booleanlist',  'login', 'class="inputbox"', $Suggestion->login);
      $lists['show'] = JHTML::_('select.booleanlist',  'show', 'class="inputbox"', $Suggestion->show);
      $lists['captcha'] = JHTML::_('select.booleanlist',  'captcha', 'class="inputbox"', $Suggestion->captcha);

      $this->assignRef('Suggestion', $Suggestion);
      $this->assignRef('lists', $lists);

      parent::display($tpl);
    }// function
}// class
