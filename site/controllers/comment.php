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

class SuggestionControllercomment extends JController
{
   var $cid;

   function __construct()
   {
      parent::__construct();
      // Register Extra tasks
      $this->registerTask( 'add'  ,    'edit' );
      $this->registerTask( 'unpublish',   'publish');

      $this->cid = JRequest::getVar( 'cid', array(0), '', 'array' );
      JArrayHelper::toInteger($this->cid, array(0));
   }
   function _buildQuery()
   {
      $user=JFactory::getUser();
      $this->_query = 'UPDATE #__suggestion_comment'
      . ' SET published = ' . (int) $this->publish
      . ' WHERE id IN ( '. $this->cids .')  '    
      ;
      return $this->_query;     
   }
   function edit()
   {
      $model = $this->getModel( 'security' );
      $can=$model->canComment(JRequest::getVar('cid'),JRequest::getVar('SID'));
     if($can!==true)
      {
          $this->setRedirect( 'index.php?option=com_suggestion&view=suggs', $can );
       return;
     }
      JRequest::setVar( 'view', 'comment' );
      JRequest::setVar( 'layout', 'form'  );
      parent::display();
   }

   function publish()
   {
      $cid     = JRequest::getVar( 'cid', array(), '', 'array' );
      $sid     = JRequest::getVar( 'SID' );
      $this->publish = ( $this->getTask() == 'publish' ? 1 : 0 );

      JArrayHelper::toInteger($cid);
      if (count( $cid ) < 1)
      {
         $action = $publish ? 'publish' : 'unpublish';
         JError::raiseError(500, JText::_( 'Select an item to' .$action, true ) );
      }
      $model = $this->getModel( 'security' );
      $can=$model->canComment(JRequest::getVar('cid'),JRequest::getVar('SID'));
     if($can!==true&&!$_COOKIE['comment'.$cid[0]])
      {
          $this->setRedirect( 'index.php?option=com_suggestion&view=suggs', $can );
       return;
     }
      $this->cids = implode( ',', $cid );

      $query = $this->_buildQuery();
      $db = &JFactory::getDBO();
      $db->setQuery($query);
      if (!$db->query())
      {
         JError::raiseError(500, $db->getErrorMsg() );
      }
      for($i=0;$i<count($this->cid);$i++)
      {
         $db->setQuery('update #__suggestion_sugg set noofComs=(select count(*) from #__suggestion_comment where published=1 and SID=(select SID from #__suggestion_comment where id='.$this->cid[$i].')) where id=(select SID from #__suggestion_comment where id='.$this->cid[$i].')');
         $db->query();
      }
       $user =& JFactory::getUser();
       $model1 = $this->getModel( 'log' );
     $post1['title']=$sid;
     if($user->id)
        $post1['description']=$user->get('name');
     else   $post1['description']='Anonymous';
     $post1['description'].=' has ';
     $post1['description'].=$this->getTask().'ed';
     $post1['description'].=' a comment at '.date(DATE_RFC822);
     $model1->store($post1);

      $link = 'index.php?option=com_suggestion&view=sugg&cid[0]='.$sid;
      $this->setRedirect($link, '');
   }

   function save()
   {
      $post = JRequest::get('post');
      $cid  = JRequest::getVar( 'cid', array(0), 'post', 'array' );
      #_ECR_SMAT_DESCRIPTION_CONTROLLER1_
      $post['id']    = (int) $cid[0];
      $model_sec = $this->getModel( 'security' );
      $user =JFactory::getUser();
      $can=$model_sec->canComment(JRequest::getVar('cid'),JRequest::getVar('SID'));
     if($can!==true)
      {
          $this->setRedirect( 'index.php?option=com_suggestion&view=suggs', $can );
       return;
     }
     $db = &JFactory::getDBO();
      $db->setQuery('select * from #__suggestion');
      $capcha=$db->loadObjectlist();
      if($capcha[0]->capcha&&!$user->id)
      {
         include(JPATH_ROOT."/components/com_suggestion/recaptchalib.php");
         $resp = recaptcha_check_answer ($capcha[0]->prvk,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

         if (!$resp->is_valid) 
         {
            $link = 'index.php?option=com_suggestion&view=sugg&cid[0]='.$post['SID'];
            $this->setRedirect( $link, 'You entered a wrong CAPTCHA');
            return;
         }
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
      $post['UID']=$user->id;
      if($post['title']=='')
      {
          $t=time();
         $link = 'index.php?option=com_suggestion&controller=comment&task=edit&SID='.$post['SID'].'&ses=s'.$t;
          $this->setRedirect( $link, 'title is required');
          $_SESSION['s'.$t]=$_POST['description'];
          return;
     }
      $model = $this->getModel( 'comment' );
      $can=$model_sec->canComment(JRequest::getVar('cid'),JRequest::getVar('SID'));
     if($can!==true)
      {
          $this->setRedirect( 'index.php?option=com_suggestion&view=suggs', $can );
       return;
     }
      if ($model->store($post)) {
         $msg = JText::_( 'Item Saved' );
         if(!$user->id)
         {
            $post['title']=htmlentities($post['title'],ENT_NOQUOTES);
            $post['description']=nl2br(htmlentities($post['description'],ENT_NOQUOTES));
            $db->setQuery("select max(id) as id from #__suggestion_comment where SID=$post[SID]");
            $CID=$db->loadObjectlist();
            setcookie('comment'.$CID[0]->id,1);
         }
         $model1 = $this->getModel( 'sugg' );
         JRequest::setVar('cid',array($post['SID']));
         $sugg=$model1->getData();
         
         $model1 = $this->getModel( 'log' );
         $post1['title']=$post['SID'];
         if($user->id)$post1['description']=$user->name;
         else $post1['description']='Anonymous';
         $post1['description'].=' has commented on '.$sugg->title.' at '.date(DATE_RFC822);
         $model1->store($post1);
         $post2['type']='comment';
          $model = $this->getModel( 'security' );
         $model->store($post2);
      } else {
         $msg = JText::_( 'Error Saving Item' );
      }
      $db->setQuery('update #__suggestion_sugg set noofComs=(select count(*) from #__suggestion_comment where published=1 and SID='.$post['SID'].') where id='.$post['SID']);
      $db->query();

      $link = 'index.php?option=com_suggestion&view=sugg&cid[0]='.$post['SID'];
      $this->setRedirect( $link, $msg );
   }

   
}
