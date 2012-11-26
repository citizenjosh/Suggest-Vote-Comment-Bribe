<?php
/**
 * @version $Id$
 * @package    Suggestion
 * @subpackage Models
 * @copyright Copyright (C) 2009 Interpreneurial LLC. All rights reserved.
 * @license GNU/GPL 
*/

//--No direct access
defined('_JEXEC') or die('=;)');

jimport('joomla.application.component.model');

class SuggestionModelsecurity extends JModel
{
   function __construct()
   {
      parent::__construct();
   }

   function store($data)
   {  
     $db = &JFactory::getDBO();
     $user =& JFactory::getUser();
     if($data['type']=='vote')
      {
      $type='vote'.$data['VID'];
        setcookie($type,1);
      }
      else
       $type=$data['type'];
      if($user->id)
      {
         $db->setQuery('select count(*) as c from #__suggestion_security where UID='.$user->id.' and action="'.$type.'"');
      }
      else
      {
         $db->setQuery('select count(*) as c from #__suggestion_security where IP="'.$_SERVER [ 'REMOTE_ADDR' ].'" and action="'.$type.'"');
      }
      $exists=$db->loadObjectlist();
      if($user->id)
      {
         if($exists[0]->c)
            $db->setQuery('update #__suggestion_security set `time`=now() where UID='.$user->id.' and action="'.$type.'"');
         else
            $db->setQuery('insert #__suggestion_security set `time`=now() ,UID='.$user->id.' ,action="'.$type.'"');   
      }
      else
      {
         if($exists[0]->c)
            $db->setQuery('update #__suggestion_security set `time`=now() where IP="'.$_SERVER [ 'REMOTE_ADDR' ].'" and action="'.$type.'"');
         else
            $db->setQuery('insert #__suggestion_security set `time`=now() ,IP="'.$_SERVER [ 'REMOTE_ADDR' ].'" ,action="'.$type.'"');
      }
      $db->query();
      return true;
   }  
   
   function canVote($VID,$SID)
   {
       $db = &JFactory::getDBO();
       $user =& JFactory::getUser();
       $db->setQuery('select * from #__suggestion');
       $settings=$db->loadObjectlist();
       $settings=$settings[0];
       if(is_array($SID)||$SID[0]!=0||$SID!=0)
       {
            if(is_array($SID))
                $db->setQuery("select * from #__suggestion_sugg where id=$SID[0]");
            else
                $db->setQuery("select * from #__suggestion_sugg where id=$SID");
            $sugg=$db->loadObjectlist();
            if($sugg[0]->state==0)
            return JText::_('CANTVOTE');
       }
       else
       {
           return JText::_("INVSUGG");
       }
       if((is_array($VID)&&$VID[0]!=0)||($VID!=0&&!is_array($VID)))
       {
           if($_COOKIE['vote'.$SID[0]])
           {
               $db->setQuery('select 1 as c');
           }
           else
           {
             if($user->id)
             {
                $db->setQuery('select count(*) as c from #__suggestion_vote where UID="'.$user->id.'"  and id='.$VID);
             }
          }
          $can=$db->loadObjectlist();
          if($can[0]->c!=1)
          {
             return JText::_("cantremvote");
          }
      }
      else
      {
            if($settings->login)
            {
            if(!$user->id)
            {
               return JText::_('needtologinvote');
            }
         }
         if($user->id)
         {
            $db->setQuery('select count(*) as c from #__suggestion_vote where UID="'.$user->id.'"  and SID='.$SID[0]);
                $can=$db->loadObjectlist();
                if($can[0]->c==1)
                {
                    return JText::_('alreadyvoted');
                }
         }
         else
         {
            $db->setQuery('select count(*) as c from #__suggestion_security where IP="'.$_SERVER [ 'REMOTE_ADDR' ].'"  and action="vote'.$SID[0].'"');
         }
         $can=$db->loadObjectlist();
         if($can[0]->c==1)
         {
                return JText::_('alreadyvoted');
         }
      }
         return true;      
   }
   function canComment($CID,$SID)
   {
         $db = &JFactory::getDBO();
       $user =& JFactory::getUser();
         $db->setQuery('select * from #__suggestion');
         $settings=$db->loadObjectlist();
      $settings=$settings[0];
        if(is_array($SID)||$SID[0]!=0||$SID!=0)
        {
            if(is_array($SID))
                $db->setQuery("select * from #__suggestion_sugg where id=$SID[0]");
            else
                $db->setQuery("select * from #__suggestion_sugg where id=$SID");
            $sugg=$db->loadObjectlist();
            if($sugg[0]->state==0)
                return JText::_('CANTCOMMENT');
        }
        else
        {
            return JText::_("INVSUGG");
        }
         if(!is_array($CID)||$CID[0]==0)
         {
            if($settings->login)
            {
            if(!$user->id)
            {
               return JText::_('needtologincomm');
            }
         }
         if($user->id)
         {
            $db->setQuery('select count(*) as c from #__suggestion_security where UID='.$user->id.' and `time`>now()-interval 3 second and action="comment"');
            
         }
         else
         {
            $db->setQuery('select count(*) as c from #__suggestion_security where IP="'.$_SERVER [ 'REMOTE_ADDR' ].'" and `time`>now()-interval 3 second and action="comment"');
         }
         $can=$db->loadObjectlist();
         if($can[0]->c==1)
         {
            return JText::_('COMMENTWAIT');
         }

      }
      else
      {
         return JText::_('COMMENTMODIFY');
      }
         return true;
   }
   function canSuggest($CID)
   {
         $db = &JFactory::getDBO();
       $user =& JFactory::getUser();
         $db->setQuery('select * from #__suggestion');
         $settings=$db->loadObjectlist();
      $settings=$settings[0];
         if(!is_array($CID)||$CID[0]==0)
         {
            if($settings->login)
            {
            if(!$user->id)
            {
               return JText::_('needtologinsugg');
            }
         }
         if($user->id)
         {
            $db->setQuery('select count(*) as c from #__suggestion_security where UID='.$user->id.' and `time`>now()-interval 3 second and action="suggest"');
            
         }
         else
         {
            $db->setQuery('select count(*) as c from #__suggestion_security where IP="'.$_SERVER [ 'REMOTE_ADDR' ].'" and `time`>now()-interval 1 minute and action="suggest"');
         }
         $can=$db->loadObjectlist();
         if($can[0]->c==1)
         {
            return JText::_('SUGGWAIT');
         }

      }
      else
      {
         return JText::_('SUGGMODIFY');
      }
         return true;   
   }
   function canBribe($SID)
   {
         $db = &JFactory::getDBO();
       $user =& JFactory::getUser();
         $db->setQuery('select * from #__suggestion');
         $settings=$db->loadObjectlist();
      $settings=$settings[0];
        if(is_array($SID)||$SID[0]!=0||$SID!=0)
        {
            if(is_array($SID))
                $db->setQuery("select * from #__suggestion_sugg where id=$SID[0]");
            else
                $db->setQuery("select * from #__suggestion_sugg where id=$SID");
            $sugg=$db->loadObjectlist();
            if($sugg[0]->state==0)
                return JText::_('CANTBRIBE');
        }
        else
        {
            return JText::_("INVSUGG");
        }
         return true;
   }
}
