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

class SuggestionsControllercomment extends JController
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
      $this->_query = 'UPDATE #__suggestion_comment'
      . ' SET published = ' . (int) $this->publish
      . ' WHERE id IN ( '. $this->cids .' )'    
      ;
      return $this->_query;     
   }
   function edit()
   {
      JRequest::setVar( 'view', 'comment' );
      JRequest::setVar( 'layout', 'form'  );
      JRequest::setVar('hidemainmenu', 1);
      parent::display();
   }

   function cancel()
   {
      $msg = JText::_( 'Operation Cancelled' );
      $this->setRedirect( 'index.php?option=com_suggestion&view=comments', $msg );
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

      $query = $this->_buildQuery();
      $db = &JFactory::getDBO();
      $db->setQuery($query);
      if (!$db->query())
      {
         JError::raiseError(500, $db->getErrorMsg() );
      }
     $user =& JFactory::getUser();
     $model=$this->getModel( 'comment' );
     $comm=$model->getdata();
     $model1 = $this->getModel( 'log' );
     $post1['title']=$comm->SID;
    if($user->id)
        $post1['description']=$user->get('name');
     else   $post1['description']='Anonymous';
     $post1['description'].=' has ';
     $post1['description'].=$this->getTask().'ed';
     $post1['description'].=' a comment at '.date(DATE_RFC822);
     $model1->store($post1);
      for($i=0;$i<count($this->cid);$i++)
      {
         $db->setQuery('update #__suggestion_sugg set noofComs=(select count(*) from #__suggestion_comment where published=1 and SID='.$comm->SID.') where id='.$comm->SID);
         $db->query();
      }
      $link = 'index.php?option=com_suggestion&view=comments';
      $this->setRedirect($link, $msg);
   }

   function save()
   {
      $post = JRequest::get('post');
      $cid  = JRequest::getVar( 'cid', array(0), 'post', 'array' );
      #_ECR_SMAT_DESCRIPTION_CONTROLLER1_
      $post['id']    = (int) $cid[0];
      if($_POST['title']=='')
      {
          $t=time();
          $link = 'index.php?option=com_suggestion&controller=comment&task=edit&cid[]='.$post['id'].'&ses=s'.$t;
          $this->setRedirect( $link, 'title is required');
          $_SESSION['s'.$t]=$_POST['description'];
          return;
     }
      if(!$post['SID'])
      {
         $link = 'index.php?option=com_suggestion&view=comments';
          $this->setRedirect( $link, 'title is required');
          return;
     }

     $db = &JFactory::getDBO();
      $db->setQuery('select * from #__suggestion');
      $settings=$db->loadObjectlist();
      $post['title']=substr($_POST['title'], 0,$settings[0]->max_title);
      $post['description']=substr($_POST['description'], 0,$settings[0]->max_desc);
      $post['title']=(htmlentities($post['title'],ENT_NOQUOTES));
      $post['description']=((htmlentities($post['description'],ENT_NOQUOTES)));
      $post['title']=str_replace("\\\\", "\\", $post['title']);
      $post['title']=str_replace("\\\"", "\"", $post['title']);
      $post['title']=str_replace("\\'", "'", $post['title']);
      $post['description']=str_replace("\\\\", "\\", $post['description']);
      $post['description']=str_replace("\\\"", "\"", $post['description']);
      $post['description']=str_replace("\\'", "'", $post['description']);
      $post['title']=str_replace(" ", "&nbsp;", $post['title']);
      $post['description']=nl2br(str_replace(" ", "&nbsp;", $post['description']));
      $model = $this->getModel( 'comment' );
      if ($model->store($post)) {
         $msg = JText::_( 'Item Saved' );
         $model1 = $this->getModel( 'sugg' );
         $user =& JFactory::getUser();
         JRequest::setVar('cid',array($post['SID']));
         $sugg=$model1->getData();
         
         $model1 = $this->getModel( 'log' );
         $post1['title']=$post['SID'];
         if($user->id)
            $post1['description']=$user->get('name');
         else $post1['description']='Anonymous';
         $post1['description'].=' has modified a comment on '.$sugg->title.' at '.date(DATE_RFC822);
         $model1->store($post1);

      } else {
         $msg = JText::_( 'Error Saving Item' );
      }
      $db = &JFactory::getDBO();
      $db->setQuery('update #__suggestion_sugg set noofComs=(select count(*) from #__suggestion_comment where published=1 and SID='.$post['SID'].') where id='.$post['SID']);
      $db->query();
      $link = 'index.php?option=com_suggestion&view=comments';
      $this->setRedirect( $link, $msg );
   }

   function remove()
   {
      $model = $this->getModel('comment');
      $comm=$model->getData();
      if(!$model->delete()) {
         $msg = JText::_( 'Error Deleting Item' );
      } else {
         $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
         $model1 = $this->getModel( 'log' );
         $post1['title']=$comm->SID;
         $user =& JFactory::getUser();
         foreach($cids as $cid) {
             if($user->id)
                $post1['description']=$user->get('name');
             else   $post1['description']='Anonymous';
             $post1['description'].=' has removed a comment at '.date(DATE_RFC822);
             $model1->store($post1);
             $msg .= JText::_( 'Item Deleted '.' : '.$cid );
         }        
      }
      $db = &JFactory::getDBO();
      for($i=0;$i<count($cids);$i++)
      {
         $db->setQuery('update #__suggestion_sugg set noofComs=(select count(*) from #__suggestion_comment where published=1 and SID='.$comm->SID.') where id='.$comm->SID);
         $db->query();
      }
      $this->setRedirect( 'index.php?option=com_suggestion&view=comments', $msg );
   }

   
}
