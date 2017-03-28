<?php
include_once('../db.php');

$userId = $_REQUEST['userId'];
getUserStudentId($userId);

function getUserStudentId($userId) {
	$objConn = new db();
	$objConn->connect();
	$objConn->query("SELECT `studentId` FROM `chatbotUser` WHERE `userId` = '" . $userId . "'");
	$response = array();
	foreach ($objConn->results->fetchall() as $temp) {
		$response[] = $temp['studentId'];
	}
	echo json_encode($response);
}
?>