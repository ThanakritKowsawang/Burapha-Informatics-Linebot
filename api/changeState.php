<?php
include_once('../db.php');
include_once('responseClass.php');

$userId = $_REQUEST['userId'];
$userState = $_REQUEST['userState'];
changeState($userId, $userState);

function changeState($userId, $userState) {
	$objConn = new db();
	$objConn->connect();

	// update user state
	$success = $objConn->query("UPDATE `chatbotUser` SET `userState` = '" . $userState . "' WHERE `userId` = '" . $userId . "'");
	$response = new Success();
	$response->success = $success;
	echo json_encode($response);
}
?>