<?php
/**
 * @version $Id$
 * @package    Suggestion
 * @subpackage Views
 * @copyright Copyright (C) 2009 Interpreneurial LLC. All rights reserved.
 * @license GNU/GPL 
*/
//--No direct access
defined('_JEXEC') or die('=;)');
function force_sp($string,$charcount)
{	$count=0;
	$spl=preg_split('[\s]',$string);
	foreach($spl as $str)
	{
		if(strlen($str)>$charcount)
		{
			$str1=substr($string,0,$count+$charcount);
			$str2=substr($string,$count+$charcount);
			$string=$str1.' '.$str2;
//			$count++;
			$string=force_sp($string,$charcount);
			break;
		}
		$count+=strlen($str)+1;
	}
	return $string;
}

$thisuser =JFactory::getUser();

if($this->settings->show)
{
   if($this->item->UID==0)
   {
      $username='Anonymous';
   }
   else
   {
      $user =JFactory::getUser($this->item->UID);
      $username=$user->name;
   }
}
?>
<div style="width:100%;">
    <div style="color:#666666; font-family:Helvetica,Arial,sans-serif; font-size:1.3em; font-weight:bold; text-align:left; width:100%;"><?php echo force_sp(str_replace('&nbsp;','&nbsp; ',$this->item->title),30); ?></div>
    <br><?php echo $this->settings->show?' '.JText::_('AUTHOR').' '.$username:''; ?>
<p><?php echo force_sp(str_replace('&nbsp;','&nbsp; ',$this->item->description),50); ?></p>
</div>
<p><table>
<tr><td><?php echo JText::_('SUGGAMOUNTBRIBED')?>:</td><td><?php echo $this->item->amountDonated; ?></td>
<td>
<?php echo $this->item->state&&$this->item->published?"<form name=bribe method=post><input type=hidden name=SID value=".$this->item->id.">
<input type=hidden name=option value=com_suggestion><input type=hidden name=controller value=bribe>
 <input type=hidden name=task value=edit><a href='javascript:void(0)' onclick='bribe.submit()'>".JText::_('leavebribe')."</a></form>":'';?></td></tr>
<tr><td><?php echo JText::_('SUGGNOOFCOMMENTS')?>:</td><td><?php echo $this->item->noofComs; ?></td>
<td><?php echo $this->item->state&&$this->item->published?"<form name=comment method=post><input type=hidden name=cid value=0><input type=hidden name=SID value=".$this->item->id.">
<input type=hidden name=option value=com_suggestion><input type=hidden name=controller value=comment>
 <input type=hidden name=task value=edit><a href='javascript:void(0)' onclick='comment.submit()'>".JText::_('leavecomment')."</a></form>":'';?></td></tr>
