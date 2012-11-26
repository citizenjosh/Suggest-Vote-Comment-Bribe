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

class SuggestionsViewsugg extends JView
{
	private $_cats = null;

	function listcats($cat= null)
	{
		if(is_array($cat))
		{
			foreach($cat as $val)
			{
				$key=$val->id;
				$this->_cats[$key]=$val->title;
				if(is_array($val->children))
				{
					$this->listcats($val->children);
				}
			}
		}

	}

	function display($tpl = null)
	{
		JHTML::stylesheet( 'suggestvotecommentbribe.css', 'administrator/components/com_suggestvotecommentbribe/assets/' );

		//Data from model
		$item =& $this->get('Data');
		$isNew = ($item->id < 1);
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );

		$this->categories = $this->get('Categories');
		//echo '<pre>'; print_r($this->categories); echo '</pre>';
		
		// Call listcats function to generate _cats variable.
		$this->listcats($this->categories);
		
		//Get Categories list
		$this->cats=$this->_cats;

		JToolBarHelper::title(   '  ' .JText::_( 'Suggestion' ).': <small><small>[ ' . $text.' ]</small></small>', 'sugg');

		JToolBarHelper::save();
		JToolBarHelper::cancel();

		$editor =& JFactory::getEditor();
		$this->assignRef('editor', $editor);

		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $item->published );
		$lists['state'] = JHTML::_('select.booleanlist',  'state', 'class="inputbox"', $item->state );
		$this->assignRef('lists', $lists);

		$this->assignRef('item', $item);
		parent::display($tpl);
	}
}
