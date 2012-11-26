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

jimport( 'joomla.application.component.model' );

/**
 * Suggestion Model
 *
 * @package    Suggest Vote Comment Bribe
 * @subpackage Models
 */
class SuggestionsModelSuggestion extends JModel
{

   /**
    * Constructor that retrieves the ID from the request
    *
    * @access  public
    * @return  void
    */
   function __construct()
   {
      parent::__construct();

      $array = JRequest::getVar('cid',  0, '', 'array');
      $this->setId((int)$array[0]);
   }//function

   /**
    * Method to set the Suggestion identifier
    *
    * @access  public
    * @param   int Suggestion identifier
    * @return  void
    */
   function setId($id)
   {
      // Set id and wipe data
      $this->_id     = $id;
      $this->_data   = null;
   }//function


   /**
    * Method to get a record
    * @return object with data
    */
   function &getData()
   {
      // Load the data
      if (empty( $this->_data )) {
         $query = ' SELECT * FROM #__suggestvotecommentbribe '.
               '  WHERE id = '.$this->_id;
         $this->_db->setQuery( $query );
         $this->_data = $this->_db->loadObject();
      }
      if (!$this->_data) {
         $this->_data = new stdClass();
         $this->_data->id = 0;
         $this->_data->greeting = null;
      }
      return $this->_data;
   }//function

   /**
    * Method to store a record
    *
    * @access  public
    * @return  boolean  True on success
    */
   function store()
   {
      $row =& $this->getTable();

      $data = JRequest::get( 'post' );
      if($data[id]!=1)
         return false;
      // Bind the form fields to the hello table
      if (!$row->bind($data)) {
         $this->setError($this->_db->getErrorMsg());
         return false;
      }

      // Make sure the record is valid
      if (!$row->check()) {
         $this->setError($this->_db->getErrorMsg());
         return false;
      }

      // Store the table to the database
      if (!$row->store()) {
         $this->setError( $row->getErrorMsg() );
         return false;
      }

      return true;
   }//function

   /**
    * Method to delete record(s)
    *
    * @access  public
    * @return  boolean  True on success
    */
   function delete()
   {
      $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

      $row =& $this->getTable();

      if (count( $cids ))
      {
         foreach($cids as $cid) {
            if (!$row->delete( $cid )) {
               $this->setError( $row->getErrorMsg() );
               return false;
            }
         }//foreach
      }
      return true;
   }//function

}// class
