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

<form
	action="index.php?option=com_suggestvotecommentbribe&view=suggestions"
	method="post" name="adminForm">
<div id="editcell">
<table class="adminlist">
	<thead>
		<tr>
			<th width="20"><input type="checkbox" id="toggle" name="toggle"
				value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th><?php echo JText::_( 'REQUIRE_LOGIN' ); ?></th>
			<th><?php echo JText::_( 'REQUIRE_CAPTCHA' ); ?></th>
			<th><?php echo JText::_( 'PAYPAL_EMAIL' ); ?></th>
			<th><?php echo JText::_( 'THANK_YOU_URL' ); ?></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked    = JHTML::_('grid.id',   $i, $row->id );
		$link       = JRoute::_( 'index.php?option=com_suggestvotecommentbribe&controller=suggestion&task=edit&cid[]='. $row->id );
		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $checked; ?></td>
		<td><a
			href="index.php?option=com_suggestvotecommentbribe&controller=suggestion&task=login"><?php echo $row->login?'yes':'No'; ?></a>
		</td>
		<td><a
			href="index.php?option=com_suggestvotecommentbribe&controller=suggestion&task=captcha"><?php echo $row->captcha?'yes':'No'; ?></a>
		</td>
		<td><a href="<?php echo $link; ?>"><?php echo $row->email; ?></a></td>
		<td><a href="<?php echo $link; ?>"><?php echo $row->URL; ?></a></td>
	</tr>
	<?php
	$k = 1 - $k;
	}
	?>
	<tfoot>
		<tr>
			<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
</table>
</div>
<input type="hidden" name="option" value="com_suggestvotecommentbribe" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="suggestion" /></form>
<script language="javascript">
$('toggle').checked=true;
checkAll(<?php echo count( $this->items ); ?>);
</script>
