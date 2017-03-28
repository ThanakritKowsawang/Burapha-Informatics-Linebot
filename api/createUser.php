<?php
include_once('../db.php');
include_once('responseClass.php');

$userId = $_REQUEST['userId'];
createUser($userId);

function createUser($userId) {
	$objConn = new db();
	$objConn->connect();
	$success = $objConn->query("INSERT INTO `chatbotUser` (`userId`) VALUES ('" . $userId . "')");
	$response = new Success();
	$response->success = $success;
	echo json_encode($response);
}
?>