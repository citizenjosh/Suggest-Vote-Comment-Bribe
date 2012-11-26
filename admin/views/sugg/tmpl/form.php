<?php
/**
 * @version $Id$
 * @package    Suggest Vote Comment Bribe
 * @subpackage Views
 * @copyright Copyright (C) 2010 Interpreneurial LLC. All rights reserved.
 * @license GNU/GPL
*/

//--No direct access
defined('_JEXEC') or die('=;)');
$db = &JFactory::getDBO();
$db->setQuery('select*from #__suggestvotecommentbribe');
$settings=$db->loadObjectlist();
$settings=$settings[0];

JHTML::_('behavior.tooltip');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
   <fieldset class="adminform">
   <legend><?php echo JText::_( 'Details' ); ?></legend>
   <table class="admintable">
      <tr>
         <td width="100" align="right" class="key">
            <label for="name">
               <?php echo JText::_( 'Title' ); ?>:
            </label>
         </td>
         <td colspan="2">
             <input class="text_area" type="text" name="title" id="title" size="32" maxlength="255" value="<?php $this->item->title=str_replace('"', "&quot;",$this->item->title);$this->item->title=str_replace('&nbsp;', " ",$this->item->title); $this->item->title=htmlspecialchars_decode($this->item->title,ENT_NOQUOTES); echo $this->item->title;?>" />
            <br>Max <?=$settings->max_title?> characters
        </td>
      </tr>
            <tr>
         <td valign="top" align="right" class="key">
            <?php echo JText::_( 'Published' ); ?>:
         </td>
         <td colspan="2">
            <?php echo $this->lists['published']; ?>
         </td>
      </tr>
      </tr>
            <tr>
         <td valign="top" align="right" class="key">
            <?php echo JText::_( 'Open' ); ?>:
         </td>
         <td colspan="2">
            <?php echo $this->lists['state']; ?>
         </td>
      </tr>
   </table>
   </fieldset>
      <fieldset class="adminform">
      <legend><?php echo JText::_( 'Description' ); ?></legend>
      <table class="admintable">
         <tr>
         <td valign="top" colspan="3">
         <textarea name=description cols=70 rows=10><?php if($_GET['ses']) echo $_SESSION[$_GET['ses']]; else{ $this->item->description=str_replace("<br />","",$this->item->description);$this->item->description=str_replace("&nbsp;"," ",$this->item->description); $this->item->description=htmlspecialchars_decode($this->item->description,ENT_NOQUOTES); echo $this->item->description;}?></textarea>
         <br>Max <?=$settings->max_desc?> characters
         <?php
//            echo $this->editor->display( 'description', $this->item->description, '550', '300', '60', '20', array('pagebreak', 'readmore') ) ;
         ?>
         </td>
         </tr>
      </table>
   </fieldset>
<?php
   if($this->item->id) $user =& JFactory::getUser($this->item->UID);
   else $user =& JFactory::getUser();
?>
   <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
   <input type="hidden" name="noofVotes" value="<?php echo $this->item->noofVotes; ?>" />
   <input type="hidden" name="amountDonated" value="<?php echo $this->item->amountDonated; ?>" />
   <input type="hidden" name="UID" value="<?php echo $user->get('id'); ?>" />
   <input type="hidden" name="option" value="com_suggestvotecommentbribe" />
   <input type="hidden" name="task" value="" />
   <input type="hidden" name="controller" value="sugg" />
</form>
      <table width="100%">
<tr>
      <td valign="middle" align="center">
      <?=JText::_('DONATE')?>
      <form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="bursar@Interpreneurial.com">
<input type="hidden" name="item_name" value="com_suggestvotecommentbribe donation">
<input type="hidden" name="currency_code" value="USD">
<input type="image" style='border:0;' src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form>
      </td>
    </tr>
</table>
