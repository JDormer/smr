<?

$template->assign('PageTopic','VIEWING MESSAGE BOXES');

if(!isset($var['box_type_id']))
{
	$db->query('SELECT count(message_id),box_type_name,box_type_id FROM message_box_types LEFT JOIN message_boxes USING(box_type_id) GROUP BY box_type_id');
	$container=array();
	$container['url'] = 'skeleton.php';
	$container['body'] = 'box_view.php';

	$PHP_OUTPUT.=('<table class="standard">');
	$PHP_OUTPUT.=('<tr>');
	$PHP_OUTPUT.=('<th>Folder</th>');
	$PHP_OUTPUT.=('<th>Messages</th>');
	$PHP_OUTPUT.=('</tr>');
	while($db->nextRecord())
	{
		$container['box_type_id'] = $db->getField('box_type_id');
		$PHP_OUTPUT.='<tr>
						<td><a href="'.SmrSession::get_new_href($container).'">'.$db->getField('box_type_name').'</a></td>
						<td>'.$db->getField('count(message_id)').'</a></td>
					</tr>';
	}
	$PHP_OUTPUT.='</table>';
}
else
{
	$PHP_OUTPUT.=create_link(create_container('skeleton.php','box_view.php'),'Back<br />');
	$db2 = new SmrMySqlDatabase();
	$db->query('SELECT * FROM message_boxes WHERE box_type_id='.$var['box_type_id'].' ORDER BY send_time DESC');
	$container = array();
	$container['url'] = 'box_delete_processing.php';
	$container['box_type_id'] = $var['box_type_id'];
	if ($db->getNumRows())
	{
		$PHP_OUTPUT.=create_echo_form($container);
		$PHP_OUTPUT.=create_submit('Delete');
		$PHP_OUTPUT.=('&nbsp;');
		$PHP_OUTPUT.=('<select name="action" size="1" id="InputFields">');
		$PHP_OUTPUT.=('<option>Marked Messages</option>');
		$PHP_OUTPUT.=('<option>All Messages</option>');
		$PHP_OUTPUT.=('</select>');
	
		$PHP_OUTPUT.=('<br /><br />');
		$PHP_OUTPUT.=('Click the name to reply<br />');
		$PHP_OUTPUT.=('<table width="100%" class="standard">');
		
		while($db->nextRecord())
		{
			$gameID = $db->getField('game_id')>0?$db->getField('game_id'):false;
			$PHP_OUTPUT.=('<tr>');
			$PHP_OUTPUT.=('<td><input type="checkbox" name="message_id[]" value="'.$db->getField('message_id').'"></td>');
			if($gameID!==false)
				$sender =& SmrPlayer::getPlayer($db->getField('sender_id'), $db->getField('game_id'));
			$sender_acc =& SmrAccount::getAccount($db->getField('sender_id'));
			$container = array();
			$container['url'] = 'skeleton.php';
			$container['body'] = 'box_reply.php';
			$container['sender_id'] = $sender_acc->getAccountID();
			$container['game_id'] = $gameID;
			$PHP_OUTPUT.=('<td nowrap="nowrap">');
			
			$sender = 'From: '.$sender_acc->login.' ('.$sender_acc->account_id.')';
			if (is_object($sender)&&$sender_acc->login != $sender->getPlayerName())
				$sender .= ' a.k.a '.$sender->getPlayerName();
			if($gameID!==false)
				$PHP_OUTPUT.=create_link($container, $sender);
			else
				$PHP_OUTPUT.=$sender;
			$PHP_OUTPUT.='</td>';
			$PHP_OUTPUT.='<td>';
			$db2->query('SELECT * FROM game WHERE game_id = ' . $db->getField('game_id'));
			if ($db2->nextRecord()) $PHP_OUTPUT.=$db2->getField('game_name'); //$trader .= ' in ' . $db2->getField('game_name');
			else $PHP_OUTPUT.=('Game no longer exists'); //$trader .= ' in a game that no longer exists.';
			$PHP_OUTPUT.=('</td></tr><tr><td colspan="3">');
			$PHP_OUTPUT.=('Sent at ' . date(DATE_FULL_SHORT, $db->getField('send_time')));
			$PHP_OUTPUT.='</td>';
			$PHP_OUTPUT.=('</tr>');
			$PHP_OUTPUT.=('<tr>');
			$PHP_OUTPUT.=('<td width="100%" colspan="3">');
			$PHP_OUTPUT.= $db->getField('message_text');
			$PHP_OUTPUT.=('</td></tr>');
			
		}
		
		$PHP_OUTPUT.=('</table>');
		$PHP_OUTPUT.=('</form>');
		
	} else
		$PHP_OUTPUT.=('There are currently no messages in this box.');
}
?>