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
/*$db->setQuery('select*from #__suggestvotecommentbribe');
$settings=$db->loadObjectlist();
$settings=$settings[0];*/
$params = &JComponentHelper::getParams('com_suggestvotecommentbribe');
$settings = new stdClass();
$settings->URL 				= $params->get("URL","");
$settings->email 			= $params->get("email","");
$settings->pubk 			= $params->get("pubk","");
$settings->prvk 			= $params->get("prvk","");
$settings->max_title 		= $params->get("max_title","100");
$settings->max_desc 		= $params->get("max_desc","1000");

$settings->useraccess 		= $params->get("useraccess","");
$settings->recaptchatheme 	= $params->get("recaptchatheme","");
$settings->recaptchalng 	= $params->get("recaptchalng","");

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
			value="<?php $this->item->title=str_replace('"', "&quot;",$this->item->title);$this->item->title=str_replace('&nbsp;', " ",$this->item->title); $this->item->title=htmlspecialchars_decode($this->item->title,ENT_NOQUOTES); echo $this->item->title;?>" />
		<br>
		<?php echo JText::_( 'MAX_TITLE_LENGTH' ); ?>: <?php echo $settings->max_title ?></td>
	</tr>

	<tr>
		<td width="100" align="right" class="key"><label for="category"> <?php echo JText::_( 'Category' ); ?>:
		</label></td>
           	<td colspan="2"><select name="catid" id="catid" > <?php foreach($this->cats as $catId => $catTitle) { echo '<option value="'.$catId.'" '.($this->item->catid==$catId?"selected":"").' >'.$catTitle.'</option>'; } ?> </select></td>	
	</tr>

	<tr>
		<td valign="top" align="right" class="key"><?php echo JText::_( 'Published' ); ?>:
		</td>
		<td colspan="2"><fieldset id="jform_published" class="radio"> <?php echo $this->lists['published']; ?></fieldset></td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><?php echo JText::_( 'Open' ); ?>:
		</td>
		<td colspan="2"><fieldset id="jform_state" class="radio"><?php echo $this->lists['state']; ?></fieldset></td>
	</tr>
</table>
</fieldset>
<fieldset class="adminform"><legend><?php echo JText::_( 'Description' ); ?></legend>
<table class="admintable">
	<tr><td>
			<?php if(isset($_GET['ses']))
			{
				$description=$_SESSION[$_GET['ses']];
			}
			else
			{
				//$this->item->description=str_replace("<br />","",$this->item->description);
				//$this->item->description=str_replace("&nbsp;","&nbsp; ",$this->item->description);
				//$this->item->description=htmlspecialchars_decode($this->item->description,ENT_NOQUOTES);
				$this->item->description=html_entity_decode($this->item->description,ENT_NOQUOTES,'UTF-8');
				$description=stripslashes($this->item->description);
			}?>

		<?php $editor =& JFactory::getEditor();
                        echo $editor->display('description', $description, '550', '400', '60', '20', false);
                 ?>

		</td></tr>
	 <tr><td>
		<?php echo JText::_( 'MAX_DESCRIPTION_LENGTH' ); ?>: <?php echo $settings->max_desc ?></td>
	</td></tr>
</table>
</fieldset>
<?php
if($this->item->id)
{
	$user =& JFactory::getUser($this->item->UID);
}
else
{
	$user =& JFactory::getUser();
}
?> <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="noofVotes" value="<?php echo $this->item->noofVotes; ?>" />
<input type="hidden" name="amountDonated" value="<?php echo $this->item->amountDonated; ?>" />
<input type="hidden" name="UID" value="<?php echo $user->get('id'); ?>" />
<input type="hidden" name="option" value="com_suggestvotecommentbribe" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="sugg" />
</form>
<table width="100%">
	<tr>
		<td valign="middle" align="center"><?php JText::_('DONATE') ?>
		<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="bursar@Interpreneurial.com">
		<input type="hidden" name="item_name" value="com_suggestvotecommentbribe donation">
		<input type="hidden" name="currency_code" value="USD">
		<input type="image" style='border: 0;' src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!"></form>
		</td>
	</tr>
</table>
