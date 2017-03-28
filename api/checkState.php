<?php
include_once('../db.php');

$userId = $_REQUEST['userId'];
checkState($userId);

function checkState($userId) {
	$objConn = new db();
	$objConn->connect();
	$objConn->query("SELECT `userState` FROM `chatbotUser` WHERE `userId` = '" . $userId . "'");
	$response = array();
	foreach ($objConn->results->fetchall() as $temp) {
		$response[] = $temp['userState'];
	}
	echo json_encode($response);
}
?>