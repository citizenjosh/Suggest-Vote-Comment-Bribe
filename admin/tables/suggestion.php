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


/**
 * Suggestion Table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class TableSuggestion extends JTable
{
   /**
    * Primary Key
    *
    * @var int
    */
   var $id = null;

   /**
    * @var string
    */
   var $URL = null;

   /**
    * @var int
    */
   var $login= 0;

   /**
    * @var int
    */
   var $show= 0;

   /**
    * @var int
    */
   var $capcha= 0;

   /**
    * @var string
    */
   var $email = null;

   /**
    * @var string
    */
   var $pubk = null;

   /**
    * @var string
    */
   var $prvk = null;

   /**
    * @var int
    */
   var $max_title = 0;

   /**
    * @var int
    */
   var $max_desc = 0;

   
   /**
    * Constructor
    *
    * @param object Database connector object
    */
   function TableSuggestion(& $db) {
      parent::__construct('#__suggestion', 'id', $db);
   }
}
?>
