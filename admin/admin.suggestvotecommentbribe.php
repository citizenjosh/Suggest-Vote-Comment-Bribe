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

// Require the base controller
require_once( JPATH_COMPONENT.DS.'controller.php' );
// Require specific controller if requested
if( $controller = JRequest::getWord('controller'))
{
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if( file_exists($path))
	{
		require_once $path;
	} else
	{
		$controller = '';
	}
}

#$language = JFactory::getLanguage();
#$language->load('com_suggestvotecommentbribe', JPATH_ADMINISTRATOR, 'en-GB', true);
#$language->load('com_suggestvotecommentbribe', JPATH_ADMINISTRATOR, null, true);
require_once JPATH_COMPONENT.'/helpers/suggestvotecommentbribe.php';
// Load the submenu.
SVCBHelper::addSubmenu(JRequest::getCmd('view', 'suggs'));

// Create the controller
$classname    = 'SuggestionsController'.$controller;
$controller   = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();
