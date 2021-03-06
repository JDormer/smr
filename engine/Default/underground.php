<?php

if ($player->getAlignment() >= 100) {
	create_error('You are not allowed to come in here!');
}

if(!$player->getSector()->hasLocation($var['LocationID'])) {
	create_error('That location does not exist in this sector');
}
$location =& SmrLocation::getLocation($var['LocationID']);
if(!$location->isUG()) {
	create_error('There is no underground here.');
}

$template->assign('PageTopic','Underground Headquarters');

require_once(get_file_loc('menu.inc'));
create_ug_menu();

require_once(get_file_loc('gov.functions.inc'));
displayBountyList($PHP_OUTPUT,'UG',0);
displayBountyList($PHP_OUTPUT,'UG',$player->getAccountID());

if ($player->getAlignment() <= 99 && $player->getAlignment() >= -100) {
	$container = create_container('government_processing.php');
	transfer('LocationID');
	$PHP_OUTPUT.=create_echo_form($container);
	$PHP_OUTPUT.=create_submit('Become a gang member');
	$PHP_OUTPUT.=('</form>');
}
?>