<tr><td><?php echo JText::_('SUGGNOOFVOTES')?>:</td><td><?php echo $this->item->noofVotes; ?></td>
<td><?php if($this->item->state&&$this->item->published) {
for($i=0;$i<count($this->votes);$i++)
{
   $vote=$this->votes[$i];
   if($vote->UID&&$vote->UID==$thisuser->id||$_COOKIE['vote'.$vote->SID])
   {
      $del= "<form name=vote".$vote->id." method=post><input type=hidden name=cid value=".$vote->id.">
<input type=hidden name=option value=com_suggestion><input type=hidden name=controller value=vote><input type=hidden name=SID value=".$this->item->id.">
 <input type=hidden name=task value=remove><a href='javascript:void(0)' onclick='vote".$vote->id.".submit()'>".JText::_('removevote')."</a></form>";
            break;
   }
   elseif($thisuser->id||(!$this->settings->capcha&&!$this->settings->login))
   {
   $del= '<form name=vote method=post>
   <input type="hidden" name="value" value="1" />
   <input type="hidden" name="option" value="com_suggestion" />
   <input type="hidden" name="task" value="save" />
   <input type="hidden" name="SID" value="'.$this->item->id.'" />
   <input type="hidden" name="controller" value="vote" />
   <input type=hidden name=cid value=0>
<a href="javascript:void(0)" onclick="vote.submit()">'.JText::_('leavevote')."</a></form>";
   }
   else $del= "<form name=vote method=post><input type=hidden name=SID value=".$this->item->id."><input type=hidden name=cid value=0>
<input type=hidden name=option value=com_suggestion><input type=hidden name=controller value=vote>
 <input type=hidden name=task value=edit><a href='javascript:void(0)' onclick='vote.submit()'>".JText::_('leavevote')."</a></form>";
}
if(!$del)
   if($thisuser->id||(!$this->settings->capcha&&!$this->settings->login))
   {
   $del= '<form name=vote method=post>
   <input type="hidden" name="value" value="1" />
   <input type="hidden" name="option" value="com_suggestion" />
   <input type="hidden" name="task" value="save" />
   <input type="hidden" name="SID" value="'.$this->item->id.'" />
   <input type="hidden" name="controller" value="vote" />
   <input type=hidden name=cid value=0>
<a href="javascript:void(0)" onclick="vote.submit()">'.JText::_('leavevote')."</a></form>";
   }
else $del= "<form name=vote method=post><input type=hidden name=SID value=".$this->item->id."><input type=hidden name=cid value=0>
<input type=hidden name=option value=com_suggestion><input type=hidden name=controller value=vote>
 <input type=hidden name=task value=edit><a href='javascript:void(0)' onclick='vote.submit()'>".JText::_('leavevote')."</a></form>";
echo $del;
}
else echo '';?></td></tr>
<!--<tr><td>Average votes:</td><td><?php echo $this->item->noofVotes?(($this->avgvote)/($this->item->noofVotes)):0; ?></td>
<td><?php //echo $this->item->state?"<a href='javascript:void(0)' onclick='vote.submit()'>Vote this suggestion</a>":'';?>
</td></tr>-->
</table></p>
<?php
if(($this->item->UID!=0&&$this->item->UID==$thisuser->id)||$_COOKIE['suggest'.$this->item->id])
{
echo "<form name=sugg><input type=hidden name=cid value=".$this->item->id.">
<input type=hidden name=option value=com_suggestion><input type=hidden name=controller value=sugg>
 ";
 if($this->item->published)
 echo "<input type=hidden name=task value=unpublish><a href='javascript:void(0)' onclick='sugg.submit()'> Unpublish</a></form>";
 else 
 echo "<input type=hidden name=task value=publish><a href='javascript:void(0)' onclick='sugg.submit()'> Publish</a></form>";
}
?>
<h2><?php echo JText::_('SUGGCOMMENTSTITLE')?>:</h2>
<?php
for($i=0;$i<count($this->comments);$i++)
{
   $comment=$this->comments[$i];
   if(($comment->UID&&$comment->UID==$thisuser->id)||$_COOKIE['comment'.$comment->id])
   {   $disable="<form name='comment".$comment->id."'><input type=hidden name=cid value=".$comment->id.">
<input type=hidden name=option value=com_suggestion><input type=hidden name=controller value=comment><input type=hidden name=SID value=".$this->item->id.">";
if($comment->published)
$disable.=" <input type=hidden name=task value=unpublish><a href='javascript:void(0)' onclick='comment".$comment->id.".submit()'> Unpublish</a></form>";
else
$disable.=" <input type=hidden name=task value=publish><a href='javascript:void(0)' onclick='comment".$comment->id.".submit()'> Publish</a></form>";
   }else $disable='';
   if($this->settings->show)
   {
      if($comment->UID==0)
      {
         $username='Anonymous';
      }
      else
      {
         $user = JFactory::getUser($comment->UID);         
         $username=$user->name;
      }
   }
?>
<h3><?php echo force_sp(str_replace('&nbsp;','&nbsp; ',$comment->title),30);?></h3><?php echo $this->settings->show?' By: '.$username:''; ?>
<p><?php echo force_sp(str_replace('&nbsp;','&nbsp; ',$comment->description),50);?></p>
<h4><?php echo $disable;?></h4>
<?php } ?>
<h2><?php echo JText::_('SUGGVOTESTITLE')?>:</h2>
<ul>
<?php
for($i=0;$i<count($this->votes);$i++)
{
   $vote=$this->votes[$i];
  if($vote->UID==0)
  {
     $username='Anonymous';
  }
  else
  {
     $user = JFactory::getUser($vote->UID);
     $username=$user->name;
  }
?>
<li><?php echo $username;?>
<?php } ?>
</ul>
</div>
