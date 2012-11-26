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

?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
<fieldset class="adminform"><legend><?php echo JText::_( 'Details' ); ?></legend>

<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="URL"> <?php echo JText::_( 'URL' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="URL" id="URL" size="32"
			maxlength="250" value="<?php echo $this->Suggestion->URL;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="URL"> <?php echo JText::_( 'PAYPAL_EMAIL' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="email" id="email"
			size="32" maxlength="250"
			value="<?php echo $this->Suggestion->email;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="URL"> <?php echo JText::_( 'RECAPTCHA_PUBLIC_KEY' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="pubk" id="pubk"
			size="32" maxlength="250"
			value="<?php echo $this->Suggestion->pubk;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="URL"> <?php echo JText::_( 'RECAPTCHA_PRIVATE_KEY' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="prvk" id="prvk"
			size="32" maxlength="250"
			value="<?php echo $this->Suggestion->prvk;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="URL"> <?php echo JText::_( 'MAX_TITLE_LENGTH' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="max_title"
			id="max_title" size="32" maxlength="250"
			value="<?php echo $this->Suggestion->max_title;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="URL"> <?php echo JText::_( 'MAX_DESCRIPTION_LENGTH' ); ?>:
		</label></td>
		<td><input class="text_area" type="text" name="max_desc" id="max_desc"
			size="32" maxlength="250"
			value="<?php echo $this->Suggestion->max_desc;?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="login"> <?php echo JText::_( 'REQUIRE_LOGIN' ); ?>:
		</label></td>
		<td><?php echo $this->lists['login']; ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="show"> <?php echo JText::_( 'REQUIRE_CAPTCHA_FOR_GUESTS' ); ?>:
		</label></td>
		<td><?php echo $this->lists['captcha']; ?></td>
	</tr>
</table>
</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_suggestvotecommentbribe" />
<input type="hidden" name="id"
	value="<?php echo $this->Suggestion->id; ?>" /> <input type="hidden"
	name="task" value="" /> <input type="hidden" name="controller"
	value="suggestion" /></form>
