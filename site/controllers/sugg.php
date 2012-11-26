<?php
/**
 * @version $Id$
 * @package    Suggestion
 * @subpackage Controllers
 * @copyright Copyright (C) 2009 Interpreneurial LLC. All rights reserved.
 * @license GNU/GPL 
*/

//--No direct access
defined('_JEXEC') or die('=;)');

jimport('joomla.application.component.controller');

class SuggestionControllersugg extends JController
{
   var $cid;

   function __construct($config = array())
   {
      parent::__construct($config);
      $this->cid = JRequest::getVar( 'cid', array(0), '', 'array' );
      JArrayHelper::toInteger($this->cid, array(0));
      $this->registerTask( 'unpublish',   'publish');
   }// function
   
   function display()
   {
      parent::display();
   }// function
   
   function _buildQuery()
   {
      $user=JFactory::getUser();
      $this->_query = 'UPDATE #__suggestion_sugg'
      . ' SET published = ' . (int) $this->publish
      . ' WHERE id IN ( '. $this->cids .' )'
      ;
      return $this->_query;     
   }
   function edit()
   {
      $model = $this->getModel( 'security' );
      $can=$model->canSuggest(JRequest::getVar('cid'));
     if($can!==true)
      {
          $this->setRedirect( 'index.php?option=com_suggestion&view=suggs', $can );
       return;
     }
      JRequest::setVar( 'view', 'sugg' );
      JRequest::setVar( 'layout', 'form'  );
      parent::display();
   }

   function publish()
   {
      $cid     = JRequest::getVar( 'cid', array(), '', 'array' );
      $this->publish = ( $this->getTask() == 'publish' ? 1 : 0 );

      JArrayHelper::toInteger($cid);
      if (count( $cid ) < 1)
      {
         $action = $publish ? 'publish' : 'unpublish';
         JError::raiseError(500, JText::_( 'Select an item to' .$action, true ) );
      }

      $this->cids = implode( ',', $cid );

      $model = $this->getModel( 'security' );
      $can=$model->canSuggest(JRequest::getVar('cid'));
     if($can!==true&&!$_COOKIE['suggest'.$cid[0]])
      {
          $this->setRedirect( 'index.php?option=com_suggestion&view=suggs', $can );
       return;
     }

      $query = $this->_buildQuery();
      $db = &JFactory::getDBO();
      $db->setQuery($query);
      if (!$db->query())
      {
         JError::raiseError(500, $db->getErrorMsg() );
      }
     $user =& JFactory::getUser();
     $model1 = $this->getModel( 'log' );
     $post1['title']=$cid[0];
     if($user->id)
        $post1['description']=$user->get('name');
     else   $post1['description']='Anonymous';
     $post1['description'].=' has ';
     $post1['description'].=$this->getTask().'ed';
     $post1['description'].=' a suggestion at '.date(DATE_RFC822);
     $model1->store($post1);
      $link = 'index.php?option=com_suggestion&view=suggs';
      $this->setRedirect($link, '');
   }

   function save()
   {
      $post = JRequest::get('post');
      $cid  = JRequest::getVar( 'cid', array(0), 'post', 'array' );
      $model_can = $this->getModel( 'security' );
      $can=$model_can->canSuggest($cid);
     if($can!==true)
      {
          $this->setRedirect( 'index.php?option=com_suggestion&view=suggs', $can );
       return;
     }

      
      #_ECR_SMAT_DESCRIPTION_CONTROLLER1_
      $post['id']    = (int) $cid[0];
      $db = &JFactory::getDBO();
      $db->setQuery('select * from #__suggestion');
      $capcha=$db->loadObjectlist();
      $user=JFactory::getUser();
      if($capcha[0]->capcha&&!$user->id)
      {
         include(JPATH_ROOT."/components/com_suggestion/recaptchalib.php");
         $resp = recaptcha_check_answer ($capcha[0]->prvk,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

         if (!$resp->is_valid) 
         {
            $link = 'index.php?option=com_suggestion&view=suggs';
            $this->setRedirect( $link, 'You entered a wrong CAPTCHA');
            return;
         }
      }
      if($post['title']=='')
      {
          $t=time();
         $link = 'index.php?option=com_suggestion&controller=sugg&task=edit&ses=s'.$t;
          $_SESSION['s'.$t]=$_POST['description'];
          $this->setRedirect( $link, 'title is required');
          return;
     }
      $post['title']=substr($_POST['title'], 0,$capcha[0]->max_title);
      $post['description']=substr($_POST['description'], 0,$capcha[0]->max_desc);
      $post['title']=(htmlentities($post['title'],ENT_NOQUOTES));
      $post['description']=((htmlentities($post['description'],ENT_NOQUOTES)));
      $post['title']=str_replace("\\\\", "\\", $post['title']);
      $post['title']=str_replace("\\\"", "\"", $post['title']);
      $post['title']=str_replace("\\'", "'", $post['title']);
      $post['description']=str_replace("\\\\", "\\", $post['description']);
      $post['description']=str_replace("\\\"", "\"", $post['description']);
      $post['description']=str_replace("\\'", "'", $post['description']);
      $post['title']=str_replace(' ','&nbsp;',$post['title']);
      $post['description']=nl2br(str_replace(' ','&nbsp;',$post['description']));

      if($post['id']===0)
      {
         $posted='posted';
      }
      else
      {
         $posted='updated';
      }      
      $model = $this->getModel( 'sugg' );
      $can=$model_can->canSuggest($cid);
     if($can!==true)
      {
          $this->setRedirect( 'index.php?option=com_suggestion&view=suggs', $can );
       return;
     }
      if ($model->store($post)) {
         $msg = JText::_( 'Item Saved' );
         $user =& JFactory::getUser();
         $post['title']=htmlentities($post['title'],ENT_NOQUOTES);
         $post['description']=nl2br(htmlentities($post['description'],ENT_NOQUOTES));
	 if($post[id]==0)
	 { 
	 	$CID=mysql_insert_id();
	 }
	 else
	 {
		$CID=$post[id];
	 }
	 if($CID[0]->id)
	      $CID=$CID[0]->id;
         if(!$user->id)
         {
             setcookie('suggest'.$CID,1);
         }
         $model1 = $this->getModel( 'log' );
         $post1['title']=$CID;
         if($user->id) $post1['description']=$user->name;
         else $post1['description']='Anonymous';
         $post1['description'].=' has '.$posted.' a suggestion at '.date(DATE_RFC822);
         $model1->store($post1);
         $post2['type']='suggest';
          $model = $this->getModel( 'security' );
         $model->store($post2);

      } else {
         $msg = JText::_( 'Error Saving Item' );
      }
      
      $link = 'index.php?option=com_suggestion&view=suggs';
      $this->setRedirect( $link, $msg );
   }   
}//class
