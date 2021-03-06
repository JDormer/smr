<?php
if(isset($CreateGameMessage)) {
	echo $CreateGameMessage;
} ?>
<table class="standard ajax" id="GameList">
	<tr>
		<th>Players</th>
		<th>Current Turn</th>
		<th></th>
	</tr><?php
	foreach($ChessGames as $ChessGame) { ?>
		<tr>
			<td><?php
				$WhitePlayer =& $ChessGame->getWhitePlayer();
				$BlackPlayer =& $ChessGame->getBlackPlayer();
				if($WhitePlayer == null) {
					?>Unknown<?php
				}
				else {
					echo $WhitePlayer->getLinkedDisplayName(false);
				} ?> vs <?php 
				if($BlackPlayer == null) {
					?>Unknown<?php
				}
				else {
					echo $BlackPlayer->getLinkedDisplayName(false);
				} ?>
			</td>
			<td><?php
				$CurrentTurnPlayer =& $ChessGame->getCurrentTurnPlayer();
				if($CurrentTurnPlayer == null) {
					?>Unknown<?php
				}
				else {
					echo $CurrentTurnPlayer->getLinkedDisplayName(false);
				} ?>
			</td>
			<td>
				<div class="buttonA"><a class="buttonA" href="<?php echo $ChessGame->getPlayGameHREF(); ?>">&nbsp;Play&nbsp;</a></div>
			</td>
		</tr><?php
	} ?>
</table><?php

if(count($PlayerList) > 0) { ?>
	<form action="<?php echo Globals::getChessCreateHREF(); ?>" method="POST">
		<label for="player_id">Challenge: </label>
		<select id="player_id" name="player_id"><?php
			foreach($PlayerList as $PlayerID => $PlayerName) {
				?><option value="<?php echo $PlayerID; ?>"><?php echo $PlayerName; ?></option><?php
			} ?>
		</select><input type="submit"/>
	</form><?php
}
else { ?>
	<p>You have challenged every player.</p><?php
}

if(isset($NPCList)) {
	if(count($NPCList) > 0) { ?>
		<form action="<?php echo Globals::getChessCreateHREF(); ?>" method="POST">
			<label for="player_id">Challenge NPC: </label>
			<select id="player_id" name="player_id"><?php
				foreach($NPCList as $PlayerID => $PlayerName) {
					?><option value="<?php echo $PlayerID; ?>"><?php echo $PlayerName; ?></option><?php
				} ?>
			</select><input type="submit"/>
		</form><?php
	}
	else { ?>
		<p>You have challenged every NPC.</p><?php
	}
} ?>