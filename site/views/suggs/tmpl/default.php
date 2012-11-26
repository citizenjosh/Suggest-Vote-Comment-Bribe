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

$db = &JFactory::getDBO();
$db->setQuery('select*from #__suggestvotecommentbribe');
$settings=$db->loadObjectlist();
$settings=$settings[0];
$ordering = ($this->lists['order'] == 'ordering');

?>
<?php	// handle parameters
$params = &JComponentHelper::getParams('com_suggestvotecommentbribe');
$columnstoshow = $params->get( 'columnstoshow' );
?>


<script
	type="text/javascript" src="includes/js/joomla.javascript.js"></script>

<form action="index.php?option=com_suggestvotecommentbribe&view=suggs"
	method="post" name="adminForm">
<div id="tablecell">
<table class="adminlist" cellpadding="5px">
	<thead>
		<tr>
		<?php
		if( is_array($columnstoshow) && in_array('showId', $columnstoshow) ){
		?>
			<th nowrap="nowrap" style="text-align: left;"><?php echo JHTML::_('grid.sort',   JText::_('ID'), 'id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		<?php } ?>
		<?php
		if( is_array($columnstoshow) && in_array('showTitle', $columnstoshow) ){
		?>
			<th style="text-align: left;"><?php echo JHTML::_('grid.sort',   JText::_('TITLE'), 'title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		<?php } ?>
		<?php
		if( is_array($columnstoshow) && in_array('showVotes', $columnstoshow) ){
		?>
			<th style="text-align: left;"><?php echo JHTML::_('grid.sort', JText::_('SUGGNOOFVOTES'), 'noofVotes', $this->lists['order_Dir'], $this->lists['order']);?></th>
		<?php } ?>
		<?php
		if( is_array($columnstoshow) && in_array('showComments', $columnstoshow) ){
		?>
			<th style="text-align: left;"><?php echo JHTML::_('grid.sort', JText::_('SUGGNOOFCOMMENTS'), 'noofComs', $this->lists['order_Dir'], $this->lists['order']);?></th>
		<?php } ?>
		<?php
		if( is_array($columnstoshow) && in_array('showBribes', $columnstoshow) ){
		?>
			<th style="text-align: left;"><?php echo JHTML::_('grid.sort', JText::_('SUGGAMOUNTBRIBED'), 'amountDonated', $this->lists['order_Dir'], $this->lists['order']);?></th>
		<?php } ?>
		<?php
		if( $settings->show && is_array($columnstoshow) && in_array('showAuthor', $columnstoshow) ){
		?>
			<th style="text-align: left;"><?php echo JHTML::_('grid.sort', JText::_('AUTHOR'), 'UID', $this->lists['order_Dir'], $this->lists['order']);?></th>
		<?php } ?>
		<?php
		if( is_array($columnstoshow) && in_array('showState', $columnstoshow) ){
		?>
			<th style="text-align: left;"><?php echo JHTML::_('grid.sort', JText::_('SUGGSTATE'), 'state', $this->lists['order_Dir'], $this->lists['order']);?></th>
		<?php } ?>
		</tr>
	</thead>

	<tbody>
	<?php
	$k = 0;
	$user = JFactory::getUser();
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		if (isset($_COOKIE['suggest'.$row->id])) {
			if($_COOKIE['suggest'.$row->id]!=1)
			{
				if(($user->id==0||$user->id!=$row->UID)&&$row->published==0)
				continue;
			}
		}
		$link = JRoute::_( 'index.php?option=com_suggestvotecommentbribe&view=sugg&cid[]='. $row->id.'&Itemid='.$this->Itemid);

		$checked = JHTML::_('grid.id',  $i, $row->id );
	?>
		
		
		
		
		
		<tr class="<?php echo "row$k"; ?>">
		<?php
		if( is_array($columnstoshow) && in_array('showId', $columnstoshow) ){
		?>
			<td align="center"><?php echo $row->id; ?></td>
		<?php } ?>
		<?php
		if( is_array($columnstoshow) && in_array('showTitle', $columnstoshow) ){
		?>
			<td><a href="<?php echo $link  ?>"> <?php $row->title=html_entity_decode($row->title,ENT_NOQUOTES); if(strlen($row->title)>20) $row->title=substr($row->title, 0,20).'...'; echo htmlentities($row->title,ENT_NOQUOTES); ?></a></td>
		<?php } ?>
		<?php
		if( is_array($columnstoshow) && in_array('showVotes', $columnstoshow) ){
		?>
			<td align="center"><?php echo $row->noofVotes;?></td>
		<?php } ?>
		<?php
		if( is_array($columnstoshow) && in_array('showComments', $columnstoshow) ){
		?>
			<td align="center"><?php echo $row->noofComs;?></td>
		<?php } ?>
		<?php
		if( is_array($columnstoshow) && in_array('showBribes', $columnstoshow) ){
		?>
			<td align="center"><?php echo $row->amountDonated;?></td>
		<?php } ?>
		<?php
		if( $settings->show && is_array($columnstoshow) && in_array('showAuthor', $columnstoshow) ){
		?>
			<td align="center">
				<?php
				if($row->UID)
				{
					$user2 =& JFactory::getUser($row->UID);
					echo $user2->get('name');
				}
				else
				{
					echo JText::_('ANONYMOUS');
				}
			?></td>
		<?php
		}
		?>
		<?php
		if( is_array($columnstoshow) && in_array('showState', $columnstoshow) ){
		?>
			<td><?php echo $row->state?'open':'closed'; ?></td>
		<?php
		}
		?>
		</tr>
	<?php
	$k = 1 - $k;
	}
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="13"><a
				href='<?php echo JRoute::_( 'index.php?option=com_suggestvotecommentbribe&controller=sugg&task=edit'.'&Itemid='.$this->Itemid); ?>'><img
				src="<?php echo 'components'.DS.'com_suggestvotecommentbribe'.DS.'assets'.DS.'images'.DS ?>icon-32-article-add.png"
				alt="<?php echo JText::_('SUGGADDNEW');?>"><br />
				<?php echo JText::_('SUGGADDNEW');?></a></td>
		</tr>
		<tr>
			<td colspan="13"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
</table>
</div>
<input type="hidden" name="option" value="com_suggestvotecommentbribe" />
<input type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="filter_order"
	value="<?php echo $this->lists['order']; ?>" /> <input type="hidden"
	name="filter_order_Dir"
	value="<?php echo $this->lists['order_Dir']; ?>" /> <input
	type="hidden" name="controller" value="sugg" /> <input type="hidden"
	name="view" value="suggs" /> <?php echo JHTML::_( 'form.token' ); ?></form>
