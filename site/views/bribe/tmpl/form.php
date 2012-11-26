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
$us=JFactory::getUser();
$URL=$settings->URL;
if(strpos($URL,'http')===false)
	$URL=JURI::root().$URL;
JHTML::_('behavior.tooltip');
?>


<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">

   <table class="admintable">
      <tr>
         <td width="100" align="right" class="key">
            <label for="name">
               <?php echo JText::_( 'BRIBEAMOUNT' ); ?>:
            </label>
         </td>
         <td colspan="2">
            <input type="text" name="amount" value="25.00">
         </td>
      </tr>
</table>
<b><?php echo JText::_( 'BRIBETEXT' ); ?></b><br>
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $settings->email;?>">
<input type="hidden" name="item_name" value="Donation">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="return" value="<?php echo $URL;?>">
<INPUT TYPE="hidden" NAME="notify_url" value="<?php echo JURI::root();?>index.php?option=com_suggestvotecommentbribe&controller=bribe&task=save&SID=<?php echo $_POST['SID'];?>&UID=<?php echo $us->id;?>">
<input type="image" src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form>
