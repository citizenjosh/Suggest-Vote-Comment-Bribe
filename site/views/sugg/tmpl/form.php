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
JHTML::_('behavior.tooltip');
?>
<script type="text/javascript">
Joomla.submitbutton=function(action) {
          var form = document.adminForm;
  switch(action)
  {
  case 'save':case 'apply':   
   <?php
                 $editor =& JFactory::getEditor();
                 echo $editor->save( 'description' );
         ?>
  case 'publish':
  case 'unpublish':
  case 'cancel':
  default:
   Joomla.submitform( action );
  }
 } 
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<fieldset class="adminform"><legend><?php echo JText::_( 'Details' ); ?></legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="name"> <?php echo JText::_( 'Title' ); ?>:
		</label></td>
		<td colspan="2"><input class="text_area" type="text" name="title"
			id="title" size="32" maxlength="255"
			value="<?php echo $this->item->title;?>" /> <br>
		<?php echo JText::_( 'MAX_CHARACTERS' ); ?>: <?php echo $this->settings->max_title ?></td>
	</tr>
</table>
</fieldset>
<fieldset class="adminform"><legend><?php echo JText::_( 'Description' ); ?></legend>
<table class="admintable">
	<tr>
		<td valign="top" colspan="3">
		 <?php $editor =& JFactory::getEditor();
			echo $editor->display('description', $this->item->description, '550', '400', '60', '20', false);
		 ?>
		</td>
	</tr>
	<tr>
		<td>
		<?php echo JText::_( 'MAX_CHARACTERS' ); ?>: <?php echo $this->settings->max_desc ?></td>
		</td>
	</tr>
</table>
</fieldset>
<?php
if( $this->requiresCaptcha )
{
	echo "<fieldset>";
	echo JTEXT::_("COPYWHATYOUWROTE");
	require_once(JPATH_ROOT.DS.'components'.DS.'com_suggestvotecommentbribe'.DS.'recaptchalib.php');
	echo recaptcha_get_html($this->settings->pubk);
	echo "</fieldset>";
}
?>
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="UID" value="<?php echo $this->user_id; ?>" />
	<input type="hidden" name="option" value="com_suggestvotecommentbribe" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
	<input type="hidden" name="controller" value="sugg" />
	<input type="hidden" name="catid" value="<?php echo $this->settings->catid; ?>" />
	<a href="#" onclick="Joomla.submitbutton('save');return false;"><img src="<?php echo 'components/com_suggestvotecommentbribe/assets/images/icon-32-save.png' ?>" alt="<?php echo JTEXT::_("SAVE"); ?>"><br /></a>
</form>
