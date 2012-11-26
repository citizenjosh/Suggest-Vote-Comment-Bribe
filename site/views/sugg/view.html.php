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

class SuggestionViewsugg extends JView
{

   function display($tpl = null)
   {
      JHTML::stylesheet( 'suggestvotecommentbribe.css', 'administrator/components/com_suggestvotecommentbribe/assets/' );

      //Data from model
      $item =& $this->get('Data');
      $isNew = ($item->id < 1);
      $text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
            
      $editor =& JFactory::getEditor();
      $this->assignRef('editor', $editor);
      //get the rest
      $cids='';
      foreach($_COOKIE as $key=>$val)
      {
          if($val==1&&strstr($key,'comment'))
          {
              $cids.=substr($key, 7).',';
          }
      }
      $cids=trim($cids,",");
      $user=JFactory::getUser();
      $db=JFactory::getDBO();
      $cids.='0';
      $db->setQuery('SELECT * FROM #__suggestvotecommentbribe_comment where SID='.$item->id.' and (published=1 or (UID='.$user->id.' and UID!=0) or id in('.$cids.')) order by id');
      $comments = $db->loadObjectlist();
      $this->assignRef('comments', $comments);
      $db->setQuery('SELECT * FROM #__suggestvotecommentbribe_vote where SID='.$item->id.' and (published=1 or (UID='.$user->id.' and UID!=0)) order by id');
      $votes = $db->loadObjectlist();
      $this->assignRef('votes', $votes);
//      $db->setQuery('SELECT sum(value) as sum FROM #__suggestvotecommentbribe_vote where SID='.$item->id.' and published=1 group by SID');
//      $avgvote = $db->loadObjectlist();
//      $this->assignRef('avgvote', $avgvote[0]->sum);

      $lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $item->published );
      $lists['state'] = JHTML::_('select.booleanlist',  'state', 'class="inputbox"', $item->state );
      $this->assignRef('lists', $lists);
      $db->setQuery('SELECT * FROM #__suggestvotecommentbribe');
      $settings = $db->loadObjectlist();
      $this->assignRef('settings', $settings[0]);

      $this->assignRef('item', $item);      
      parent::display($tpl);
   }// function

}// class
