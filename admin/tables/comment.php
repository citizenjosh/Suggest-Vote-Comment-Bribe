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
 * Class for table suggestion_comment
 *
 */
class Tablecomment extends JTable
{
   var $db = null;
   
   /**
    * @var int
    * Primary key
    */
   var $id = 0;

   /**
    * @var varchar
    */
   var $title = NULL;

   /**
    * @var text
    */
   var $description = NULL;

   /**
    * @var int
    */
   var $published = 1;

   /**
    * @var int
    */
   var $UID= 0;

   /**
    * @var int
    */
   var $SID= 0;

   /**
    * Constructor
    *
    * @param object $_db Database connector object
    */
   function __construct( &$_db )
   {
      parent::__construct( '#__suggestion_comment', 'id', $_db );
      $this->db = $_db;
   }// function

}// class
