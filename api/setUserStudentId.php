<?php
include_once('../db.php');
include_once('responseClass.php');

$userId = $_REQUEST['userId'];
$studentId = $_REQUEST['studentId'];
setUserStudentId($userId, $studentId);

function setUserStudentId($userId, $studentId){
	$objConn = new db();
	$objConn->connect();
	$success = $objConn->query("UPDATE `chatbotUser` SET `studentId` = '" . $studentId . "' WHERE `userId` = '" . $userId . "'");
	$response = new Success();
	$response->success = $success;
	echo json_encode($response);
}
?>