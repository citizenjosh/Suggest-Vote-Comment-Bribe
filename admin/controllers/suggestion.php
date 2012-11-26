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

jimport('joomla.application.component.controller');

/**
 * Suggestion Controller
 *
 * @package    Suggest Vote Comment Bribe
 * @subpackage Controllers
 */
class SuggestionsControllerSuggestion extends SuggestionsController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add' , 'edit' );
	}// function

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'Suggestion' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 1);

		parent::display();
	}// function

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$post = JRequest::get('post');
		$msg='';
		if($post['max_title']>255||$post['max_title']<1||!is_numeric($post['max_title']))
		{
			$link = 'index.php?option=com_suggestvotecommentbribe&controller=suggestion&task=edit&cid[]=1';
			$this->setRedirect($link, JText::_('SET_MAX_TITLE_LENGTH'));
			return;
		}
		if($post['max_desc']>65000||$post['max_desc']<1||!is_numeric($post['max_desc']))
		{
			$link = 'index.php?option=com_suggestvotecommentbribe&controller=suggestion&task=edit&cid[]=1';
			$this->setRedirect($link, JText::_('SET_MAX_DESCRIPTION_LENGTH'));
			return;
		}
		if($post['login']==1&&$post['captcha']==1)
		{
			$post['captcha']=0;
			JRequest::setVar('captcha',0);
			$msg=JText::_('CAPTCHA_AUTOMATICALLY_DISABLED');
		}
		if($post['captcha']==1&&($post['prvk']==''||$post['pubk']==''))
		{
			$link = 'index.php?option=com_suggestvotecommentbribe&controller=suggestion&task=edit&cid[]=1';
			$this->setRedirect($link, JText::_('RECAPTCHA_API_KEYS_REQUIRED'));
			return;
		}
		if(!preg_match("/^[_a-z0-9-]+([\._][_a-z0-9-]+)*@[a-z0-9-]+([\._][a-z0-9-]+)*(\.[a-z]{2,5})$/", strtolower($post['email'])))
		{
			$link = 'index.php?option=com_suggestvotecommentbribe&controller=suggestion&task=edit&cid[]=1';
			$this->setRedirect($link, JText::_('EMAIL_ADDRESS_IS_REQUIRED'));
			return;
		}
		if(!preg_match("/^(https?:\/\/(www)?[\w\-]+(\.[\w\-])+)/i", strtolower($post['URL'])))
		{
			$link = 'index.php?option=com_suggestvotecommentbribe&controller=suggestion&task=edit&cid[]=1';
			$this->setRedirect($link, JText::_('ABSOLUTE_URL_IS_REQUIRED'));
			return;
		}
		$fp=fopen('http://api.recaptcha.net/noscript?k='.$post['pubk'],'r');
		if($post['pubk']&&strstr(fread($fp, 1000),'INVALID_PUBLIC_KEY'))
		{
			$link = 'index.php?option=com_suggestvotecommentbribe&controller=suggestion&task=edit&cid[]=1';
			$this->setRedirect($link, JText::_('INVALID_PUBLIC_KEY'));
			return;
		}
		include 'recaptchalib.php';
		$recaptcha_check=_recaptcha_http_post (RECAPTCHA_VERIFY_SERVER, "/verify",
		array (
                                                 'privatekey' => $post['prvk'],
                                                 'remoteip' => '',
                                                 'challenge' => '',
                                                 'response' => ''
                                                 )
                                                 );
                                                 if($post['prvk']&&strstr($recaptcha_check[1],'invalid-site-private-key'))
                                                 {
                                                 	$link = 'index.php?option=com_suggestvotecommentbribe&controller=suggestion&task=edit&cid[]=1';
                                                 	$this->setRedirect($link, JText::_('INVALID_PRIVATE_KEY'));
                                                 	return;
                                                 }
                                                 $model = $this->getModel('SUGGESTION');
                                                 if ($model->store($post)) {
                                                 	$msg .= JText::_( 'SETTINGS_SAVED' );
                                                 } else {
                                                 	$msg = JText::_( 'ERROR_SAVING_SETTINGS' );
                                                 }
                                                 $link = 'index.php?option=com_suggestvotecommentbribe';
                                                 $this->setRedirect($link, $msg);
	}// function


	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'OPERATION_CANCELLED' );
		$this->setRedirect( 'index.php?option=com_suggestvotecommentbribe', $msg );
	}// function

	function login()
	{
		$db=JFactory::getDBO();
		$db->setQuery("select*from #__suggestvotecommentbribe");
		$captcha=$db->loadObjectlist();
		$capch='';
		if($captcha[0]->login==0&&$captcha[0]->captcha==1)
		{
			$capch=',captcha=0';
			$msg=JText::_('CAPTCHA_AUTOMATICALLY_DISABLED');
		}
		if($captcha[0]->captcha==1&&($captcha[0]->prvk==''||$captcha[0]->pubk==''))
		{
			$capch=',captcha=0';
			$link = 'index.php?option=com_suggestvotecommentbribe';
			$this->setRedirect($link, JText::_('RECAPTCHA_API_KEYS_REQUIRED'));
			return;
		}
		$db->setQuery("update #__suggestvotecommentbribe set login=1-login$capch");
		$db->query();
		$this->setRedirect( 'index.php?option=com_suggestvotecommentbribe', $msg );
	}// function
	function captcha()
	{
		$db=JFactory::getDBO();
		$db->setQuery("select*from #__suggestvotecommentbribe");
		$captcha=$db->loadObjectlist();
		if($captcha[0]->captcha==0&&$captcha[0]->login==1)
		{
			$msg=JText::_('CANNOT_ENABLE_CAPTCHA_WHEN_LOGIN_REQUIRED');
			$this->setRedirect( 'index.php?option=com_suggestvotecommentbribe', $msg );
			return;
		}
		if($captcha[0]->captcha==0&&($captcha[0]->prvk==''||$captcha[0]->pubk==''))
		{
			$link = 'index.php?option=com_suggestvotecommentbribe';
			$this->setRedirect($link, JText::_('RECAPTCHA_API_KEYS_REQUIRED'));
			return;
		}
		$db->setQuery("update #__suggestvotecommentbribe set captcha=1-captcha");
		$db->query();
		$this->setRedirect( 'index.php?option=com_suggestvotecommentbribe', '' );
	}// function
	function showUser()
	{
		$db=JFactory::getDBO();
		$db->setQuery("select*from #__suggestvotecommentbribe");
		$captcha=$db->loadObjectlist();
		$capch='';
		if($captcha[0]->login==1&&$captcha[0]->captcha==1)
		{
			$capch=',captcha=0';
			$msg=JText::_('CAPTCHA_AUTOMATICALLY_DISABLED');
		}
		if($captcha[0]->captcha==1&&($captcha[0]->prvk==''||$captcha[0]->pubk==''))
		{
			$capch=',captcha=0';
			$msg=JText::_('RECAPTCHA_API_KEYS_REQUIRED');
		}
		$db->setQuery("update #__suggestvotecommentbribe set `show`=1-`show`$capch");
		$db->query();
		$this->setRedirect( 'index.php?option=com_suggestvotecommentbribe', $msg );
	}// function
}// class
