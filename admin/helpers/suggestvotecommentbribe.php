<?php
/**
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license             GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * @package             Joomla.Administrator
 * @subpackage  com_messages
 * @since               1.6
 */
class SVCBHelper extends JController
{
/**
         * Configure the Linkbar.
         *
         * @param       string  The name of the active view.
         *
         * @return      void
         * @since       1.6
         */

        public static function addSubmenu($vName)
        {
		JSubMenuHelper::addEntry(
                        JText::_('COM_SVCB_SUGGS'),
                        'index.php?option=com_suggestvotecommentbribe&view=suggs',
                        $vName == 'suggs'
                );

		JSubMenuHelper::addEntry(
                        JText::_('COM_SVCB_COMMENTS'),
                        'index.php?option=com_suggestvotecommentbribe&view=comments',
                        $vName == 'comments'
                );	

                JSubMenuHelper::addEntry(
                        JText::_('COM_SVCB_VOTES'),
                        'index.php?option=com_suggestvotecommentbribe&view=votes',
                        $vName == 'votes'
                );

		JSubMenuHelper::addEntry(
                        JText::_('COM_SVCB_BRIBES'),
                        'index.php?option=com_suggestvotecommentbribe&view=bribes',
                        $vName == 'bribes'
                );	
	  	
		JSubMenuHelper::addEntry(
                        JText::_('COM_SVCB_LOGS'),
                        'index.php?option=com_suggestvotecommentbribe&view=logs',
                        $vName == 'logs'
                );	
        }

}

