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

class SuggestionModelsugg extends JModel
{

   /**
    * Constructor
    *
    * @access  public
    * @return  void
    */
   function __construct()
   {
      parent::__construct();
   }//function
   function store($data)
   {  
      $row =& $this->getTable('sugg');

      if (!$row->bind($data)) {
         return false;
      }

      if (!$row->check()) {
         return false;
      }

      if (!$row->store()) {
         return false;
      }

      return true;
   }  
   
   function delete()
   {
      $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
      $row =& $this->getTable('sugg');
  
      if (count( $cids )) {
         foreach($cids as $cid) {
            if (!$row->delete( $cid )) {
               $this->setError( $row->getErrorMsg() );
               return false;
            }
         }
      }
      return true;
   }

   function getData()
   {
      $id = JRequest::getVar('cid');
      $row =& $this->getTable('sugg');
      $row->load($id[0]);
      return $row;
   }
}// class
