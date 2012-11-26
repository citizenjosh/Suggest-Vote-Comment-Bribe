<?php
/**
 * @version $Id$
 * @package    Suggestion
 * @subpackage Tables
 * @copyright Copyright (C) 2009 Interpreneurial LLC. All rights reserved.
 * @license GNU/GPL 
*/

//--No direct access
defined('_JEXEC') or die('=;)');

/**
 * Class for table suggestion_bribe
 *
 */
class Tablebribe extends JTable
{
   var $db = null;
   
   /**
    * @var int
    * Primary key
    */
   var $id = 0;

   /**
    * @var int
    */
   var $SID = 0;

   /**
    * @var int
    */
   var $amount= 0;

   /**
    * @var int
    */
   var $published = 0;


   /**
    * Constructor
    *
    * @param object $_db Database connector object
    */
   function __construct( &$_db )
   {
      parent::__construct( '#__suggestion_bribe', 'id', $_db );
      $this->db = $_db;
   }// function

}// class
