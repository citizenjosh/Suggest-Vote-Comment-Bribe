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
$user =& JFactory::getUser();

JHTML::_('behavior.tooltip');
?>

<form action="index.php" method="post" name="Form" id="Form">
<fieldset class="adminform"><legend><?php echo JText::_( 'Details' ); ?></legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="name"> <?php echo JText::_( 'Title' ); ?>:
		</label></td>
		<td colspan="2"><input class="text_area" type="text" name="title"
			id="title" size="32" maxlength="255"
			value="<?php echo $this->item->title;?>" /> <br>
		Max <?php $settings->max_title ?> characters</td>
	</tr>
</table>
</fieldset>
<fieldset class="adminform"><legend><?php echo JText::_( 'Description' ); ?></legend>
<table class="admintable">
	<tr>
		<td valign="top" colspan="3"><textarea name=description cols=70
			rows=10><?php if($_GET['ses']){
				echo $_SESSION[$_GET['ses']];
			}
			else
			{
				echo $this->item->description;
			}?></textarea> <br>
		Max <?php $settings->max_desc ?> characters</td>
	</tr>
</table>
</fieldset>
			<?php
			if($this->item->id==0 && $settings->captcha && $user->id<1)
			{
				echo "<fieldset>";
				echo JTEXT::_("COPYWHATYOUWROTE");
				require_once(JPATH_ROOT.DS.'components'.DS.'com_suggestvotecommentbribe'.DS.'recaptchalib.php');
				echo recaptcha_get_html($settings->pubk);
				echo "</fieldset>";
			}
			?> <input type="hidden" name="cid[]"
	value="<?php echo $this->item->id; ?>" /> <input type="hidden"
	name="UID" value="<?php echo $user->get('id'); ?>" /> <input
	type="hidden" name="option" value="com_suggestvotecommentbribe" /> <input
	type="hidden" name="task" value="save" /> <input type="hidden"
	name="controller" value="sugg" /> <input type="image"
	src="<?php echo 'components'.DS.'com_suggestvotecommentbribe'.DS.'assets'.DS.'images'.DS ?>icon-32-save.png"
	alt="<?php echo JTEXT::_("SAVE"); ?>"><br />
			<?php echo JTEXT::_("SAVE"); ?></form>
