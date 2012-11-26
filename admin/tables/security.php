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
 * Class for table suggestion_sugg
 *
 */
class Tablesecurity extends JTable
{
   var $db = null;
   
   /**
    * @var int
    * Primary key
    */
   var $UID = 0;

   /**
    * @var varchar
    */
   var $IP = NULL;

   /**
    * @var timestamp
    */
   var $time = NULL;

   /**
    * @var varchar
    */
   var $action = NULL;


   /**
    * Constructor
    *
    * @param object $_db Database connector object
    */
   function __construct( &$_db )
   {
      parent::__construct( '#__suggestion_security', 'time', $_db );
      $this->db = $_db;
   }// function

}// class
