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
 * Suggestion Controller
 *
 * @package    Suggestion
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
      JRequest::setVar('hidemainmenu', 1);

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
         $link = 'index.php?option=com_suggestion&controller=suggestion&task=edit&cid[]=1';
         $this->setRedirect($link, JText::_('Please set Max title length to a number between 1 and 255'));
        return;
     }
        if($post['max_desc']>65000||$post['max_desc']<1||!is_numeric($post['max_desc']))
        {
         $link = 'index.php?option=com_suggestion&controller=suggestion&task=edit&cid[]=1';
         $this->setRedirect($link, JText::_('Please set Max description length to a number between 1 and 65000'));
        return;
     }
     if($post['login']==1&&$post['capcha']==1)
     {
        $post['capcha']=0;
        JRequest::setVar('capcha',0);
        $msg=JText::_('Captcha was automatically disabled, ');
     }
        if($post['capcha']==1&&($post['prvk']==''||$post['pubk']==''))
        {
         $link = 'index.php?option=com_suggestion&controller=suggestion&task=edit&cid[]=1';
         $this->setRedirect($link, JText::_('To use reCAPTCHA you must get API keys from http://recaptcha.net/api/getkey'));
        return;
     }
      if(!preg_match("/^[_a-z0-9-]+([\._][_a-z0-9-]+)*@[a-z0-9-]+([\._][a-z0-9-]+)*(\.[a-z]{2,5})$/", strtolower($post['email'])))
        {
         $link = 'index.php?option=com_suggestion&controller=suggestion&task=edit&cid[]=1';
         $this->setRedirect($link, JText::_('email address is required to receive bribes'));
        return;
     }
      if(!preg_match("/^(https?:\/\/(www)?[\w\-]+(\.[\w\-])+)/i", strtolower($post['URL'])))
        {
         $link = 'index.php?option=com_suggestion&controller=suggestion&task=edit&cid[]=1';
         $this->setRedirect($link, JText::_('absolute URL is required to receive bribes'));
        return;
     }
      $fp=fopen('http://api.recaptcha.net/noscript?k='.$post['pubk'],'r');
      if($post['pubk']&&strstr(fread($fp, 1000),'Invalid public key. Make sure you copy and pasted it correctly.'))
      {
         $link = 'index.php?option=com_suggestion&controller=suggestion&task=edit&cid[]=1';
         $this->setRedirect($link, JText::_('Invalid public key. Make sure you copy and pasted it correctly.'));
        return;
      }
      include 'recaptchalib.php';
      $recapcha_check=_recaptcha_http_post (RECAPTCHA_VERIFY_SERVER, "/verify",
                                          array (
                                                 'privatekey' => $post['prvk'],
                                                 'remoteip' => '',
                                                 'challenge' => '',
                                                 'response' => ''
                                                 )
                                          );
      if($post['prvk']&&strstr($recapcha_check[1],'invalid-site-private-key'))
      {
         $link = 'index.php?option=com_suggestion&controller=suggestion&task=edit&cid[]=1';
         $this->setRedirect($link, JText::_('Invalid private key. Make sure you copy and pasted it correctly.'));
        return;
      }
      $model = $this->getModel('Suggestion');
      if ($model->store($post)) {
         $msg .= JText::_( 'Settings Saved!' );
      } else {
         $msg = JText::_( 'Error Saving Settings' );
      }
      $link = 'index.php?option=com_suggestion';
      $this->setRedirect($link, $msg);
   }// function


   /**
    * cancel editing a record
    * @return void
    */
   function cancel()
   {
      $msg = JText::_( 'Operation Cancelled' );
      $this->setRedirect( 'index.php?option=com_suggestion', $msg );
   }// function

   function login()
   {
        $db=JFactory::getDBO();
        $db->setQuery("select*from #__suggestion");
        $capcha=$db->loadObjectlist();
        $capch='';
        if($capcha[0]->login==0&&$capcha[0]->capcha==1)
     {
        $capch=',capcha=0';
        $msg='Captcha was automatically disabled';
     }
        if($capcha[0]->capcha==1&&($capcha[0]->prvk==''||$capcha[0]->pubk==''))
        {
        $capch=',capcha=0';
         $link = 'index.php?option=com_suggestion';
         $this->setRedirect($link, JText::_('To use reCAPTCHA you must get API keys from http://recaptcha.net/api/getkey'));
        return;
     }
        $db->setQuery("update #__suggestion set login=1-login$capch");
        $db->query();
      $this->setRedirect( 'index.php?option=com_suggestion', $msg );
   }// function
   function capcha()
   {
     $db=JFactory::getDBO();
        $db->setQuery("select*from #__suggestion");
        $capcha=$db->loadObjectlist();
        if($capcha[0]->capcha==0&&$capcha[0]->login==1)
     {
        $msg=JText::_('You can\'t enable CAPTCHA when login is required');
        $this->setRedirect( 'index.php?option=com_suggestion', $msg );
        return;
     }
        if($capcha[0]->capcha==0&&($capcha[0]->prvk==''||$capcha[0]->pubk==''))
        {
         $link = 'index.php?option=com_suggestion';
         $this->setRedirect($link, JText::_('To use reCAPTCHA you must get API keys from http://recaptcha.net/api/getkey'));
        return;
     }
     $db->setQuery("update #__suggestion set capcha=1-capcha");
     $db->query();
     $this->setRedirect( 'index.php?option=com_suggestion', '' );
   }// function
   function showUser()
   {
     $db=JFactory::getDBO();
        $db->setQuery("select*from #__suggestion");
        $capcha=$db->loadObjectlist();
        $capch='';
        if($capcha[0]->login==1&&$capcha[0]->capcha==1)
     {
        $capch=',capcha=0';
        $msg=JText::_('Capcha was automatically disabled, ');
     }
        if($capcha[0]->capcha==1&&($capcha[0]->prvk==''||$capcha[0]->pubk==''))
        {
        $capch=',capcha=0';
         $msg=JText::_('To use reCAPTCHA you must get API keys from http://recaptcha.net/api/getkey');
     }
        $db->setQuery("update #__suggestion set `show`=1-`show`$capch");
        $db->query();
      $this->setRedirect( 'index.php?option=com_suggestion', $msg );
   }// function
}// class
