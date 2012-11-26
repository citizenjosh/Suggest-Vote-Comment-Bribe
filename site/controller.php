<?php
/**
 * @version $Id$
 * @package    Suggestion
 * @subpackage _ECR_SUBPACKAGE_
 * @copyright Copyright (C) 2009 Interpreneurial LLC. All rights reserved.
 * @license GNU/GPL 
*/

//--No direct access
defined('_JEXEC') or die('=;)');

jimport('joomla.application.component.controller');

/**
 * Suggestion default Controller
 *
 * @package    Suggestion
 * @subpackage Controllers
 */
class SuggestionController extends JController
{
   /**
    * Method to display the view
    *
    * @access  public
    */
   function display()
   {
	if($_REQUEST['view']=='sugg')
	{
		$db=JFactory::getDBO();
		$thisuser=JFactory::getUser();
		$cids=implode(',',$_REQUEST['cid']);
		$db->setQuery("select * from #__suggestion_sugg where ID in ($cids)");
		$item=$db->loadObjectlist();
		$item=$item[0];
		if($item->published==0&&(($thisuser!=0||$thisuser!=$item->UID)&&!$_COOKIE['suggest'.$item->id]))
		{
			$this->setRedirect('index.php?option=com_suggestion&view=suggs',JTEXT::_('THISWASUNPUBLISHED'));
		}
	}
      parent::display();
   }// function
      
   
}// class
